<?php

namespace App\Http\Controllers;

use App\Models\Star;
use App\Models\Comment;
use App\Models\Download;
use App\Models\Model3D;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
                $starred = false;

                if ($model->stars_count > 0) {
                    $model->decrement('stars_count');
                }
            } else {
                Star::create([
                    'user_id' => $user->id,
                    'model_id' => $model->id
                ]);

                $model->increment('stars_count');
                $starred = true;
            }

            DB::commit();

            return $r->wantsJson()
                ? $this->apiData([
                    'starred' => $starred,
                    'stars' => $model->fresh()->stars_count,
                ], $starred ? 'Model starred.' : 'Model unstarred.')
                : back();

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Star toggle error: ' . $e->getMessage(), [
                'user_id' => $user?->id,
                'model_id' => $model->id,
            ]);

            return $r->wantsJson()
                ? $this->apiError('Failed to update star.', 500)
                : back()->with('error', 'Failed to update star.');
        }
    }

    public function comment(Request $r, Model3D $model)
    {
        $validator = Validator::make($r->all(), [
            'body' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'exists:comments,id'],
        ]);

        if ($validator->fails()) {
            return $r->wantsJson()
                ? $this->apiError('Comment validation failed.', 422, $validator->errors())
                : back()->withErrors($validator)->withInput();
        }

        if ($r->filled('parent_id')) {
            $parentBelongsToModel = Comment::where('id', $r->parent_id)
                ->where('model_id', $model->id)
                ->exists();

            if (!$parentBelongsToModel) {
                return $r->wantsJson()
                    ? $this->apiError('Reply target does not belong to this model.', 422)
                    : back()->with('error', 'Reply target does not belong to this model.');
            }
        }

        $comment = Comment::create([
            'model_id' => $model->id,
            'user_id' => $r->user()->id,
            'parent_id' => $r->parent_id,
            'body' => $r->body
        ]);

        $comment->load('user');

        return $r->wantsJson()
            ? $this->apiData([
                'comment' => $comment,
                'html' => $this->renderCommentHtml($comment, $model->id),
                'comments_count' => Comment::where('model_id', $model->id)->count(),
                'parent_id' => $comment->parent_id,
                'parent_replies_count' => $comment->parent_id
                    ? Comment::where('parent_id', $comment->parent_id)->count()
                    : null,
            ], 'Comment posted.')
            : back()->with('success', 'Comment posted.');
    }

    public function deleteComment(Request $r, Comment $comment)
    {
        $user = $r->user();
        $deletedId = $comment->id;
        $parentId = $comment->parent_id;
        $modelId = $comment->model_id;

        if (!$user || $user->cannot('delete', $comment)) {
            return $r->wantsJson()
                ? $this->apiError('You do not have access to delete this comment.', 403)
                : back()->with('error', 'You do not have access to delete this comment.');
        }

        DB::beginTransaction();
        try {
            // Delete replies recursively.
            $this->deleteRepliesRecursively($comment->id);

            $comment->delete();

            DB::commit();

            return $r->wantsJson()
                ? $this->apiData([
                    'comment_id' => $deletedId,
                    'parent_id' => $parentId,
                    'comments_count' => Comment::where('model_id', $modelId)->count(),
                    'parent_replies_count' => $parentId
                        ? Comment::where('parent_id', $parentId)->count()
                        : null,
                ], 'Comment and replies deleted.')
                : back()->with('success', 'Comment deleted.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Comment delete error: ' . $e->getMessage(), [
                'user_id' => $user?->id,
                'comment_id' => $comment->id,
            ]);

            return $r->wantsJson()
                ? $this->apiError('Failed to delete comment.', 500)
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

    private function renderCommentHtml(Comment $comment, int $modelId): string
    {
        return view('model.comment-item', [
            'comment' => $comment,
            'allComments' => collect([$comment]),
            'modelId' => $modelId,
        ])->render();
    }

    public function download(Request $r, Model3D $model)
    {
        $isOwnerDownload = $r->user() && $r->user()->id === $model->user_id;
        $counted = false;

        if (!$isOwnerDownload && $this->shouldCountDownload($r, $model)) {
            Download::create([
                'model_id' => $model->id,
                'downloaded_at' => now()
            ]);

            $model->increment('download_count');
            $counted = true;
        }

        if ($r->wantsJson()) {
            return $this->apiData([
                'download_url' => $model->modelUrl(),
                'counted' => $counted,
                'download_count' => $model->fresh()->download_count,
            ]);
        }

        return redirect($model->modelUrl());
    }

    private function shouldCountDownload(Request $request, Model3D $model): bool
    {
        $viewer = $request->user()
            ? 'u:'.$request->user()->id
            : 'g:'.sha1($request->ip().'|'.substr((string) $request->userAgent(), 0, 120));

        return Cache::store(config('web3dshare.cache.store'))->add(
            'web3dshare:downloaded:'.$model->id.':'.$viewer,
            true,
            now()->addMinutes(config('web3dshare.engagement.download_cooldown_minutes'))
        );
    }
}
