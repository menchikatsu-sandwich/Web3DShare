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