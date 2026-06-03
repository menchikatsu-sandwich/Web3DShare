<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\SupabaseStorage;

class ProfileController extends Controller
{
    public function creator(User $user)
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

        return view('creator.show', compact('user', 'stats', 'models'));
    }

    public function show(Request $r)
    {
        return $r->wantsJson()
            ? response()->json($r->user())
            : view('profile.index', ['user'=>$r->user()]);
    }

    public function update(Request $r)
    {
        $user = $r->user();

        $r->validate([
            'nickname'=>'nullable|string|max:100',
            'image'=>'nullable|image|max:5000'
        ]);

        try {
            if($r->hasFile('image')){
                // hapus lama
                SupabaseStorage::deleteProfile($user->profile_image_path);

                $path = SupabaseStorage::uploadProfile($user->id, $r->file('image'));

                $user->profile_image_path = $path;
            }

            $user->nickname = $r->nickname ?? $user->nickname;

            $user->save();
        } catch (\Exception $e) {
            \Log::error('Profile update error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return $r->wantsJson()
                ? response()->json(['error' => $e->getMessage()], 500)
                : back()->with('error', 'Profile update failed: ' . $e->getMessage());
        }

        return $r->wantsJson()
            ? response()->json($user)
            : back()->with('success', 'Profile updated.');
    }
}
