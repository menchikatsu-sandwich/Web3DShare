<?php

namespace App\Http\Controllers;

use App\Models\Model3D;
use App\Models\ModelView;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\SupabaseStorage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon; 
use App\Services\MetadataCache;

class ModelController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $r)
    {
        $currentUser = $r->user() ?: Auth::guard('sanctum')->user();

        // 1. Base query with relationships.
        $q = Model3D::select([
                'id',
                'user_id',
                'category_id',
                'title',
                'description',
                'thumbnail_path',
                'download_count',
                'stars_count',
                'view_count',
                'created_at',
            ])
            ->with([
                'user:id,username,nickname,upload_tier,profile_image_path',
                'category:id,name',
                'tags:id,name,slug',
            ]);

        // 2. Search, case-insensitive and including creators/tags.
        if ($r->filled('search')) {
            // Normalize the keyword for case-insensitive matching.
            $search = strtolower($r->search);
            
            $q->where(function($subQuery) use ($search) {
                $subQuery->where(DB::raw('LOWER(title)'), 'like', '%' . $search . '%')
                        ->orWhere(DB::raw('LOWER(description)'), 'like', '%' . $search . '%')
                        // Search by creator name, username, or nickname.
                        ->orWhereHas('user', function($userQuery) use ($search) {
                            $userQuery->where(DB::raw('LOWER(username)'), 'like', '%' . $search . '%')
                                    ->orWhere(DB::raw('LOWER(nickname)'), 'like', '%' . $search . '%');
                        })
                        // Search by tag.
                        ->orWhereHas('tags', function($tagQuery) use ($search) {
                            $tagQuery->where(DB::raw('LOWER(name)'), 'like', '%' . $search . '%');
                        });
            });
        }

        // 3. Category and tag filters.
        if ($r->category) {
            $q->where('category_id', $r->category);
        }

        if ($r->tag) {
            // Match by slug because the front-end sends tag slugs, not numeric ids.
            // This avoids PostgreSQL "invalid input syntax for type bigint" errors.
            $q->whereHas('tags', fn($t) => $t->where('slug', $r->tag));
        }
        // 4. My Models vs public Explore filter.
        if ($r->filter == 'my_models' && $currentUser) {
            $q->where('user_id', $currentUser->id);
        }

        // 5. Timeframe filter.
        if ($r->filled('timeframe') && $r->timeframe !== 'all_time') {
            if ($r->timeframe === 'this_month') {
                $q->where('created_at', '>=', Carbon::now()->startOfMonth());
            } elseif ($r->timeframe === 'this_week') {
                $q->where('created_at', '>=', Carbon::now()->startOfWeek());
            }
        }

        // 6. Sorting.
        $sort = $r->get('sort', 'latest');
        
        switch ($sort) {
            case 'top_downloads':
                $q->orderBy('download_count', 'desc');
                break;
            case 'top_views':
                $q->orderBy('view_count', 'desc');
                break;
            case 'top_stars':
                $q->orderBy('stars_count', 'desc');
                break;
            case 'latest':
            default:
                $q->latest();
                break;
        }

        // 7. Pagination while preserving the query string.
        $models = $q->paginate(12)->withQueryString();

        // Load categories and tags for the home view filters.
        $categories = $this->cachedCategories();
        $tags = $this->cachedPopularTags();

        // 8. Return data in the requested format.
        if ($r->wantsJson()) {
            return $this->apiData([
                'models' => $models,
                'categories' => $categories,
                'tags' => $tags,
                'filters' => [
                    'search' => $r->get('search'),
                    'category' => $r->get('category'),
                    'tag' => $r->get('tag'),
                    'filter' => $r->get('filter'),
                    'timeframe' => $r->get('timeframe', 'all_time'),
                    'sort' => $sort,
                ],
            ]);
        }

        return view('home', compact('models', 'categories', 'tags'));
    }

    public function store(Request $r)
    {
        $limits = config('web3dshare.limits');
        $validator = Validator::make($r->all(), [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'tags' => ['nullable', 'string', 'max:500'],
            'model' => [
                'required',
                'file',
                'max:'.$limits['model_upload_kb'],
                function ($attribute, $value, $fail) {
                    if (!$value->isValid()) {
                        $fail('The model upload did not complete. Please choose the file again.');
                        return;
                    }

                    if (strtolower($value->getClientOriginalExtension()) !== 'glb') {
                        $fail('The model must be a .glb file.');
                    }
                },
            ],
            'thumbnail' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp,gif',
                'max:'.$limits['thumbnail_upload_kb'],
                function ($attribute, $value, $fail) {
                    if (!$value->isValid()) {
                        $fail('The thumbnail upload did not complete. Please choose the image again.');
                    }
                },
            ],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        if ($validator->fails()) {
            \Log::warning('Model upload validation failed', [
                'user_id' => $r->user()?->id,
                'errors' => $validator->errors()->toArray(),
                'model_file' => $r->file('model')?->getClientOriginalName(),
                'model_size' => $r->file('model')?->getSize(),
                'model_mime' => $r->file('model')?->getClientMimeType(),
                'thumbnail_file' => $r->file('thumbnail')?->getClientOriginalName(),
                'thumbnail_size' => $r->file('thumbnail')?->getSize(),
                'thumbnail_mime' => $r->file('thumbnail')?->getClientMimeType(),
                'category_id' => $r->category_id,
            ]);

            return $this->apiError('Upload validation failed.', 422, $validator->errors());
        }

        $user = $r->user();

        // limit upload user basic
        if (!$user->isVerifiedUploader()) {
            $count = Model3D::where('user_id', $user->id)
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count();

            if ($count >= $limits['basic_monthly_uploads']) {
                return $this->apiError(
                    'Upload limit reached for this month. Please wait until next month or upgrade to verified uploader.',
                    403,
                    [
                    'next_reset' => now()->addMonthNoOverflow()->startOfMonth()->toDateString(),
                    ]
                );
            }
        }

        try {
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

            $this->syncTagsFromString($model, $r->tags, $user->id);

            MetadataCache::forgetPopularTags();

            return $this->apiData([
                'model' => $model->fresh(['category', 'tags', 'user']),
            ], 'Model uploaded.', 201);
        } catch (\Exception $e) {
            \Log::error('Model upload error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return $this->apiError($e->getMessage(), 500);
        }
    }

    public function show(Request $r, Model3D $model)
    {
        // Load model counts on the user relation so the UI can show published totals.
        $model->load([
            'tags', 
            'category',
            'comments.user',
            'user' => function($query) {
                $query->withCount('models');
            }
        ]);

        $viewCounted = $this->trackViewOncePerCooldown($r, $model);

        // $recommendations = Model3D::where('category_id', $model->category_id)
        //     ->where('id', '!=', $model->id)
        //     ->inRandomOrder()
        //     ->limit(5)
        //     ->get();

        $recommendations = Model3D::with('category:id,name')
            ->where('category_id', $model->category_id)
            ->where('id', '!=', $model->id)
            ->latest()
            ->limit(5)
            ->select('id', 'category_id', 'title', 'thumbnail_path')
            ->get();
        $categories = $this->cachedCategories();
        $authorModelCount = Model3D::where('user_id', $model->user_id)->count();
        $hasStarred = $r->user()
            ? $model->stars()->where('user_id', $r->user()->id)->exists()
            : false;
        $isManageContext = $r->query('from') === 'my_models'
            && $r->user()
            && $r->user()->id === $model->user_id;

        // Return the partial directly without wrapping it in an extra modal layout.
        // This keeps the modal response faster.
        if ($r->wantsJson()) {
            return $this->apiData([
                'model' => $model,
                'recommendations' => $recommendations,
                'categories' => $categories,
                'is_manage_context' => $isManageContext,
                'view_counted' => $viewCounted,
                'author_model_count' => $authorModelCount,
                'has_starred' => $hasStarred,
            ]);
        }

        if ($r->ajax()) {
            return view('model.partial', compact('model', 'recommendations', 'categories', 'isManageContext', 'authorModelCount', 'hasStarred'));
        }

        return view('model.show', compact('model', 'recommendations', 'categories', 'isManageContext', 'authorModelCount', 'hasStarred'));
    }

    public function update(Request $r, Model3D $model)
    {
        if (!$r->user() || $r->user()->cannot('update', $model)) {
            return $r->wantsJson()
                ? $this->apiError('You do not have access to edit this model.', 403)
                : back()->with('error', 'You do not have access to edit this model.');
        }

        $data = $r->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'category_id' => ['required', 'exists:categories,id'],
            'tags' => ['nullable', 'string', 'max:500'],
        ]);

        $model->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'category_id' => $data['category_id'],
        ]);

        $this->syncTagsFromString($model, $data['tags'] ?? '', $r->user()->id);

        return $r->wantsJson()
            ? $this->apiData(['model' => $model->fresh('category', 'tags')], 'Model updated.')
            : back()->with('success', 'Model updated.');
    }

    public function destroy(Request $r, Model3D $model)
    {
        $user = $r->user();
        if (!$user || $user->cannot('delete', $model)) {
            return $r->wantsJson()
                ? $this->apiError('You do not have access to delete this model.', 403)
                : back()->with('error', 'You do not have access to delete this model.');
        }

        $model->delete();

        return $r->wantsJson()
            ? $this->apiData([], 'Model deleted.')
            : redirect('/?filter=my_models')->with('success', 'Model deleted.');
    }

    private function syncTagsFromString(Model3D $model, ?string $tags, int $userId): void
    {
        $tagIds = [];
        $tagNames = collect(explode(',', $tags ?? ''))
            ->map(fn($name) => trim(strtolower($name)))
            ->filter()
            ->unique()
            ->take(config('web3dshare.limits.max_tags_per_model'));

        foreach ($tagNames as $name) {
            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'created_by' => $userId,
                ]
            );

            $tagIds[] = $tag->id;
        }

        $model->tags()->sync($tagIds);
    }

    private function trackViewOncePerCooldown(Request $request, Model3D $model): bool
    {
        $viewer = $request->user()
            ? 'u:'.$request->user()->id
            : 'g:'.sha1($request->ip().'|'.substr((string) $request->userAgent(), 0, 120));

        $key = 'web3dshare:viewed:'.$model->id.':'.$viewer;

        if (!Cache::store(config('web3dshare.cache.store'))->add($key, true, now()->addMinutes(config('web3dshare.engagement.view_cooldown_minutes')))) {
            return false;
        }

        ModelView::create([
            'model_id' => $model->id,
            'user_id' => $request->user()?->id,
            'viewed_at' => now()
        ]);

        $model->increment('view_count');
        $model->refresh();

        return true;
    }

    private function cachedCategories()
    {
        return MetadataCache::categories();
    }

    private function cachedPopularTags()
    {
        return MetadataCache::popularTags();
    }
}
