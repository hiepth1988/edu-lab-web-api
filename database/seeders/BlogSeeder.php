<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        if (Post::exists()) {
            return;
        }

        $categories = [
            'online-exam' => ['vi' => 'Online Exam Platform', 'en' => 'Online Exam Platform'],
            'lms' => ['vi' => 'LMS', 'en' => 'LMS'],
            'learning-analytics' => ['vi' => 'Learning Analytics', 'en' => 'Learning Analytics'],
        ];

        $categoryModels = [];
        foreach ($categories as $slug => $names) {
            $category = Category::create();
            foreach ($names as $locale => $name) {
                $category->translations()->create(['locale' => $locale, 'slug' => $slug, 'name' => $name]);
            }
            $categoryModels[$slug] = $category;
        }

        $tags = [
            'architecture' => ['vi' => 'Kiến trúc', 'en' => 'Architecture'],
            'security' => ['vi' => 'Bảo mật', 'en' => 'Security'],
        ];

        $tagModels = [];
        foreach ($tags as $slug => $names) {
            $tag = Tag::create();
            foreach ($names as $locale => $name) {
                $tag->translations()->create(['locale' => $locale, 'slug' => $slug, 'name' => $name]);
            }
            $tagModels[$slug] = $tag;
        }

        $author = User::where('email', 'admin@xoedulab.local')->first();

        $posts = [
            [
                'category' => 'online-exam',
                'tags' => ['architecture', 'security'],
                'vi' => [
                    'slug' => 'kien-truc-online-exam-platform',
                    'title' => 'Kiến trúc Online Exam Platform chống gian lận',
                    'excerpt' => 'Thiết kế ngân hàng câu hỏi, random đề và random đáp án để hạn chế gian lận trong thi trực tuyến.',
                    'content' => 'Một nền tảng thi trực tuyến đáng tin cậy cần ngân hàng câu hỏi được gắn tag độ khó và kỹ năng, cơ chế random đề theo ràng buộc, random thứ tự đáp án, chấm điểm tự động cho câu trắc nghiệm và hỗ trợ chấm tự luận bán tự động bằng AI.',
                ],
                'en' => [
                    'slug' => 'online-exam-platform-architecture',
                    'title' => 'Online Exam Platform Architecture Against Cheating',
                    'excerpt' => 'Designing a question bank, randomized exams and randomized answers to reduce cheating in online exams.',
                    'content' => 'A trustworthy online exam platform needs a question bank tagged by difficulty and skill, constraint-based exam randomization, randomized answer order, automatic grading for multiple choice, and AI-assisted grading for essays.',
                ],
            ],
            [
                'category' => 'lms',
                'tags' => ['architecture'],
                'vi' => [
                    'slug' => 'lms-rieng-vs-moodle',
                    'title' => 'Xây LMS riêng hay dùng Moodle?',
                    'excerpt' => 'So sánh chi phí, khả năng tùy biến và tốc độ triển khai giữa LMS tự xây và Moodle.',
                    'content' => 'Moodle phù hợp khi cần triển khai nhanh với ngân sách hạn chế. LMS tự xây phù hợp khi cần tích hợp sâu vào quy trình nghiệp vụ, cần trải nghiệm thương hiệu riêng, hoặc cần mở rộng sang AI/analytics mà Moodle không hỗ trợ tốt.',
                ],
                'en' => [
                    'slug' => 'custom-lms-vs-moodle',
                    'title' => 'Building a Custom LMS vs Using Moodle',
                    'excerpt' => 'Comparing cost, customization and time-to-launch between a custom LMS and Moodle.',
                    'content' => 'Moodle is a good fit when you need to launch quickly on a limited budget. A custom LMS is a better fit when you need deep integration with your business processes, a fully branded experience, or room to grow into AI and analytics that Moodle does not support well.',
                ],
            ],
            [
                'category' => 'learning-analytics',
                'tags' => [],
                'vi' => [
                    'slug' => 'vi-sao-du-lieu-hoc-tap-quan-trong-hon-diem-so',
                    'title' => 'Vì sao dữ liệu học tập quan trọng hơn điểm số?',
                    'excerpt' => 'Điểm số chỉ là kết quả cuối cùng — dữ liệu hành vi học tập mới cho biết vì sao học viên đạt hay không đạt kết quả đó.',
                    'content' => 'Learning Analytics theo dõi tiến độ, thời gian học, tỷ lệ hoàn thành và hành vi tương tác để phát hiện sớm học viên có nguy cơ bỏ học, thay vì chỉ biết kết quả sau khi đã quá muộn để can thiệp.',
                ],
                'en' => [
                    'slug' => 'why-learning-data-matters-more-than-scores',
                    'title' => 'Why Learning Data Matters More Than Scores',
                    'excerpt' => 'A score is only the final outcome — learning behavior data explains why a learner did or did not get there.',
                    'content' => 'Learning Analytics tracks progress, time on task, completion rate and interaction behavior to flag at-risk learners early, instead of only knowing the outcome once it is too late to intervene.',
                ],
            ],
        ];

        foreach ($posts as $data) {
            $post = Post::create([
                'category_id' => $categoryModels[$data['category']]->id,
                'author_id' => $author?->id,
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]);

            foreach (['vi', 'en'] as $locale) {
                $post->translations()->create([
                    'locale' => $locale,
                    'slug' => $data[$locale]['slug'],
                    'title' => $data[$locale]['title'],
                    'excerpt' => $data[$locale]['excerpt'],
                    'content' => $data[$locale]['content'],
                    'meta_title' => $data[$locale]['title'],
                    'meta_description' => $data[$locale]['excerpt'],
                ]);
            }

            $post->tags()->sync(array_map(fn ($slug) => $tagModels[$slug]->id, $data['tags']));
        }
    }
}
