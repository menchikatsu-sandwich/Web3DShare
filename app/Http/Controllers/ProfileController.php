<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SupabaseStorage;

class ProfileController extends Controller
{
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
