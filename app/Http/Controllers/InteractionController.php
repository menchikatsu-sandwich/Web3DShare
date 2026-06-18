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
            \Log::error('Star toggle error: ' . $e->getMessage(), [
                'user_id' => $user?->id,
                'model_id' => $model->id,
            ]);

            return $r->wantsJson()
                ? response()->json(['error' => 'Failed to update star.'], 500)
                : back()->with('error', 'Failed to update star.');
        }
    }

    public function comment(Request $r, Model3D $model)
    {
        $r->validate([
            'body' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'exists:comments,id'],
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
            : back()->with('success', 'Comment posted.');
    }

    public function deleteComment(Request $r, Comment $comment)
    {
        $user = $r->user();

        // Access check: admin, moderator, or comment owner.
        $isAdminOrMod = $user && in_array($user->role, ['admin', 'moderator']);
        $isOwner = $user && $user->id === $comment->user_id;

        if (!$isOwner && !$isAdminOrMod) {
            return $r->wantsJson()
                ? response()->json(['error' => 'You do not have access to delete this comment.'], 403)
                : back()->with('error', 'You do not have access to delete this comment.');
        }

        DB::beginTransaction();
        try {
            // Delete replies recursively.
            $this->deleteRepliesRecursively($comment->id);

            $comment->delete();

            DB::commit();

            return $r->wantsJson()
                ? response()->json(['success' => true, 'message' => 'Comment and replies deleted.'])
                : back()->with('success', 'Comment deleted.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Comment delete error: ' . $e->getMessage(), [
                'user_id' => $user?->id,
                'comment_id' => $comment->id,
            ]);

            return $r->wantsJson()
                ? response()->json(['error' => 'Failed to delete comment.'], 500)
                : back()->with('error', 'Failed to delete comment.');
        }
    }

    // Recursive helper to delete every nested reply.
    private function deleteRepliesRecursively($parentId)
    {
        $replies = Comment::where('parent_id', $parentId)->get();
        
        foreach ($replies as $reply) {
            $this->deleteRepliesRecursively($reply->id);
            $reply->delete();
        }
    }

    public function download(Request $r, Model3D $model)
    {
        $isOwnerDownload = $r->user() && $r->user()->id === $model->user_id;

        if (!$isOwnerDownload) {
            Download::create([
                'model_id' => $model->id,
                'downloaded_at' => now()
            ]);

            $model->increment('download_count');
        }

        return redirect($model->modelUrl());
    }
}
