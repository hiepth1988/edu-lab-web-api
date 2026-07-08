<?php

namespace App\Observers;

use App\Models\CaseStudy;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\ResearchPost;
use App\Models\Solution;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CacheInvalidationObserver
{
    private const TAGS = [
        Post::class => ['posts'],
        Category::class => ['posts', 'categories'],
        Tag::class => ['posts', 'tags'],
        Solution::class => ['solutions'],
        Product::class => ['products'],
        CaseStudy::class => ['case-studies'],
        ResearchPost::class => ['research'],
        Page::class => ['pages'],
    ];

    public function saved(Model $model): void
    {
        $this->flush($model);
    }

    public function deleted(Model $model): void
    {
        $this->flush($model);
    }

    private function flush(Model $model): void
    {
        $tags = self::TAGS[$model::class] ?? null;

        if ($tags) {
            Cache::tags($tags)->flush();
        }
    }
}
