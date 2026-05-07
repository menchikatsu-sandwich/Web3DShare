<?php

namespace App\Http\Controllers;

use App\Models\Model3D;
use App\Models\ModelView;
use App\Models\Download;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Services\SupabaseStorage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ModelController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $r)
    {
        $q = Model3D::with(['user', 'category', 'tags']);

        if ($r->search) {
            $q->where('title', 'like', '%' . $r->search . '%');
        }

        if ($r->category) {
            $q->where('category_id', $r->category);
        }

        if ($r->tag) {
            $q->whereHas('tags', fn($t) => $t->where('slug', $r->tag));
        }

        // filter my_models
        if ($r->filter == 'my_models' && Auth::check()) {
            $q->where('user_id', Auth::id());
        }

        $models = $q->latest()->paginate(12);

        return $r->wantsJson()
            ? response()->json($models)
            : view('home', compact('models'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'title' => 'required',
            'model' => 'required|file|mimes:glb',
            'thumbnail' => 'required|image'
        ]);

        $user = $r->user();

        // limit upload user basic
        if (!$user->isVerifiedUploader()) {
            $count = Model3D::where('user_id', $user->id)
                ->whereMonth('created_at', now()->month)
                ->count();

            if ($count >= 5) {
                abort(403, 'limit');
            }
        }

        // upload file
        $modelPath = SupabaseStorage::uploadModel($user->id, $r->file('model'));
        $thumbPath = SupabaseStorage::uploadThumbnail($user->id, $r->file('thumbnail'));

        // create model
        $model = Model3D::create([
            'user_id' => $user->id,
            'category_id' => $r->category_id,
            'title' => $r->title,
            'description' => $r->description,
            'model_path' => $modelPath,
            'thumbnail_path' => $thumbPath
        ]);

        // handle tags
        if ($r->tags) {
            $tagNames = explode(',', $r->tags);
            $tagIds = [];

            foreach ($tagNames as $name) {
                $name = trim(strtolower($name));

                if (!$name) continue;

                $tag = Tag::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    [
                        'name' => $name,
                        'created_by' => $user->id
                    ]
                );

                $tagIds[] = $tag->id;
            }

            $model->tags()->sync(array_unique($tagIds));
        }

        return $r->wantsJson()
            ? response()->json($model)
            : redirect('/');
    }

    public function show(Request $r, Model3D $model)
    {
        // PERBAIKAN: Menambahkan withCount('models') ke relasi user
        // agar jumlah model yang diupload user bisa terhitung dan tampil di UI.
        $model->load([
            'tags', 
            'category',
            'user' => function($query) {
                $query->withCount('models');
            }
        ]);

        // TRACK VIEW
        ModelView::create([
            'model_id' => $model->id,
            'user_id' => $r->user()?->id,
            'viewed_at' => now()
        ]);

        $model->increment('view_count');

        // $recommendations = Model3D::where('category_id', $model->category_id)
        //     ->where('id', '!=', $model->id)
        //     ->inRandomOrder()
        //     ->limit(5)
        //     ->get();

        $recommendations = Model3D::where('category_id', $model->category_id)
        ->where('id', '!=', $model->id)
        ->latest() // Lebih cepat dari inRandomOrder
        ->limit(5)
        ->select('id', 'title', 'thumbnail_path') // Ambil yang perlu saja
        ->get();

        // PERBAIKAN: Langsung lempar ke view 'partial' tanpa dibungkus layout modal tambahan.
        // Ini bikin respons API jauh lebih cepat pas buka modal.
        if ($r->ajax()) {
            return view('model.partial', compact('model', 'recommendations'));
        }

        return view('model.show', compact('model', 'recommendations'));
    }

    public function destroy(Request $r, Model3D $model)
    {
        $this->authorize('delete', $model);

        $model->delete();

        return $r->wantsJson()
            ? response()->json(['msg' => 'deleted'])
            : back();
    }
}