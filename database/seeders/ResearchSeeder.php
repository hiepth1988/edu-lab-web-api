<?php

namespace Database\Seeders;

use App\Models\ResearchPost;
use App\Models\ResearchTopic;
use Illuminate\Database\Seeder;

class ResearchSeeder extends Seeder
{
    public function run(): void
    {
        if (ResearchPost::exists()) {
            return;
        }

        $topics = [
            'knowledge-graph' => ['vi' => 'Knowledge Graph', 'en' => 'Knowledge Graph'],
            'student-behavior' => ['vi' => 'Student Behavior', 'en' => 'Student Behavior'],
            'human-potential' => ['vi' => 'Human Potential', 'en' => 'Human Potential'],
        ];

        $topicModels = [];
        foreach ($topics as $slug => $names) {
            $topic = ResearchTopic::create();
            foreach ($names as $locale => $name) {
                $topic->translations()->create(['locale' => $locale, 'slug' => $slug, 'name' => $name]);
            }
            $topicModels[$slug] = $topic;
        }

        $posts = [
            [
                'topic' => 'knowledge-graph',
                'vi' => [
                    'slug' => 'gioi-thieu-knowledge-graph-trong-giao-duc',
                    'title' => 'Giới thiệu Knowledge Graph trong giáo dục',
                    'excerpt' => 'Knowledge Graph mô hình hóa quan hệ giữa các đơn vị kiến thức để cá nhân hóa lộ trình học tập.',
                    'content' => 'Một Knowledge Graph giáo dục biểu diễn kiến thức dưới dạng đồ thị: node là đơn vị kiến thức/kỹ năng, cạnh là quan hệ tiên quyết hoặc phụ thuộc. Đây là nền tảng để adaptive learning gợi ý đúng nội dung tiếp theo cho từng học viên.',
                ],
                'en' => [
                    'slug' => 'introduction-to-knowledge-graph-in-education',
                    'title' => 'An Introduction to Knowledge Graphs in Education',
                    'excerpt' => 'Knowledge graphs model relationships between knowledge units to personalize learning paths.',
                    'content' => 'An education knowledge graph represents knowledge as a graph: nodes are knowledge units or skills, edges are prerequisite or dependency relationships. This is the foundation for adaptive learning to recommend the right next content for each learner.',
                ],
            ],
            [
                'topic' => 'student-behavior',
                'vi' => [
                    'slug' => 'hanh-vi-hoc-tap-va-du-lieu-tuong-tac',
                    'title' => 'Hành vi học tập và dữ liệu tương tác',
                    'excerpt' => 'Dữ liệu tương tác (thời gian, số lần thử lại, thời điểm bỏ dở) tiết lộ nhiều hơn điểm số.',
                    'content' => 'Theo dõi hành vi học tập ở cấp độ tương tác — thời gian trên mỗi câu hỏi, số lần thử lại, thời điểm bỏ dở bài học — cho phép phát hiện sớm rủi ro bỏ học trước khi nó thể hiện ở điểm số.',
                ],
                'en' => [
                    'slug' => 'learning-behavior-and-interaction-data',
                    'title' => 'Learning Behavior and Interaction Data',
                    'excerpt' => 'Interaction data (time, retries, drop-off points) reveals more than final scores.',
                    'content' => 'Tracking learning behavior at the interaction level — time per question, retry counts, lesson drop-off points — enables early detection of dropout risk before it shows up in scores.',
                ],
            ],
            [
                'topic' => 'human-potential',
                'vi' => [
                    'slug' => 'cong-nghe-giao-duc-va-tiem-nang-con-nguoi',
                    'title' => 'Công nghệ giáo dục và tiềm năng con người',
                    'excerpt' => 'Mục tiêu cuối cùng của công nghệ giáo dục là giúp mỗi người học đạt đúng tiềm năng của mình.',
                    'content' => 'Công nghệ chỉ là công cụ. Mục tiêu dài hạn của XO Education Technology Lab là dùng dữ liệu và AI để hiểu rõ hơn cách mỗi người học tiếp thu kiến thức, từ đó giúp họ đạt đúng tiềm năng thay vì áp một khuôn mẫu chung.',
                ],
                'en' => [
                    'slug' => 'education-technology-and-human-potential',
                    'title' => 'Education Technology and Human Potential',
                    'excerpt' => 'The ultimate goal of education technology is helping each learner reach their true potential.',
                    'content' => 'Technology is just a tool. XO Education Technology Lab\'s long-term goal is to use data and AI to better understand how each learner absorbs knowledge, helping them reach their true potential instead of forcing a one-size-fits-all mold.',
                ],
            ],
        ];

        foreach ($posts as $data) {
            $post = ResearchPost::create([
                'research_topic_id' => $topicModels[$data['topic']]->id,
                'status' => 'published',
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
        }
    }
}
