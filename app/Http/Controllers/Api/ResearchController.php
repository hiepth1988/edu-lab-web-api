<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;

// Research items are posts with type = "research" and use the same fields, response and
// behaviour as articles. "topic" repeats "category" for older front-end code.
class ResearchController extends PostController
{
    protected string $type = Post::TYPE_RESEARCH;

    protected string $cacheTag = 'research';

    protected function summary(Post $post, string $locale): array
    {
        $summary = parent::summary($post, $locale);

        return [...$summary, 'topic' => $summary['category']];
    }
}
