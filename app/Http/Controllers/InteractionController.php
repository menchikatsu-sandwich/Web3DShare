<?php

namespace App\Http\Controllers;

use App\Models\Star;
use App\Models\Comment;
use App\Models\Download;
use App\Models\Model3D;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InteractionController extends Controller
{
    public function star(Request $r, Model3D $model)
    {
        $user = $r->user();

        DB::beginTransaction();

        try {
            $exist = Star::where('user_id', $user->id)
                ->where('model_id', $model->id)
                ->lockForUpdate()
                ->first();

            if ($exist) {
                $exist->delete();

                if ($model->stars_count > 0) {
                    $model->decrement('stars_count');
                }
            } else {
                Star::create([
                    'user_id' => $user->id,
                    'model_id' => $model->id
                ]);

                $model->increment('stars_count');
            }

            DB::commit();

            return $r->wantsJson()
                ? response()->json(['stars' => $model->stars_count])
                : back();

        } catch (\Exception $e) {
            DB::rollBack();
            abort(500, 'star error');
        }
    }

    public function comment(Request $r, Model3D $model)
    {
        $r->validate([
            'body' => 'required'
        ]);

        $comment = Comment::create([
            'model_id' => $model->id,
            'user_id' => $r->user()->id,
            'parent_id' => $r->parent_id,
            'body' => $r->body
        ]);

        $comment->load('user');

        return $r->wantsJson()
            ? response()->json($comment)
            : back();
    }

    public function deleteComment(Request $r, Comment $comment)
    {
        $user = $r->user();

        // Cek Hak Akses: Apakah dia admin, moderator, atau pemilik komentar tersebut
        // Sesuaikan properti 'role' dengan kolom yang ada di database User kamu
        $isAdminOrMod = $user && in_array($user->role, ['admin', 'moderator']);
        $isOwner = $user && $user->id === $comment->user_id;

        if (!$isOwner && !$isAdminOrMod) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus komentar ini.');
        }

        DB::beginTransaction();
        try {
            // Fungsi pembantu untuk menghapus replies secara rekursif/berantai
            $this->deleteRepliesRecursively($comment->id);

            // Hapus komentar utama
            $comment->delete();

            DB::commit();

            return $r->wantsJson()
                ? response()->json(['success' => true, 'message' => 'Komentar dan balasannya berhasil dihapus.'])
                : back()->with('success', 'Komentar berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            abort(500, 'Gagal menghapus komentar');
        }
    }

    // Fungsi pembantu rekursif untuk menjamin semua sub-reply terdalam ikut terhapus
    private function deleteRepliesRecursively($parentId)
    {
        $replies = Comment::where('parent_id', $parentId)->get();
        
        foreach ($replies as $reply) {
            // Cari lagi apakah sub-reply ini punya anak lagi di bawahnya
            $this->deleteRepliesRecursively($reply->id);
            // Hapus sub-reply tersebut
            $reply->delete();
        }
    }

    public function download(Request $r, Model3D $model)
    {
        Download::create([
            'model_id' => $model->id,
            'downloaded_at' => now()
        ]);

        $model->increment('download_count');

        return redirect($model->modelUrl());
    }
}