<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use App\Models\Model3D;
use App\Models\Report;
use App\Models\User;
use App\Models\VerificationRequest;
use App\Services\MetadataCache;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $stats = $this->panelStats();
        $payload = [
            'stats' => $stats,
            'dashboard' => $this->dashboardOverview(),
            'models' => Model3D::with('user')->latest()->limit(60)->get(),
            'users' => User::withCount('models')->withSum('models', 'download_count')->latest()->limit(80)->get(),
            'reports' => Report::with(['model3d.user', 'reporter', 'reviewer'])->latest()->limit(80)->get(),
            'requests' => VerificationRequest::with('user')->where('request_status', 'pending')->latest()->limit(80)->get(),
            'categories' => Category::withCount('models')->orderBy('name')->get(),
        ];

        if ($request->wantsJson()) {
            return $this->apiData($payload);
        }

        return view('admin.panel', $payload);
    }

    private function panelStats()
    {
        return [
            'models' => Model3D::count(),
            'users' => User::count(),
            'reports' => Report::whereIn('report_status', ['pending', 'reviewed'])->count(),
            'verify' => VerificationRequest::where('request_status', 'pending')->count(),
        ];
    }

    private function dashboardOverview(): array
    {
        $topModels = Model3D::with('user')
            ->orderByDesc('view_count')
            ->orderByDesc('download_count')
            ->limit(5)
            ->get(['id', 'user_id', 'title', 'view_count', 'download_count']);
        $categoryDistribution = Category::withCount('models')
            ->get(['id', 'name'])
            ->filter(fn (Category $category) => $category->models_count > 0)
            ->sortByDesc('models_count')
            ->take(5)
            ->values();
        $verifiedUsers = User::where('role', 'user')->where('upload_tier', 'verified')->count();
        $basicUsers = User::where('role', 'user')->where('upload_tier', 'basic')->count();
        $staffUsers = User::whereIn('role', ['admin', 'moderator'])->count();

        return [
            'engagement' => [
                'views' => (int) Model3D::sum('view_count'),
                'downloads' => (int) Model3D::sum('download_count'),
            ],
            'category_distribution' => $categoryDistribution,
            'category_distribution_max' => max(1, (int) $categoryDistribution->max('models_count')),
            'tiers' => [
                'verified' => $verifiedUsers,
                'basic' => $basicUsers,
                'staff' => $staffUsers,
                'total' => $verifiedUsers + $basicUsers + $staffUsers,
            ],
            'queue' => [
                'pending_reports' => Report::where('report_status', 'pending')->count(),
                'reviewed_reports' => Report::where('report_status', 'reviewed')->count(),
                'verification_requests' => VerificationRequest::where('request_status', 'pending')->count(),
            ],
            'top_models' => $topModels,
        ];
    }

    public function status()
    {
        return response()->json($this->panelStats());
    }

    public function deleteModel($id)
    {
        $model = Model3D::findOrFail($id);
        $model->delete();

        return request()->wantsJson()
            ? $this->apiData([], 'Model has been taken down.')
            : back()->with('success', 'Model has been taken down.');
    }

    public function promote($id)
    {
        $user = User::findOrFail($id);
        if ($user->role !== 'user') {
            return $this->userManagementError('Only standard users can be promoted to moderator.');
        }

        $user->role = 'moderator';
        $user->save();

        return request()->wantsJson()
            ? $this->apiData(['user' => $user], "{$user->username} promoted to moderator.")
            : back()->with('success', "{$user->username} promoted to moderator.");
    }

    public function demote($id)
    {
        $user = User::findOrFail($id);
        if ($user->role !== 'moderator') {
            return $this->userManagementError('Only moderators can be demoted to user.');
        }

        $user->role = 'user';
        $user->save();

        return request()->wantsJson()
            ? $this->apiData(['user' => $user], "{$user->username} demoted to user.")
            : back()->with('success', "{$user->username} demoted to user.");
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === request()->user()->id) {
            return $this->userManagementError('You cannot delete your own administrator account.');
        }

        if ($user->role === 'admin') {
            return $this->userManagementError('Administrator accounts cannot be deleted from user management.');
        }

        // Delete all models owned by the user.
        Model3D::where('user_id', $user->id)->delete();

        $user->delete();

        return request()->wantsJson()
            ? $this->apiData([], 'User deleted.')
            : back()->with('success', 'User deleted.');
    }

    // CATEGORY
    public function storeCategory(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        $category = Category::create([
            'name' => $data['name'],
            'created_by' => $request->user()->id,
        ]);

        MetadataCache::forgetCategories();

        return $request->wantsJson()
            ? $this->apiData(['category' => $category], 'Category added.', 201)
            : back()->with('success', 'Category added.');
    }

    public function deleteCategory($id)
    {
        $category = Category::withCount('models')->findOrFail($id);

        if ($category->models_count > 0) {
            return request()->wantsJson()
                ? $this->apiError('Category cannot be deleted while models are still using it.', 409)
                : back()->with('error', 'Category cannot be deleted while models are still using it.');
        }

        $category->delete();
        MetadataCache::forgetCategories();

        return request()->wantsJson()
            ? $this->apiData([], 'Category deleted.')
            : back()->with('success', 'Category deleted.');
    }

    public function reports()
    {
        return Report::with('model3d')->latest()->get();
    }

    public function resolveReport(Request $r, Report $report)
    {
        $report->update([
            'report_status' => 'resolved',
            'reviewed_by' => $r->user()->id,
        ]);

        return $r->wantsJson()
            ? $this->apiData(['report' => $report->fresh('model3d.user', 'reporter', 'reviewer')], 'Report marked as resolved.')
            : back()->with('success', 'Report marked as resolved.');
    }

    public function reviewReport(Request $r, Report $report)
    {
        $report->update([
            'report_status' => 'reviewed',
            'reviewed_by' => $r->user()->id,
        ]);

        return $r->wantsJson()
            ? $this->apiData(['report' => $report->fresh('model3d.user', 'reporter', 'reviewer')], 'Report moved to in review.')
            : back()->with('success', 'Report moved to in review.');
    }

    private function userManagementError(string $message)
    {
        return request()->wantsJson()
            ? $this->apiError($message, 422)
            : back()->with('error', $message);
    }
}
