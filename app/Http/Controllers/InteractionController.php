<?php

namespace App\Http\Controllers;

use App\Models\Star;
use App\Models\Comment;
use App\Models\Download;
use App\Models\Model3D;
use Illuminate\Http\Request;

class InteractionController extends Controller
{
    public function star(Request $r, Model3D $model)
    {
        $user = $r->user();

        $exist = Star::where('user_id',$user->id)
            ->where('model_id',$model->id)
            ->first();

        if($exist){
            $exist->delete();
            $model->decrement('stars_count');
        }else{
            Star::create([
                'user_id'=>$user->id,
                'model_id'=>$model->id
            ]);
            $model->increment('stars_count');
        }

        return $r->wantsJson()
            ? response()->json(['stars'=>$model->stars_count])
            : back();
    }

    public function comment(Request $r, Model3D $model)
    {
        $r->validate([
            'body'=>'required'
        ]);

        Comment::create([
            'model_id'=>$model->id,
            'user_id'=>$r->user()->id,
            'parent_id'=>$r->parent_id,
            'body'=>$r->body
        ]);

        return $r->wantsJson()
            ? response()->json(['msg'=>'ok'])
            : back();
    }

    public function download(Request $r, Model3D $model)
    {
        Download::create([
            'model_id'=>$model->id,
            'downloaded_at'=>now()
        ]);

        $model->increment('download_count');

        return redirect(
            config('app.supabase_url')
            .'/storage/v1/object/public/model-assets/'
            .$model->model_path
        );
    }
}