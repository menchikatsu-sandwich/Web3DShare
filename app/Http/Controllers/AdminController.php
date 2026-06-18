<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Model3D;
use App\Models\Category;
use App\Models\Report;
use App\Models\VerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $stats = $this->panelStats();

        return view('admin.panel', [
            'stats'    => $stats,
            'models'   => Model3D::with('user')->latest()->limit(60)->get(),
            'users'    => User::latest()->limit(80)->get(),
            'reports'  => Report::with(['model3d.user', 'reporter', 'reviewer'])->latest()->limit(80)->get(),
            'requests' => VerificationRequest::with('user')->where('request_status', 'pending')->latest()->limit(80)->get(),
            'categories'=> Category::withCount('models')->orderBy('name')->get()
        ]);
    }

    private function panelStats()
    {
        return [
            'models' => Model3D::count(),
            'users'  => User::count(),
            'reports'=> Report::whereIn('report_status', ['pending', 'reviewed'])->count(),
            'verify' => VerificationRequest::where('request_status', 'pending')->count()
        ];
    }

    public function status()
    {
        return response()->json($this->panelStats());
    }

    public function deleteModel($id)
    {
        Model3D::findOrFail($id)->delete();
        return back()->with('success', 'Model has been taken down.');
    }

    public function promote($id)
    {
        $user = User::findOrFail($id);
        $user->role = 'moderator';
        $user->save();

        return back()->with('success', "{$user->username} promoted to moderator.");
    }

    public function demote($id)
    {
        $user = User::findOrFail($id);
        $user->role = 'user';
        $user->save();

        return back()->with('success', "{$user->username} demoted to user.");
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // Delete all models owned by the user.
        Model3D::where('user_id', $user->id)->delete();

        $user->delete();

        return back()->with('success', 'User deleted.');
    }

    // CATEGORY
    public function storeCategory()
    {
        request()->validate(['name' => ['required', 'string', 'max:100', 'unique:categories,name']]);

        Category::create([
            'name'       => request('name'),
            'created_by' => Auth::id(), // ← fix: ambil id user yang login
        ]);

        return back()->with('success', 'Category added.');
    }

    public function deleteCategory($id)
    {
        $category = Category::withCount('models')->findOrFail($id);

        if ($category->models_count > 0) {
            return back()->with('error', 'Category cannot be deleted while models are still using it.');
        }

        $category->delete();

        return back()->with('success', 'Category deleted.');
    }

    public function reports()
    {
        return Report::with('model3d')->latest()->get();
    }

    public function resolveReport(Request $r, Report $report)
    {
        $report->update([
            'report_status'=>'resolved',
            'reviewed_by'=>$r->user()->id
        ]);

        return $r->wantsJson()
            ? response()->json(['msg'=>'done'])
            : back()->with('success', 'Report marked as resolved.');
    }

    public function reviewReport(Request $r, Report $report)
    {
        $report->update([
            'report_status' => 'reviewed',
            'reviewed_by' => $r->user()->id,
        ]);

        return $r->wantsJson()
            ? response()->json(['msg' => 'reviewed'])
            : back()->with('success', 'Report marked as reviewed.');
    }
}
