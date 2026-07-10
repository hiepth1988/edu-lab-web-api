<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class HomePageSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedHome();
        $this->seedSimplePage('about', [
            'vi' => [
                'title' => 'Về chúng tôi',
                'excerpt' => 'XO Edu Lab — đối tác công nghệ cho giáo dục.',
                'content' => 'XO Edu Lab thiết kế và phát triển hạ tầng công nghệ cho giáo dục: LMS, nền tảng thi trực tuyến, AI Education và Learning Analytics. Chúng tôi tin rằng mỗi dự án nên để lại một tài sản công nghệ, không chỉ là doanh thu.',
            ],
            'en' => [
                'title' => 'About us',
                'excerpt' => 'XO Edu Lab — your education technology partner.',
                'content' => 'XO Edu Lab designs and builds technology infrastructure for education: LMS, online exam platforms, AI Education and Learning Analytics. We believe every project should leave behind a technology asset, not just revenue.',
            ],
        ]);
        $this->seedSimplePage('contact', [
            'vi' => [
                'title' => 'Liên hệ',
                'excerpt' => 'Đặt lịch tư vấn hoặc gửi yêu cầu dự án cho XO Edu Lab.',
                'content' => 'Bạn đang xây LMS, Exam Platform hay AI Learning? Hãy để lại thông tin, chúng tôi sẽ phản hồi trong 24 giờ làm việc.',
            ],
            'en' => [
                'title' => 'Contact',
                'excerpt' => 'Book a consultation or send us your EdTech project brief.',
                'content' => 'Building an LMS, Exam Platform or AI Learning product? Leave your details and we will get back to you within one business day.',
            ],
        ]);
    }

    private function seedSimplePage(string $key, array $translations): void
    {
        $page = Page::updateOrCreate(['key' => $key], ['status' => 'published']);

        foreach ($translations as $locale => $data) {
            $page->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'slug' => $key,
                    'meta_title' => $data['title'],
                    'meta_description' => $data['excerpt'],
                    ...$data,
                ]
            );
        }
    }

    private function seedHome(): void
    {
        $page = Page::updateOrCreate(['key' => 'home'], ['status' => 'published']);

        $page->translations()->updateOrCreate(['locale' => 'vi'], [
            'slug' => 'home',
            'title' => 'Building the Future of Education Technology',
            'excerpt' => 'Chúng tôi thiết kế và phát triển nền tảng giáo dục hiện đại, có khả năng mở rộng, tích hợp AI và dữ liệu học tập.',
            'meta_title' => 'XO Edu Lab — Đối tác công nghệ giáo dục',
            'meta_description' => 'XO Edu Lab xây dựng hạ tầng công nghệ cho giáo dục: LMS, nền tảng thi trực tuyến, AI Education, Learning Analytics và Adaptive Learning.',
        ]);

        $page->translations()->updateOrCreate(['locale' => 'en'], [
            'slug' => 'home',
            'title' => 'Building the Future of Education Technology',
            'excerpt' => 'We design and build modern, scalable education platforms powered by AI and learning data.',
            'meta_title' => 'XO Edu Lab — Education Technology Partner',
            'meta_description' => 'XO Edu Lab builds technology infrastructure for education: LMS, online exam platforms, AI Education, Learning Analytics and Adaptive Learning.',
        ]);

        $sections = [
            'hero' => [
                'sort_order' => 1,
                'vi' => [
                    'heading' => 'Building the Future of Education Technology',
                    'subheading' => 'Chúng tôi thiết kế và phát triển nền tảng giáo dục hiện đại, có khả năng mở rộng, tích hợp AI và dữ liệu học tập.',
                    'cta_label' => 'Đặt lịch tư vấn',
                    'cta_url' => '/contact',
                ],
                'en' => [
                    'heading' => 'Building the Future of Education Technology',
                    'subheading' => 'We design and build modern, scalable education platforms powered by AI and learning data.',
                    'cta_label' => 'Book a consultation',
                    'cta_url' => '/contact',
                ],
            ],
            'who_we_help' => [
                'sort_order' => 2,
                'vi' => [
                    'heading' => 'Chúng tôi đồng hành với ai',
                    'body' => 'Schools, Training Centers, EdTech Startups, Enterprise Learning',
                ],
                'en' => [
                    'heading' => 'Who we help',
                    'body' => 'Schools, Training Centers, EdTech Startups, Enterprise Learning',
                ],
            ],
            'solutions' => [
                'sort_order' => 3,
                'vi' => [
                    'heading' => 'Giải pháp',
                    'body' => 'LMS, Online Exam, School Management, AI Education, Learning Analytics, Adaptive Learning',
                    'cta_label' => 'Xem tất cả giải pháp',
                    'cta_url' => '/solutions',
                ],
                'en' => [
                    'heading' => 'Solutions',
                    'body' => 'LMS, Online Exam, School Management, AI Education, Learning Analytics, Adaptive Learning',
                    'cta_label' => 'View all solutions',
                    'cta_url' => '/solutions',
                ],
            ],
            'tech_capability' => [
                'sort_order' => 4,
                'vi' => [
                    'heading' => 'Năng lực công nghệ',
                    'body' => 'Nuxt/Vue, Laravel, AI/LLM, Elasticsearch, Redis, Queue, Cloud, Security',
                ],
                'en' => [
                    'heading' => 'Technology capability',
                    'body' => 'Nuxt/Vue, Laravel, AI/LLM, Elasticsearch, Redis, Queue, Cloud, Security',
                ],
            ],
            'research_lab' => [
                'sort_order' => 5,
                'vi' => [
                    'heading' => 'Research Lab',
                    'body' => 'Knowledge Graph, Brain-based Learning, Student Behavior, Human Potential',
                    'cta_label' => 'Khám phá Research',
                    'cta_url' => '/research',
                ],
                'en' => [
                    'heading' => 'Research Lab',
                    'body' => 'Knowledge Graph, Brain-based Learning, Student Behavior, Human Potential',
                    'cta_label' => 'Explore Research',
                    'cta_url' => '/research',
                ],
            ],
            'case_studies' => [
                'sort_order' => 6,
                'vi' => [
                    'heading' => 'Case Studies',
                    'body' => 'TopThi và các case có thể công khai/ẩn danh — chứng minh năng lực thực chiến.',
                    'cta_label' => 'Xem case studies',
                    'cta_url' => '/case-studies',
                ],
                'en' => [
                    'heading' => 'Case Studies',
                    'body' => 'TopThi and other case studies — proof of real-world execution.',
                    'cta_label' => 'View case studies',
                    'cta_url' => '/case-studies',
                ],
            ],
            'latest_insights' => [
                'sort_order' => 7,
                'vi' => [
                    'heading' => 'Bài viết mới nhất',
                    'body' => '',
                    'cta_label' => 'Xem tất cả Insights',
                    'cta_url' => '/insights',
                ],
                'en' => [
                    'heading' => 'Latest Insights',
                    'body' => '',
                    'cta_label' => 'View all Insights',
                    'cta_url' => '/insights',
                ],
            ],
            'final_cta' => [
                'sort_order' => 8,
                'vi' => [
                    'heading' => 'Bạn đang xây LMS, Exam Platform hay AI Learning?',
                    'cta_label' => 'Book a consultation',
                    'cta_url' => '/contact',
                ],
                'en' => [
                    'heading' => 'Building an LMS, Exam Platform or AI Learning product?',
                    'cta_label' => 'Book a consultation',
                    'cta_url' => '/contact',
                ],
            ],
        ];

        foreach ($sections as $key => $section) {
            $model = $page->sections()->updateOrCreate(
                ['section_key' => $key],
                ['sort_order' => $section['sort_order'], 'is_active' => true]
            );

            foreach (['vi', 'en'] as $locale) {
                $model->translations()->updateOrCreate(
                    ['locale' => $locale],
                    $section[$locale]
                );
            }
        }
    }
}
