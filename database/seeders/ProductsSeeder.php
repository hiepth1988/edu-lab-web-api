<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Solution;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        if (Product::exists()) {
            // Products already seeded (e.g. re-running on an existing DB) — the
            // cross-links below are idempotent (uses sync()), so still apply them.
            $this->syncRelatedProducts();

            return;
        }

        $products = [
            [
                'slug' => 'topthi',
                'stage' => 'live',
                'vi' => ['name' => 'TopThi', 'role_summary' => 'Living lab và case study chính cho năng lực Exam + AI Learning', 'description' => 'TopThi là nền tảng thi trực tuyến vận hành thực tế, nơi các năng lực Exam Engine, Knowledge Graph, Learning Analytics và AI Learning được thử nghiệm trước khi đóng gói thành sản phẩm.'],
                'en' => ['name' => 'TopThi', 'role_summary' => 'Living lab and flagship case study for Exam + AI Learning capability', 'description' => 'TopThi is a live online exam platform where Exam Engine, Knowledge Graph, Learning Analytics and AI Learning capabilities are tested before being packaged into products.'],
            ],
            [
                'slug' => 'exam-engine',
                'stage' => 'mvp-3-6-months',
                'vi' => ['name' => 'Exam Engine', 'role_summary' => 'Tổ chức một kỳ thi trực tuyến hoàn chỉnh, từ ra đề tới có kết quả — không cần đội ngũ kỹ thuật riêng.', 'description' => 'Exam Engine cung cấp toàn bộ vòng đời của một kỳ thi: tạo đề, làm bài, chấm điểm tự động (module tạo đề, làm bài, chấm điểm, phân tích kết quả) và phân tích kết quả.'],
                'en' => ['name' => 'Exam Engine', 'role_summary' => 'Run a complete online exam, from setting questions to seeing results — with no in-house technical team.', 'description' => 'Exam Engine covers the full exam lifecycle: creating exams, taking exams, automatic grading (question creation, exam taking, grading and result-analysis modules) and result analysis.'],
            ],
            [
                'slug' => 'question-bank-engine',
                'stage' => 'mvp-3-6-months',
                'vi' => ['name' => 'Question Bank Engine', 'role_summary' => 'Giữ ngân hàng đề của bạn có tổ chức và tái sử dụng được, dù có hàng chục nghìn câu hỏi.', 'description' => 'Question Bank Engine giúp quản lý ngân hàng câu hỏi ở quy mô lớn với gắn tag độ khó, kỹ năng và import/export hàng loạt.'],
                'en' => ['name' => 'Question Bank Engine', 'role_summary' => 'Keep your question bank organized and reusable, even at tens of thousands of questions.', 'description' => 'Question Bank Engine manages large-scale question banks with difficulty/skill tagging and bulk import/export.'],
            ],
            [
                'slug' => 'learning-analytics-platform',
                'stage' => '6-12-months',
                'vi' => ['name' => 'Learning Analytics Platform', 'role_summary' => 'Biết học viên nào sắp bỏ học trước khi họ thực sự bỏ học.', 'description' => 'Nền tảng phân tích hành vi học tập, theo dõi tiến độ và cảnh báo sớm rủi ro bỏ học (dashboard hành vi học tập, tiến độ, rủi ro bỏ học).'],
                'en' => ['name' => 'Learning Analytics Platform', 'role_summary' => 'Know which learners are about to drop off before they actually do.', 'description' => 'A platform to analyze learning behavior, track progress, and give early warnings for dropout risk (dashboards for learning behavior, progress, dropout risk).'],
            ],
            [
                'slug' => 'ai-learning-engine',
                'stage' => '9-18-months',
                'vi' => ['name' => 'AI Learning Engine', 'role_summary' => 'Mỗi học viên có một lộ trình học khác nhau, tự động điều chỉnh theo năng lực của họ.', 'description' => 'AI Learning Engine gợi ý nội dung học tiếp theo, phát hiện điểm yếu và cá nhân hóa lộ trình học tập.'],
                'en' => ['name' => 'AI Learning Engine', 'role_summary' => 'Every learner gets a different path, automatically adjusted to their ability.', 'description' => 'AI Learning Engine recommends what to learn next, detects weaknesses, and personalizes learning paths.'],
            ],
            [
                'slug' => 'knowledge-graph-engine',
                'stage' => '12-18-months',
                'vi' => ['name' => 'Knowledge Graph Engine', 'role_summary' => 'Biết chính xác học viên cần học gì tiếp theo dựa trên những gì họ đã thành thạo.', 'description' => 'Knowledge Graph Engine mô hình hóa mối quan hệ giữa các đơn vị kiến thức, điều kiện tiên quyết và phụ thuộc kỹ năng (knowledge mapping, prerequisite, skill dependency).'],
                'en' => ['name' => 'Knowledge Graph Engine', 'role_summary' => 'Know exactly what a learner should study next, based on what they have already mastered.', 'description' => 'Knowledge Graph Engine models relationships between knowledge units, prerequisites and skill dependencies (knowledge mapping, prerequisites, skill dependency).'],
            ],
        ];

        foreach ($products as $index => $data) {
            $product = Product::create(['status' => 'published', 'stage' => $data['stage'], 'sort_order' => $index]);

            foreach (['vi', 'en'] as $locale) {
                $product->translations()->create([
                    'locale' => $locale,
                    'slug' => $data['slug'],
                    'name' => $data[$locale]['name'],
                    'role_summary' => $data[$locale]['role_summary'],
                    'description' => $data[$locale]['description'],
                    'meta_title' => $data[$locale]['name'],
                    'meta_description' => $data[$locale]['role_summary'],
                ]);
            }
        }

        $this->syncRelatedProducts();
    }

    // Cross-links solutions to the core-engine products they are built on
    // (checklist item #1: "Learning Analytics" solution vs "Learning Analytics
    // Platform" product; item #6: Related Products for AI Education), so the
    // relationship is visible on the solution page, not just described in text.
    private function syncRelatedProducts(): void
    {
        $links = [
            'ai-education' => ['ai-learning-engine', 'knowledge-graph-engine'],
            'learning-analytics' => ['learning-analytics-platform'],
        ];

        foreach ($links as $solutionSlug => $productSlugs) {
            $solution = Solution::whereHas('translations', fn ($q) => $q->where('slug', $solutionSlug))->first();

            if (! $solution) {
                continue;
            }

            $productIds = Product::whereHas('translations', fn ($q) => $q->whereIn('slug', $productSlugs))
                ->get()
                ->sortBy(fn ($product) => array_search($product->translation('vi')?->slug, $productSlugs))
                ->pluck('id')
                ->values();

            $syncPayload = [];
            foreach ($productIds as $order => $productId) {
                $syncPayload[$productId] = ['sort_order' => $order];
            }

            $solution->relatedProducts()->sync($syncPayload);
        }
    }
}
