<?php

namespace App\Providers;

use App\Models\CaseStudy;
use App\Models\Category;
use App\Models\Lead;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\ResearchPost;
use App\Models\Solution;
use App\Models\Tag;
use App\Observers\AuditLogObserver;
use App\Observers\CacheInvalidationObserver;
use Illuminate\Cache\RateLimiting\Limit;
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
        foreach ([Post::class, Category::class, Tag::class, Solution::class, Product::class, CaseStudy::class, ResearchPost::class, Lead::class] as $model) {
            $model::observe(AuditLogObserver::class);
        }

        foreach ([Post::class, Category::class, Tag::class, Solution::class, Product::class, CaseStudy::class, ResearchPost::class, Page::class] as $model) {
            $model::observe(CacheInvalidationObserver::class);
        }

        $this->registerRateLimiters();
    }

    private function registerRateLimiters(): void
    {
        RateLimiter::for('api', function ($request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('auth', function ($request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('leads', function ($request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('newsletter', function ($request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('search', function ($request) {
            return Limit::perMinute(30)->by($request->ip());
        });
    }
}
