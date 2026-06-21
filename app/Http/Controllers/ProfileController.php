<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use App\Services\SupabaseStorage;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function creator(Request $request, User $user)
    {
        $stats = [
            'models' => $user->models()->count(),
            'views' => $user->models()->sum('view_count'),
            'stars' => $user->models()->sum('stars_count'),
            'downloads' => $user->models()->sum('download_count'),
        ];

        $models = $user->models()
            ->select([
                'id',
                'user_id',
                'category_id',
                'title',
                'thumbnail_path',
                'download_count',
                'stars_count',
                'view_count',
                'created_at',
            ])
            ->with(['category:id,name', 'user:id,username,nickname,upload_tier,profile_image_path'])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        if ($request->wantsJson()) {
            return $this->apiData([
                'user' => $user->only([
                    'id',
                    'username',
                    'nickname',
                    'role',
                    'upload_tier',
                    'profile_image_path',
                    'profile_image_url',
                    'created_at',
                    'updated_at',
                ]),
                'stats' => $stats,
                'models' => $models,
            ]);
        }

        return view('creator.show', compact('user', 'stats', 'models'));
    }

    public function show(Request $r)
    {
        return $r->wantsJson()
            ? $this->apiData(['user' => $r->user()])
            : view('profile.index', ['user' => $r->user()]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        try {
            if ($request->hasFile('image')) {
                // Remove the old profile image.
                SupabaseStorage::deleteProfile($user->profile_image_path);

                $path = SupabaseStorage::uploadProfile($user->id, $request->file('image'));

                $user->profile_image_path = $path;
            }

            $user->nickname = $data['nickname'] ?? $user->nickname;

            $user->save();
        } catch (\Exception $e) {
            \Log::error('Profile update error: '.$e->getMessage(), [
                'user_id' => $user->id,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return $request->wantsJson()
                ? $this->apiError($e->getMessage(), 500)
                : back()->with('error', 'Profile update failed: '.$e->getMessage());
        }

        return $request->wantsJson()
            ? $this->apiData(['user' => $user], 'Profile updated.')
            : back()->with('success', 'Profile updated.');
    }
}
