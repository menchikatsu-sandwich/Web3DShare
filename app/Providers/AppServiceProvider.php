<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Model3D;
use App\Models\Report;
use App\Observers\Model3DObserver;
use App\Policies\CommentPolicy;
use App\Policies\Model3DPolicy;
use App\Policies\ReportPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Model3D::class, Model3DPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);
        Gate::policy(Report::class, ReportPolicy::class);

        Model3D::observe(Model3DObserver::class);

        RateLimiter::for('auth-actions', function (Request $request) {
            return Limit::perMinute(8)->by($request->ip());
        });

        RateLimiter::for('uploads', function (Request $request) {
            return Limit::perMinute(3)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('interactions', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('comments', function (Request $request) {
            return Limit::perMinute(12)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('reports', function (Request $request) {
            return Limit::perHour(8)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('verification', function (Request $request) {
            return Limit::perHour(3)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('downloads', function (Request $request) {
            $model = $request->route('model');
            $modelId = is_object($model) && method_exists($model, 'getKey')
                ? $model->getKey()
                : (string) $model;

            return Limit::perMinute(30)->by(($request->user()?->id ?: $request->ip()).'|'.$modelId);
        });

        RateLimiter::for('admin-actions', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
