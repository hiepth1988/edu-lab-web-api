<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Research posts become posts with type = "research", and research topics become categories
 * with type = "research". Public URLs (/research/{slug}) keep their slugs.
 * The old research_* tables are left untouched as a backup.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('type', 20)->default('post')->after('id')->index();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('type', 20)->default('post')->after('id')->index();
        });

        if (! Schema::hasTable('research_posts')) {
            return;
        }

        DB::transaction(function () {
            $categoryIds = $this->copyTopics();
            $this->copyResearchPosts($categoryIds);
        });
    }

    public function down(): void
    {
        // Removes the copied rows; translations cascade.
        DB::table('posts')->where('type', 'research')->delete();
        DB::table('categories')->where('type', 'research')->delete();

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropColumn('type');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropColumn('type');
        });
    }

    /** @return array<int, int> research_topic_id => category_id */
    private function copyTopics(): array
    {
        $map = [];

        foreach (DB::table('research_topics')->orderBy('id')->get() as $topic) {
            $categoryId = DB::table('categories')->insertGetId([
                'type' => 'research',
                'created_at' => $topic->created_at,
                'updated_at' => $topic->updated_at,
            ]);
            $map[$topic->id] = $categoryId;

            foreach (DB::table('research_topic_translations')->where('research_topic_id', $topic->id)->get() as $t) {
                DB::table('category_translations')->insert([
                    'category_id' => $categoryId,
                    'locale' => $t->locale,
                    'slug' => $this->uniqueSlug('category_translations', $t->locale, $t->slug),
                    'name' => $t->name,
                    'created_at' => $t->created_at,
                    'updated_at' => $t->updated_at,
                ]);
            }
        }

        return $map;
    }

    /** @param array<int, int> $categoryIds */
    private function copyResearchPosts(array $categoryIds): void
    {
        foreach (DB::table('research_posts')->orderBy('id')->get() as $research) {
            $postId = DB::table('posts')->insertGetId([
                'type' => 'research',
                'category_id' => $categoryIds[$research->research_topic_id] ?? null,
                'status' => $research->status,
                'published_at' => $research->published_at,
                'show_cta' => false,
                'created_at' => $research->created_at,
                'updated_at' => $research->updated_at,
            ]);

            foreach (DB::table('research_post_translations')->where('research_post_id', $research->id)->get() as $t) {
                DB::table('post_translations')->insert([
                    'post_id' => $postId,
                    'locale' => $t->locale,
                    'slug' => $this->uniqueSlug('post_translations', $t->locale, $t->slug),
                    'title' => $t->title,
                    'excerpt' => $t->excerpt,
                    'content' => $t->content,
                    'meta_title' => $t->meta_title,
                    'meta_description' => $t->meta_description,
                    'og_image' => $t->og_image ?? null,
                    'canonical_url' => $t->canonical_url ?? null,
                    'created_at' => $t->created_at,
                    'updated_at' => $t->updated_at,
                ]);
            }
        }
    }

    // Slugs are unique per locale; only add a suffix if an article already uses the same slug.
    private function uniqueSlug(string $table, string $locale, string $slug): string
    {
        $candidate = $slug;
        $i = 2;

        while (DB::table($table)->where('locale', $locale)->where('slug', $candidate)->exists()) {
            $candidate = "{$slug}-{$i}";
            $i++;
        }

        return $candidate;
    }
};
