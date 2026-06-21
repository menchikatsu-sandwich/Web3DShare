<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreModelRequest;
use App\Http\Requests\UpdateModelRequest;
use App\Models\Model3D;
use App\Models\ModelView;
use App\Models\Tag;
use App\Services\MetadataCache;
use App\Services\SupabaseStorage;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

            $q->where(function ($subQuery) use ($search) {
                $subQuery->where(DB::raw('LOWER(title)'), 'like', '%'.$search.'%')
                    ->orWhere(DB::raw('LOWER(description)'), 'like', '%'.$search.'%')
                        // Search by creator name, username, or nickname.
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where(DB::raw('LOWER(username)'), 'like', '%'.$search.'%')
                            ->orWhere(DB::raw('LOWER(nickname)'), 'like', '%'.$search.'%');
                    })
                        // Search by tag.
                    ->orWhereHas('tags', function ($tagQuery) use ($search) {
                        $tagQuery->where(DB::raw('LOWER(name)'), 'like', '%'.$search.'%');
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
            $q->whereHas('tags', fn ($t) => $t->where('slug', $r->tag));
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

    public function store(StoreModelRequest $request)
    {
        $limits = config('web3dshare.limits');
        $data = $request->validated();
        $user = $request->user();

        if ($this->hasReachedBasicUploadLimit($user, $limits['basic_monthly_uploads'])) {
            return $this->apiError(
                'Upload limit reached for this month. Please wait until next month or upgrade to verified uploader.',
                403,
                [
                    'next_reset' => now()->addMonthNoOverflow()->startOfMonth()->toDateString(),
                ]
            );
        }

        try {
            // upload file
            $modelPath = SupabaseStorage::uploadModel($user->id, $request->file('model'));
            $thumbPath = SupabaseStorage::uploadThumbnail($user->id, $request->file('thumbnail'));

            // create model
            $model = Model3D::create([
                'user_id' => $user->id,
                'category_id' => $data['category_id'],
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'model_path' => $modelPath,
                'thumbnail_path' => $thumbPath,
            ]);

            $this->syncTagsFromString($model, $data['tags'] ?? null, $user->id);

            MetadataCache::forgetPopularTags();

            return $this->apiData([
                'model' => $model->fresh(['category', 'tags', 'user']),
            ], 'Model uploaded.', 201);
        } catch (\Exception $e) {
            \Log::error('Model upload error: '.$e->getMessage(), [
                'user_id' => $user->id,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
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
            'user' => function ($query) {
                $query->withCount('models');
            },
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

    public function update(UpdateModelRequest $request, Model3D $model)
    {
        if (! $request->user() || $request->user()->cannot('update', $model)) {
            return $request->wantsJson()
                ? $this->apiError('You do not have access to edit this model.', 403)
                : back()->with('error', 'You do not have access to edit this model.');
        }

        $data = $request->validated();

        $model->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'category_id' => $data['category_id'],
        ]);

        $this->syncTagsFromString($model, $data['tags'] ?? '', $request->user()->id);

        return $request->wantsJson()
            ? $this->apiData(['model' => $model->fresh('category', 'tags')], 'Model updated.')
            : back()->with('success', 'Model updated.');
    }

    public function destroy(Request $r, Model3D $model)
    {
        $user = $r->user();
        if (! $user || $user->cannot('delete', $model)) {
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
            ->map(fn ($name) => trim(strtolower($name)))
            ->filter()
            ->unique()
            ->take(config('web3dshare.limits.max_tags_per_model'));

        // Sync an empty set directly instead of running the tag creation loop.
        if ($tagNames->isEmpty()) {
            $model->tags()->sync([]);

            return;
        }

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

    private function hasReachedBasicUploadLimit($user, int $monthlyLimit): bool
    {
        // Verified uploaders and staff accounts are intentionally exempt from the monthly limit.
        if ($user->hasUnlimitedUploads()) {
            return false;
        }

        $monthlyUploads = Model3D::where('user_id', $user->id)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        return $monthlyUploads >= $monthlyLimit;
    }

    private function trackViewOncePerCooldown(Request $request, Model3D $model): bool
    {
        $viewer = $request->user()
            ? 'u:'.$request->user()->id
            : 'g:'.sha1($request->ip().'|'.substr((string) $request->userAgent(), 0, 120));

        $key = 'web3dshare:viewed:'.$model->id.':'.$viewer;

        if (! Cache::store(config('web3dshare.cache.store'))->add($key, true, now()->addMinutes(config('web3dshare.engagement.view_cooldown_minutes')))) {
            return false;
        }

        ModelView::create([
            'model_id' => $model->id,
            'user_id' => $request->user()?->id,
            'viewed_at' => now(),
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
