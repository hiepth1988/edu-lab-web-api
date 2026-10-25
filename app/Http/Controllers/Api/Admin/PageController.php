<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Admin\Concerns\EnforcesPublishPermission;
use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Edits the fixed site pages (home, partnership, about...). Pages and their
 * sections are tied to front-end templates, so they can only be edited here,
 * not created or deleted. Slugs are read-only for the same reason.
 */
class PageController extends Controller
{
    use EnforcesPublishPermission;

    public function index(): JsonResponse
    {
        $pages = Page::with('translations:id,page_id,locale,slug,title')
            ->withCount('sections')
            ->orderBy('id')
            ->get();

        return response()->json(['data' => $pages]);
    }

    public function show(Page $page): JsonResponse
    {
        return response()->json(['data' => $this->loadPage($page)]);
    }

    public function update(Request $request, Page $page): JsonResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:draft,published'],
            'translations' => ['required', 'array'],
            'translations.*.title' => ['nullable', 'string', 'max:255'],
            'translations.*.excerpt' => ['nullable', 'string'],
            'translations.*.content' => ['nullable', 'string'],
            'translations.*.meta_title' => ['nullable', 'string', 'max:255'],
            'translations.*.meta_description' => ['nullable', 'string'],
            'translations.*.og_image' => ['nullable', 'string', 'max:2048'],
            'sections' => ['array'],
            'sections.*.id' => ['required', 'integer'],
            'sections.*.sort_order' => ['required', 'integer'],
            'sections.*.is_active' => ['required', 'boolean'],
            'sections.*.translations' => ['array'],
            'sections.*.translations.*.heading' => ['nullable', 'string', 'max:255'],
            'sections.*.translations.*.subheading' => ['nullable', 'string', 'max:255'],
            'sections.*.translations.*.body' => ['nullable', 'string'],
            'sections.*.translations.*.cta_label' => ['nullable', 'string', 'max:255'],
            'sections.*.translations.*.cta_url' => ['nullable', 'string', 'max:2048'],
            'sections.*.translations.*.extra' => ['nullable', 'array'],
        ]);

        $this->ensureCanSetStatus($data['status']);

        $locales = DB::table('locales')->pluck('code')->all();
        $sections = $page->sections()->get()->keyBy('id');

        foreach ($data['sections'] ?? [] as $i => $section) {
            if (! $sections->has($section['id'])) {
                throw ValidationException::withMessages(["sections.$i.id" => 'Section does not belong to this page.']);
            }
        }

        DB::transaction(function () use ($page, $data, $locales, $sections) {
            $page->update(['status' => $data['status']]);

            foreach ($data['translations'] as $locale => $payload) {
                $translation = $page->translations()->where('locale', $locale)->first();
                // Only existing translations are edited: each one owns a slug that a front route depends on.
                if (! $translation || ! in_array($locale, $locales, true)) {
                    continue;
                }

                $translation->update([
                    'title' => $payload['title'] ?? $translation->title,
                    'excerpt' => $payload['excerpt'] ?? null,
                    'content' => $payload['content'] ?? null,
                    'meta_title' => $payload['meta_title'] ?? null,
                    'meta_description' => $payload['meta_description'] ?? null,
                    'og_image' => $payload['og_image'] ?? null,
                ]);
            }

            foreach ($data['sections'] ?? [] as $payload) {
                $section = $sections->get($payload['id']);
                $section->update([
                    'sort_order' => $payload['sort_order'],
                    'is_active' => $payload['is_active'],
                ]);

                foreach ($payload['translations'] ?? [] as $locale => $fields) {
                    if (! in_array($locale, $locales, true)) {
                        continue;
                    }

                    $section->translations()->updateOrCreate(['locale' => $locale], [
                        'heading' => $fields['heading'] ?? null,
                        'subheading' => $fields['subheading'] ?? null,
                        'body' => $fields['body'] ?? null,
                        'cta_label' => $fields['cta_label'] ?? null,
                        'cta_url' => $fields['cta_url'] ?? null,
                        'extra' => $fields['extra'] ?? null,
                    ]);
                }
            }

            // Keep "last updated" meaningful in the page list when only sections changed.
            $page->touch();
        });

        // Section saves don't trigger the Page observer, so flush explicitly.
        Cache::tags(['pages'])->flush();

        return response()->json(['data' => $this->loadPage($page->fresh())]);
    }

    private function loadPage(Page $page): Page
    {
        return $page->load(['translations', 'sections.translations']);
    }
}
