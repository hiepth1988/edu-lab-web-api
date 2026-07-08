<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        if (Product::exists()) {
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
                'vi' => ['name' => 'Exam Engine', 'role_summary' => 'Module tạo đề, làm bài, chấm điểm, phân tích kết quả', 'description' => 'Exam Engine cung cấp toàn bộ vòng đời của một kỳ thi: tạo đề, làm bài, chấm điểm tự động và phân tích kết quả.'],
                'en' => ['name' => 'Exam Engine', 'role_summary' => 'Module for exam creation, taking, grading and result analysis', 'description' => 'Exam Engine covers the full exam lifecycle: creating exams, taking exams, automatic grading and result analysis.'],
            ],
            [
                'slug' => 'question-bank-engine',
                'stage' => 'mvp-3-6-months',
                'vi' => ['name' => 'Question Bank Engine', 'role_summary' => 'Quản lý câu hỏi, tag, difficulty, skill, import/export', 'description' => 'Question Bank Engine giúp quản lý ngân hàng câu hỏi ở quy mô lớn với gắn tag độ khó, kỹ năng và import/export hàng loạt.'],
                'en' => ['name' => 'Question Bank Engine', 'role_summary' => 'Manage questions, tags, difficulty, skill, import/export', 'description' => 'Question Bank Engine manages large-scale question banks with difficulty/skill tagging and bulk import/export.'],
            ],
            [
                'slug' => 'learning-analytics-platform',
                'stage' => '6-12-months',
                'vi' => ['name' => 'Learning Analytics Platform', 'role_summary' => 'Dashboard hành vi học tập, tiến độ, rủi ro bỏ học', 'description' => 'Nền tảng phân tích hành vi học tập, theo dõi tiến độ và cảnh báo sớm rủi ro bỏ học.'],
                'en' => ['name' => 'Learning Analytics Platform', 'role_summary' => 'Dashboards for learning behavior, progress, dropout risk', 'description' => 'A platform to analyze learning behavior, track progress, and give early warnings for dropout risk.'],
            ],
            [
                'slug' => 'ai-learning-engine',
                'stage' => '9-18-months',
                'vi' => ['name' => 'AI Learning Engine', 'role_summary' => 'Gợi ý học, phát hiện điểm yếu, cá nhân hóa lộ trình', 'description' => 'AI Learning Engine gợi ý nội dung học tiếp theo, phát hiện điểm yếu và cá nhân hóa lộ trình học tập.'],
                'en' => ['name' => 'AI Learning Engine', 'role_summary' => 'Recommends content, detects weaknesses, personalizes paths', 'description' => 'AI Learning Engine recommends what to learn next, detects weaknesses, and personalizes learning paths.'],
            ],
            [
                'slug' => 'knowledge-graph-engine',
                'stage' => '12-18-months',
                'vi' => ['name' => 'Knowledge Graph Engine', 'role_summary' => 'Mapping kiến thức, prerequisite, skill dependency', 'description' => 'Knowledge Graph Engine mô hình hóa mối quan hệ giữa các đơn vị kiến thức, điều kiện tiên quyết và phụ thuộc kỹ năng.'],
                'en' => ['name' => 'Knowledge Graph Engine', 'role_summary' => 'Maps knowledge, prerequisites, skill dependencies', 'description' => 'Knowledge Graph Engine models relationships between knowledge units, prerequisites and skill dependencies.'],
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
    }
}
