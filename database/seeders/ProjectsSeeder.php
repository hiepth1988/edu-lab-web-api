<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Project;
use App\Models\ProjectTranslation;
use Illuminate\Database\Seeder;

class ProjectsSeeder extends Seeder
{
    public function run(): void
    {
        $categories = $this->seedCategories();

        $this->seedTopThi($categories['exam-ai-learning']);
        $this->seedMsd($categories['lms-community']);
        $this->seedHanQuocNori($categories['edtech-platform']);
        $this->seedSeiko($categories['lms-center']);
        $this->seedCorporateLd($categories['enterprise-learning']);
        $this->seedSchoolK12($categories['school-management']);
        $this->seedIndependentTutor($categories['independent-educators']);
        $this->seedFreeContentBranding($categories['content-library-platform']);
    }

    /**
     * @return array<string, Category>
     */
    private function seedCategories(): array
    {
        $definitions = [
            'exam-ai-learning' => ['vi' => 'Thi trực tuyến & AI Learning', 'en' => 'Online Exams & AI Learning'],
            'lms-community' => ['vi' => 'LMS đào tạo cộng đồng', 'en' => 'Community-Training LMS'],
            'edtech-platform' => ['vi' => 'Nền tảng EdTech toàn diện', 'en' => 'Full-Stack EdTech Platform'],
            'lms-center' => ['vi' => 'LMS trung tâm đào tạo', 'en' => 'Training-Center LMS'],
            'enterprise-learning' => ['vi' => 'Đào tạo nội bộ doanh nghiệp', 'en' => 'Corporate L&D'],
            'school-management' => ['vi' => 'Quản lý trường học', 'en' => 'School Management'],
            'independent-educators' => ['vi' => 'Công cụ cho giáo viên độc lập', 'en' => 'Tools for Independent Educators'],
            'content-library-platform' => ['vi' => 'Học liệu miễn phí & Xây dựng thương hiệu', 'en' => 'Free Content & Brand Building'],
        ];

        $categories = [];

        foreach ($definitions as $slug => $names) {
            $existing = CategoryTranslation::where('locale', 'vi')->where('slug', $slug)->first();

            if ($existing) {
                $categories[$slug] = $existing->category;

                continue;
            }

            $category = Category::create([]);

            $category->translations()->create(['locale' => 'vi', 'slug' => $slug, 'name' => $names['vi']]);
            $category->translations()->create(['locale' => 'en', 'slug' => $slug, 'name' => $names['en']]);

            $categories[$slug] = $category;
        }

        return $categories;
    }

    /**
     * Creates the repeatable solution-module child rows (image + per-locale translations)
     * for a project. Shared helper so each seed*() method only has to list content.
     *
     * @param  array<int, array{image?: string|null, vi: array{title: string, description?: string, technical_note?: string, features?: string[]}, en: array{title: string, description?: string, technical_note?: string, features?: string[]}}>  $modules
     */
    private function seedSolutionModules(Project $project, array $modules): void
    {
        foreach ($modules as $index => $module) {
            $model = $project->solutionModules()->create(['image' => $module['image'] ?? null, 'sort_order' => $index]);

            foreach (['vi', 'en'] as $locale) {
                $model->translations()->create([
                    'locale' => $locale,
                    'title' => $module[$locale]['title'],
                    'description' => $module[$locale]['description'] ?? null,
                    'technical_note' => $module[$locale]['technical_note'] ?? null,
                    'features' => $module[$locale]['features'] ?? [],
                ]);
            }
        }
    }

    private function seedTopThi(Category $category): void
    {
        if (ProjectTranslation::where('slug', 'topthi')->exists()) {
            return;
        }

        $project = Project::create([
            'category_id' => $category->id,
            'status' => 'published',
            'is_featured' => true,
            'published_at' => now(),
        ]);

        $project->translations()->create([
            'locale' => 'vi',
            'slug' => 'topthi',
            'title' => 'TopThi — Living Lab cho Exam & AI Learning',
            'excerpt' => 'Xây dựng nền tảng thi trực tuyến làm bằng chứng năng lực thực chiến cho Exam Engine và Learning Analytics.',

            'hero_eyebrow' => 'EdTech / Online Exam Platform',
            'hero_badges' => [
                ['icon' => 'quiz', 'label' => 'Exam Engine'],
                ['icon' => 'psychology', 'label' => 'AI Learning'],
                ['icon' => 'monitoring', 'label' => 'Analytics'],
                ['icon' => 'shield', 'label' => 'Anti-cheat'],
            ],
            'hero_stats' => [
                ['value' => '40%', 'label' => 'Giảm thời gian chấm bài'],
                ['value' => '10,000+', 'label' => 'Lượt làm bài mỗi tháng'],
                ['value' => '99.9%', 'label' => 'Uptime nền tảng'],
            ],

            'snapshot_items' => [
                ['icon' => 'category', 'label' => 'Industry', 'value' => 'EdTech / Exam'],
                ['icon' => 'devices', 'label' => 'Type', 'value' => 'Platform'],
                ['icon' => 'schedule', 'label' => 'Trạng thái', 'value' => 'Đang vận hành'],
                ['icon' => 'code', 'label' => 'Tech', 'value' => 'Laravel'],
            ],

            'challenges_heading' => 'Thách thức bài toán',
            'challenges_description' => 'Cần một nền tảng thi trực tuyến đáng tin cậy, chống gian lận và có khả năng phân tích kết quả học tập ở quy mô lớn.',
            'challenges' => [
                ['icon' => 'shield', 'color' => 'primary', 'title' => 'Chống gian lận', 'description' => 'Đảm bảo tính toàn vẹn của bài thi trực tuyến ở quy mô lớn.', 'wide' => false],
                ['icon' => 'bolt', 'color' => 'secondary', 'title' => 'Chấm điểm quy mô lớn', 'description' => 'Tự động hóa chấm điểm cho hàng nghìn lượt thi mỗi tháng.', 'wide' => false],
                ['icon' => 'monitoring', 'color' => 'gold', 'title' => 'Phân tích học tập', 'description' => 'Biến dữ liệu làm bài thành insight về năng lực học viên.', 'wide' => false],
            ],

            'feature_map_heading' => 'Bản đồ chức năng',
            'feature_groups' => [
                ['title' => 'Ngân hàng câu hỏi', 'badge_label' => 'QUESTION BANK', 'features' => ['Gắn tag độ khó/kỹ năng', 'Random đề & đáp án', 'Đa dạng loại câu hỏi']],
                ['title' => 'Chấm điểm', 'badge_label' => 'AUTO GRADING', 'features' => ['Chấm tự động tức thời', 'Xử lý hàng nghìn lượt thi']],
                ['title' => 'Phân tích', 'badge_label' => 'ANALYTICS', 'features' => ['Dashboard hành vi làm bài', 'Learning Analytics', 'Knowledge Graph (thử nghiệm)']],
            ],

            'journey_heading' => 'Hành trình sử dụng',
            'journey_steps' => [
                ['title' => 'Vào phòng thi', 'description' => 'Học viên truy cập đề thi được random riêng'],
                ['title' => 'Làm bài', 'description' => 'Trả lời câu hỏi trắc nghiệm/tự luận'],
                ['title' => 'Nộp bài', 'description' => 'Hệ thống chấm điểm tự động tức thời'],
                ['title' => 'Xem kết quả', 'description' => 'Phân tích hành vi và năng lực qua dashboard'],
            ],

            'results_heading' => 'Kết quả & Tác động',
            'results' => [
                ['icon' => 'bolt', 'color' => 'primary', 'value' => '40%', 'label' => 'Giảm thời gian chấm bài'],
                ['icon' => 'trending_up', 'color' => 'secondary', 'value' => '10,000+', 'label' => 'Lượt làm bài mỗi tháng'],
                ['icon' => 'shield', 'color' => 'gold', 'value' => '99.9%', 'label' => 'Uptime nền tảng'],
            ],

            'lessons_quote' => 'Nền tảng trở thành living lab để thử nghiệm Exam Engine, Knowledge Graph và Learning Analytics trước khi đóng gói thành sản phẩm riêng.',
            'lessons_citation' => '— Đội ngũ XO Edu Lab',

            'meta_title' => 'TopThi — Living Lab cho Exam & AI Learning',
            'meta_description' => 'Case study TopThi: nền tảng thi trực tuyến làm bằng chứng năng lực Exam Engine và Learning Analytics.',
        ]);

        $project->translations()->create([
            'locale' => 'en',
            'slug' => 'topthi',
            'title' => 'TopThi — A Living Lab for Exam & AI Learning',
            'excerpt' => 'Building an online exam platform as proof of real-world execution for Exam Engine and Learning Analytics.',

            'hero_eyebrow' => 'EdTech / Online Exam Platform',
            'hero_badges' => [
                ['icon' => 'quiz', 'label' => 'Exam Engine'],
                ['icon' => 'psychology', 'label' => 'AI Learning'],
                ['icon' => 'monitoring', 'label' => 'Analytics'],
                ['icon' => 'shield', 'label' => 'Anti-cheat'],
            ],
            'hero_stats' => [
                ['value' => '40%', 'label' => 'Reduction in grading time'],
                ['value' => '10,000+', 'label' => 'Exam attempts per month'],
                ['value' => '99.9%', 'label' => 'Platform uptime'],
            ],

            'snapshot_items' => [
                ['icon' => 'category', 'label' => 'Industry', 'value' => 'EdTech / Exam'],
                ['icon' => 'devices', 'label' => 'Type', 'value' => 'Platform'],
                ['icon' => 'schedule', 'label' => 'Status', 'value' => 'In operation'],
                ['icon' => 'code', 'label' => 'Tech', 'value' => 'Laravel'],
            ],

            'challenges_heading' => 'The Challenge',
            'challenges_description' => 'Needed a trustworthy, cheat-resistant online exam platform capable of analyzing learning outcomes at scale.',
            'challenges' => [
                ['icon' => 'shield', 'color' => 'primary', 'title' => 'Cheat resistance', 'description' => 'Ensuring the integrity of online exams at scale.', 'wide' => false],
                ['icon' => 'bolt', 'color' => 'secondary', 'title' => 'Grading at scale', 'description' => 'Automating grading across thousands of exam attempts per month.', 'wide' => false],
                ['icon' => 'monitoring', 'color' => 'gold', 'title' => 'Learning analytics', 'description' => 'Turning exam-taking data into insight about learner ability.', 'wide' => false],
            ],

            'feature_map_heading' => 'Feature Map',
            'feature_groups' => [
                ['title' => 'Question Bank', 'badge_label' => 'QUESTION BANK', 'features' => ['Difficulty/skill tagging', 'Randomized exams & answers', 'Diverse question types']],
                ['title' => 'Grading', 'badge_label' => 'AUTO GRADING', 'features' => ['Instant automatic grading', 'Handles thousands of attempts']],
                ['title' => 'Analytics', 'badge_label' => 'ANALYTICS', 'features' => ['Exam-behavior dashboard', 'Learning Analytics', 'Knowledge Graph (experimental)']],
            ],

            'journey_heading' => 'User Journey',
            'journey_steps' => [
                ['title' => 'Enter the exam', 'description' => 'Learner accesses an individually randomized exam'],
                ['title' => 'Take the exam', 'description' => 'Answer multiple-choice/essay questions'],
                ['title' => 'Submit', 'description' => 'The system grades instantly and automatically'],
                ['title' => 'View results', 'description' => 'Behavior and ability analysis via dashboard'],
            ],

            'results_heading' => 'Results & Impact',
            'results' => [
                ['icon' => 'bolt', 'color' => 'primary', 'value' => '40%', 'label' => 'Reduction in grading time'],
                ['icon' => 'trending_up', 'color' => 'secondary', 'value' => '10,000+', 'label' => 'Exam attempts per month'],
                ['icon' => 'shield', 'color' => 'gold', 'value' => '99.9%', 'label' => 'Platform uptime'],
            ],

            'lessons_quote' => 'The platform became a living lab to test Exam Engine, Knowledge Graph and Learning Analytics before packaging them into standalone products.',
            'lessons_citation' => '— The XO Edu Lab Team',

            'meta_title' => 'TopThi — A Living Lab for Exam & AI Learning',
            'meta_description' => 'TopThi case study: an online exam platform proving Exam Engine and Learning Analytics capability.',
        ]);

        $metrics = [
            ['value' => '40%', 'vi' => 'Giảm thời gian chấm bài', 'en' => 'Reduction in grading time'],
            ['value' => '10,000+', 'vi' => 'Lượt làm bài mỗi tháng', 'en' => 'Exam attempts per month'],
            ['value' => '99.9%', 'vi' => 'Uptime nền tảng', 'en' => 'Platform uptime'],
        ];

        foreach ($metrics as $i => $metric) {
            $m = $project->metrics()->create(['value' => $metric['value'], 'sort_order' => $i]);
            $m->translations()->create(['locale' => 'vi', 'label' => $metric['vi']]);
            $m->translations()->create(['locale' => 'en', 'label' => $metric['en']]);
        }

        $this->seedSolutionModules($project, [
            [
                'vi' => [
                    'title' => 'Ngân hàng câu hỏi thông minh',
                    'description' => 'Xây dựng ngân hàng câu hỏi có gắn tag độ khó/kỹ năng, cơ chế random đề và đáp án cho từng lượt thi.',
                    'features' => ['Gắn tag độ khó & kỹ năng', 'Random đề và đáp án theo từng lượt thi'],
                ],
                'en' => [
                    'title' => 'Smart Question Bank',
                    'description' => 'A question bank tagged by difficulty/skill, with exam and answer randomization for every attempt.',
                    'features' => ['Difficulty & skill tagging', 'Randomized exam and answers per attempt'],
                ],
            ],
            [
                'vi' => [
                    'title' => 'Chấm điểm & Dashboard phân tích',
                    'description' => 'Chấm điểm tự động ngay khi nộp bài, cùng dashboard phân tích hành vi làm bài của học viên.',
                    'features' => ['Chấm tự động tức thời khi nộp bài', 'Dashboard phân tích hành vi làm bài'],
                ],
                'en' => [
                    'title' => 'Grading & Analytics Dashboard',
                    'description' => 'Automatic grading on submission, plus a dashboard analyzing exam-taking behavior.',
                    'features' => ['Instant automatic grading on submission', 'Exam-behavior analytics dashboard'],
                ],
            ],
        ]);
    }

    // MSD Learning Platform — draft theo casestudy/msd/case-study-msd.md.
    // status = draft (không phải published): tài liệu nguồn yêu cầu xin xác nhận
    // MSD Vietnam trước khi đăng công khai (tên tổ chức + dữ liệu học viên nhạy cảm:
    // dân tộc, khuyết tật). Không seed metrics/hero_stats/scale_stats/results vì chưa
    // có số liệu vận hành thật — đừng bịa số, điền qua CMS khi có số liệu từ MSD.
    // featured_image/og_image để trống, cần ảnh chụp màn hình thật (xem checklist trong chat).
    private function seedMsd(Category $category): void
    {
        if (ProjectTranslation::where('slug', 'msd-learning-platform')->exists()) {
            return;
        }

        $msd = Project::create([
            'category_id' => $category->id,
            'status' => 'draft',
            'published_at' => null,
        ]);

        $msd->translations()->create([
            'locale' => 'vi',
            'slug' => 'msd-learning-platform',
            'title' => 'MSD Learning Platform — Nền tảng học trực tuyến vì phát triển bền vững',
            'excerpt' => 'Xây dựng nền tảng LMS phục vụ đào tạo cộng đồng, hỗ trợ đo lường tác động xã hội (người học dân tộc thiểu số, người khuyết tật) và bảo vệ nội dung khóa học có bản quyền.',

            'hero_eyebrow' => 'EdTech / Community Learning (Draft)',
            'hero_badges' => [
                ['icon' => 'school', 'label' => 'LMS'],
                ['icon' => 'video_library', 'label' => 'Video bảo vệ nội dung'],
                ['icon' => 'accessibility_new', 'label' => 'Tiếp cận cộng đồng'],
                ['icon' => 'card_membership', 'label' => 'Chứng chỉ tự động'],
            ],

            'snapshot_items' => [
                ['icon' => 'category', 'label' => 'Industry', 'value' => 'NGO / Community Education'],
                ['icon' => 'devices', 'label' => 'Type', 'value' => 'LMS Platform'],
                ['icon' => 'code', 'label' => 'Tech', 'value' => 'Laravel 11, Nuxt 3'],
            ],

            'challenges_heading' => 'Thách thức bài toán',
            'challenges_description' => 'Cần một nền tảng học trực tuyến phục vụ đào tạo cộng đồng, hướng đến cả học viên phổ thông lẫn các nhóm yếu thế.',
            'challenges' => [
                ['icon' => 'video_library', 'color' => 'primary', 'title' => 'Nội dung đa định dạng', 'description' => 'Video, văn bản, bài tập, flashcard với quy trình biên tập, kiểm duyệt rõ ràng.', 'wide' => false],
                ['icon' => 'shield', 'color' => 'secondary', 'title' => 'Bảo vệ nội dung video', 'description' => 'Chống tải lậu và tua nhanh để né học đủ bài.', 'wide' => false],
                ['icon' => 'accessibility_new', 'color' => 'gold', 'title' => 'Đo lường tiếp cận cộng đồng', 'description' => 'Báo cáo mức độ tiếp cận nhóm học viên yếu thế cho nhà tài trợ.', 'wide' => false],
                ['icon' => 'notifications_active', 'color' => 'primary', 'title' => 'Nhắc học đúng lúc', 'description' => 'Thông báo đúng đối tượng theo khóa học, theo nhóm hoặc toàn bộ học viên.', 'wide' => false],
                ['icon' => 'card_membership', 'color' => 'secondary', 'title' => 'Chứng chỉ & song ngữ', 'description' => 'Tự động cấp chứng chỉ hoàn thành và mở rộng nội dung song ngữ.', 'wide' => true],
            ],

            'feature_map_heading' => 'Bản đồ chức năng',
            'feature_groups' => [
                ['title' => 'Learning', 'badge_label' => '4 LOẠI BÀI HỌC', 'features' => ['Video, văn bản/hình ảnh', 'Bài tập, flashcard', 'Theo dõi tiến độ theo bài học']],
                ['title' => 'Video Protection', 'badge_label' => 'AI PIPELINE', 'features' => ['Whisper phụ đề song ngữ', 'Watermark + mã hóa HLS', 'Chặn tua nhanh']],
                ['title' => 'Chứng chỉ', 'badge_label' => 'TỰ ĐỘNG', 'features' => ['Sinh PDF/PNG tự động', 'Định vị trường thông tin']],
                ['title' => 'Thông báo', 'badge_label' => 'REDIS + PUSHER', 'features' => ['Gửi tức thời hoặc theo lịch', 'Nhắc học viên không hoạt động']],
                ['title' => 'Báo cáo tác động', 'badge_label' => 'CHO NHÀ TÀI TRỢ', 'features' => ['Dữ liệu dân tộc/khuyết tật', 'Xuất báo cáo Excel']],
            ],

            'journey_heading' => 'Hành trình học viên',
            'journey_steps' => [
                ['title' => 'Đăng ký', 'description' => 'Xác thực qua Google OAuth'],
                ['title' => 'Học bài', 'description' => 'Video được bảo vệ, kèm phụ đề song ngữ'],
                ['title' => 'Làm bài tập', 'description' => 'Quiz, flashcard, tự luận'],
                ['title' => 'Nhận nhắc nhở', 'description' => 'Thông báo đúng lúc nếu tạm ngưng học'],
                ['title' => 'Hoàn thành', 'description' => 'Nhận chứng chỉ tự động'],
            ],

            'lessons_quote' => 'Bảo vệ nội dung và đo lường tác động xã hội có thể cùng tồn tại trong một kiến trúc LMS duy nhất nếu mô hình dữ liệu được thiết kế đúng ngay từ đầu.',
            'lessons_citation' => '— Đội ngũ Kỹ thuật XO',

            'meta_title' => 'MSD Learning Platform — Nền tảng học trực tuyến vì phát triển bền vững',
            'meta_description' => 'Case study MSD Learning Platform: nền tảng LMS đào tạo cộng đồng, bảo vệ nội dung video bản quyền và đo lường tác động xã hội tới nhóm học viên yếu thế.',
        ]);

        $msd->translations()->create([
            'locale' => 'en',
            'slug' => 'msd-learning-platform',
            'title' => 'MSD Learning Platform — An Online Learning Platform for Sustainable Development',
            'excerpt' => 'Building an LMS platform for community training that measures social impact among ethnic-minority and disabled learners while protecting copyrighted course content.',

            'hero_eyebrow' => 'EdTech / Community Learning (Draft)',
            'hero_badges' => [
                ['icon' => 'school', 'label' => 'LMS'],
                ['icon' => 'video_library', 'label' => 'Protected Video'],
                ['icon' => 'accessibility_new', 'label' => 'Community Reach'],
                ['icon' => 'card_membership', 'label' => 'Auto Certificates'],
            ],

            'snapshot_items' => [
                ['icon' => 'category', 'label' => 'Industry', 'value' => 'NGO / Community Education'],
                ['icon' => 'devices', 'label' => 'Type', 'value' => 'LMS Platform'],
                ['icon' => 'code', 'label' => 'Tech', 'value' => 'Laravel 11, Nuxt 3'],
            ],

            'challenges_heading' => 'The Challenge',
            'challenges_description' => 'Needed an online learning platform for community training, serving both general learners and disadvantaged groups.',
            'challenges' => [
                ['icon' => 'video_library', 'color' => 'primary', 'title' => 'Multi-format content', 'description' => 'Video, text, exercises, and flashcards with a clear editorial and review workflow.', 'wide' => false],
                ['icon' => 'shield', 'color' => 'secondary', 'title' => 'Video content protection', 'description' => 'Preventing piracy and fast-forwarding past required viewing.', 'wide' => false],
                ['icon' => 'accessibility_new', 'color' => 'gold', 'title' => 'Community reach measurement', 'description' => 'Reporting reach to disadvantaged learner groups for donors.', 'wide' => false],
                ['icon' => 'notifications_active', 'color' => 'primary', 'title' => 'Timely reminders', 'description' => 'Targeted notifications by course, by group, or platform-wide.', 'wide' => false],
                ['icon' => 'card_membership', 'color' => 'secondary', 'title' => 'Certificates & bilingual content', 'description' => 'Automatic completion certificates and room to expand into bilingual content.', 'wide' => true],
            ],

            'feature_map_heading' => 'Feature Map',
            'feature_groups' => [
                ['title' => 'Learning', 'badge_label' => '4 LESSON TYPES', 'features' => ['Video, text/image', 'Exercises, flashcards', 'Per-lesson progress tracking']],
                ['title' => 'Video Protection', 'badge_label' => 'AI PIPELINE', 'features' => ['Whisper bilingual subtitles', 'Watermark + HLS encoding', 'Seek blocking']],
                ['title' => 'Certificates', 'badge_label' => 'AUTOMATIC', 'features' => ['Auto-generated PDF/PNG', 'Positionable info fields']],
                ['title' => 'Notifications', 'badge_label' => 'REDIS + PUSHER', 'features' => ['Instant or scheduled sending', 'Reminders for inactive learners']],
                ['title' => 'Impact Reporting', 'badge_label' => 'FOR DONORS', 'features' => ['Ethnicity/disability data', 'Excel report exports']],
            ],

            'journey_heading' => 'Learner Journey',
            'journey_steps' => [
                ['title' => 'Sign up', 'description' => 'Authenticate via Google OAuth'],
                ['title' => 'Watch lessons', 'description' => 'Protected video with bilingual subtitles'],
                ['title' => 'Do exercises', 'description' => 'Quizzes, flashcards, essay questions'],
                ['title' => 'Get reminded', 'description' => 'Timely notifications if learning pauses'],
                ['title' => 'Complete', 'description' => 'Receive an automatic certificate'],
            ],

            'lessons_quote' => 'Content protection and social-impact measurement can coexist in a single LMS architecture if the data model is designed correctly from day one.',
            'lessons_citation' => '— The XO Engineering Team',

            'meta_title' => 'MSD Learning Platform — An Online Learning Platform for Sustainable Development',
            'meta_description' => 'MSD Learning Platform case study: a community-training LMS protecting copyrighted video content and measuring social impact among disadvantaged learners.',
        ]);

        $this->seedSolutionModules($msd, [
            [
                'vi' => [
                    'title' => 'Bảo vệ nội dung video & phụ đề song ngữ AI',
                    'description' => 'Pipeline xử lý nền: tách phụ đề bằng OpenAI Whisper, dịch song ngữ, gắn watermark, mã hóa HLS.',
                    'features' => ['Whisper transcription tiếng Việt, dịch sang tiếng Anh', 'Watermark & mã hóa HLS qua ffmpeg', 'Trình phát Video.js chặn tua nhanh'],
                    'technical_note' => 'Hàng đợi xử lý nền, tùy chọn đẩy lưu trữ lên AWS S3.',
                ],
                'en' => [
                    'title' => 'Video Protection & AI Bilingual Subtitles',
                    'description' => 'A background pipeline: transcription via OpenAI Whisper, bilingual translation, watermarking, HLS encoding.',
                    'features' => ['Vietnamese Whisper transcription, translated to English', 'Watermarking & HLS encoding via ffmpeg', 'Video.js player with seek blocking'],
                    'technical_note' => 'Background processing queue, with optional push to AWS S3 storage.',
                ],
            ],
            [
                'vi' => [
                    'title' => 'Chứng chỉ tự động & Đo lường tác động',
                    'description' => 'Chứng chỉ sinh tự động ngay khi hoàn tất khóa học; dữ liệu học viên ghi nhận thêm dân tộc, khuyết tật, trình độ học vấn.',
                    'features' => ['Template designer định vị trường thông tin', 'Xuất báo cáo Excel cho nhà tài trợ', 'Dashboard tổng học viên hoạt động/hoàn thành'],
                    'technical_note' => 'DomPDF + Intervention Image sinh chứng chỉ; Maatwebsite Excel cho báo cáo.',
                ],
                'en' => [
                    'title' => 'Automatic Certificates & Impact Measurement',
                    'description' => 'Certificates auto-generated the moment a learner completes a course; the learner data model also records ethnicity, disability, and education level.',
                    'features' => ['Template designer for positioning info fields', 'Excel report exports for donors', 'Dashboard of active/completed learners'],
                    'technical_note' => 'DomPDF + Intervention Image generate certificates; Maatwebsite Excel powers reporting.',
                ],
            ],
        ]);
    }

    // HanQuocNori — theo docs/hanquocnori/*.md (project/business/technical overview).
    // status = published: dự án đã vận hành nhiều năm (migrations 2020–2022), không có
    // dữ liệu nhạy cảm kiểu MSD. Không seed hero_stats/results dạng số liệu kinh doanh
    // (users, doanh thu, tỷ lệ hoàn thành...) vì tài liệu nguồn không có — đừng bịa số,
    // điền qua CMS khi có số liệu thật từ chủ dự án. scale_stats chỉ dùng các con số
    // kiến trúc đã có sẵn trong tài liệu (models/migrations/admin screens...), không
    // phải số liệu vận hành. featured_image/og_image để trống, cần ảnh chụp màn hình thật.
    private function seedHanQuocNori(Category $category): void
    {
        if (ProjectTranslation::where('slug', 'hanquocnori')->exists()) {
            return;
        }

        $project = Project::create([
            'category_id' => $category->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $project->translations()->create([
            'locale' => 'vi',
            'slug' => 'hanquocnori',
            'title' => 'HanQuocNori — Nền tảng học tiếng Hàn trực tuyến toàn diện',
            'excerpt' => 'Xây dựng nền tảng EdTech học tiếng Hàn trọn gói: LMS, thi trực tuyến, thương mại điện tử và lớp học 1-1 với giáo viên qua video call.',

            'hero_eyebrow' => 'EdTech / Learning Platform',
            'hero_badges' => [
                ['icon' => 'school', 'label' => 'LMS'],
                ['icon' => 'quiz', 'label' => 'Online Exam'],
                ['icon' => 'shopping_cart', 'label' => 'Commerce'],
                ['icon' => 'video_call', 'label' => '1-1 Video Call'],
                ['icon' => 'group', 'label' => 'Affiliate'],
            ],

            'snapshot_items' => [
                ['icon' => 'category', 'label' => 'Industry', 'value' => 'EdTech'],
                ['icon' => 'devices', 'label' => 'Type', 'value' => 'Platform'],
                ['icon' => 'person_search', 'label' => 'Role', 'value' => 'Full-stack Dev'],
                ['icon' => 'code', 'label' => 'Tech', 'value' => 'Laravel, Nuxt.js'],
                ['icon' => 'translate', 'label' => 'Languages', 'value' => 'VN, EN, KR'],
            ],

            'scale_heading' => 'Quy mô hệ thống',
            'scale_description' => 'Một hệ sinh thái EdTech vận hành nhiều năm với kiến trúc Repository + Service Layer rõ ràng.',
            'scale_stats' => [
                ['value' => '300+', 'label' => 'Admin Screens'],
                ['value' => '186', 'label' => 'Migrations'],
                ['value' => '76', 'label' => 'Models'],
                ['value' => '47+', 'label' => 'Repositories'],
                ['value' => '6', 'label' => 'Loại bài tập'],
                ['value' => '35', 'label' => 'Vuex Modules'],
            ],

            'challenges_heading' => 'Thách thức bài toán',
            'challenges_description' => 'HanQuocNori cần vận hành như một hệ sinh thái hoàn chỉnh thay vì một website khóa học đơn thuần.',
            'challenges' => [
                ['icon' => 'video_library', 'color' => 'primary', 'title' => 'Nội dung học đa dạng', 'description' => '6 loại bài tập khác nhau cùng flashcard từ vựng và video DASH streaming.', 'wide' => false],
                ['icon' => 'quiz', 'color' => 'secondary', 'title' => 'Thi trực tuyến độc lập', 'description' => 'Vận hành phân hệ thi ExamMeta → Exam → ExamConfig → ExamSchedule → TakeExam.', 'wide' => false],
                ['icon' => 'payments', 'color' => 'gold', 'title' => 'Thương mại điện tử', 'description' => 'Giỏ hàng, 3 phương thức thanh toán, mã giảm giá và chương trình affiliate.', 'wide' => false],
                ['icon' => 'video_call', 'color' => 'primary', 'title' => 'Lớp học 1-1', 'description' => 'Đặt lịch theo slot, quản lý buổi học còn lại, video call trực tiếp.', 'wide' => false],
                ['icon' => 'admin_panel_settings', 'color' => 'secondary', 'title' => 'Quản trị quy mô lớn', 'description' => 'Đa ngôn ngữ và thông báo real-time cho hơn 300 màn hình quản trị.', 'wide' => true],
            ],

            'feature_map_heading' => 'Bản đồ chức năng',
            'feature_groups' => [
                ['title' => 'Learning', 'badge_label' => '6 LOẠI BÀI TẬP', 'features' => ['Video DASH streaming', 'Trắc nghiệm & điền từ', 'Nghe hiểu & đọc hiểu', 'Flashcard từ vựng']],
                ['title' => 'Examination', 'badge_label' => 'EXAM ENGINE', 'features' => ['ExamMeta → TakeExam', 'Chấm tự động trắc nghiệm', 'Chấm tay bài tự luận', 'Bảng xếp hạng']],
                ['title' => 'Commerce', 'badge_label' => '3 PHƯƠNG THỨC TT', 'features' => ['MoMo / COD / Chuyển khoản', 'Mã giảm giá & Affiliate', 'Cấp quyền tự động']],
                ['title' => '1-1 Class', 'badge_label' => 'STRINGEE VIDEO', 'features' => ['Đặt lịch theo slot', 'Video call JWT riêng phiên', 'Đánh giá sau buổi học']],
                ['title' => 'Admin', 'badge_label' => '300+ MÀN HÌNH', 'features' => ['Repository + Service Layer', 'Real-time qua Pusher', 'Đa ngôn ngữ VN/EN/KR']],
            ],

            'journey_heading' => 'Hành trình người học',
            'journey_steps' => [
                ['title' => 'Khám phá', 'description' => 'Tìm khóa học, sách, combo phù hợp'],
                ['title' => 'Đăng ký', 'description' => 'Thanh toán qua MoMo/COD/chuyển khoản'],
                ['title' => 'Học lý thuyết', 'description' => 'Video DASH streaming & flashcard'],
                ['title' => 'Luyện tập', 'description' => '6 loại bài tập tương tác'],
                ['title' => 'Thi đánh giá', 'description' => 'Hệ thống thi trực tuyến độc lập'],
                ['title' => 'Học 1-1', 'description' => 'Đặt lịch & video call với giáo viên'],
                ['title' => 'Theo dõi tiến độ', 'description' => 'Bảng xếp hạng & báo cáo học tập'],
            ],

            'architecture_heading' => 'Kiến trúc kỹ thuật',
            'architecture_layers' => [
                ['icon' => 'smartphone', 'title' => 'Frontend', 'subtitle' => 'Nuxt.js 2 (SSR)'],
                ['icon' => 'admin_panel_settings', 'title' => 'Admin', 'subtitle' => 'Vue.js 2 + Vuetify'],
                ['icon' => 'api', 'title' => 'API', 'subtitle' => 'Laravel 7'],
                ['icon' => 'settings_applications', 'title' => 'Service Layer', 'subtitle' => 'Repository Pattern'],
                ['icon' => 'storage', 'title' => 'Data', 'subtitle' => 'MySQL + Redis'],
            ],

            'tech_stack_groups' => [
                ['title' => 'Backend', 'items' => ['Laravel 7', 'PHP 7.2+', 'JWT (tymon/jwt-auth)', 'Redis']],
                ['title' => 'Admin', 'items' => ['Vue.js 2', 'Vuetify 2', 'Vuex', 'CKEditor 5']],
                ['title' => 'Front', 'items' => ['Nuxt.js 2 (SSR)', 'Video.js + dashjs', 'Pusher-js', 'nuxt-i18n']],
                ['title' => 'Tích hợp', 'items' => ['MoMo', 'Stringee', 'Infusionsoft CRM', 'PayPal']],
            ],

            'results_heading' => 'Kết quả & Tác động',
            'results' => [
                ['icon' => 'integration_instructions', 'color' => 'primary', 'value' => 'All-in-one', 'label' => 'LMS + Thi + Thương mại + Lớp 1-1 trong một hệ sinh thái'],
                ['icon' => 'bolt', 'color' => 'secondary', 'value' => 'Tự động hoá', 'label' => 'Cấp quyền khoá học & thanh toán MoMo'],
                ['icon' => 'architecture', 'color' => 'gold', 'value' => 'Scalable', 'label' => 'Kiến trúc Repository + Service Layer'],
            ],

            'lessons_quote' => 'Kiến trúc Repository kết hợp Service Layer rõ ràng giúp hệ thống mở rộng ổn định qua hàng trăm màn hình quản trị và hàng chục nghiệp vụ khác nhau trong suốt vòng đời dự án.',
            'lessons_citation' => '— Đội ngũ Kỹ thuật XO',

            'meta_title' => 'HanQuocNori — Nền tảng học tiếng Hàn trực tuyến toàn diện',
            'meta_description' => 'Case study HanQuocNori: nền tảng EdTech học tiếng Hàn với LMS, thi trực tuyến, thương mại điện tử và lớp học 1-1 qua video call.',
        ]);

        $project->translations()->create([
            'locale' => 'en',
            'slug' => 'hanquocnori',
            'title' => 'HanQuocNori — A Comprehensive Online Korean Learning Platform',
            'excerpt' => 'Building a full-stack Korean-learning EdTech platform: LMS, online exams, e-commerce, and 1-on-1 video classes with teachers.',

            'hero_eyebrow' => 'EdTech / Learning Platform',
            'hero_badges' => [
                ['icon' => 'school', 'label' => 'LMS'],
                ['icon' => 'quiz', 'label' => 'Online Exam'],
                ['icon' => 'shopping_cart', 'label' => 'Commerce'],
                ['icon' => 'video_call', 'label' => '1-1 Video Call'],
                ['icon' => 'group', 'label' => 'Affiliate'],
            ],

            'snapshot_items' => [
                ['icon' => 'category', 'label' => 'Industry', 'value' => 'EdTech'],
                ['icon' => 'devices', 'label' => 'Type', 'value' => 'Platform'],
                ['icon' => 'person_search', 'label' => 'Role', 'value' => 'Full-stack Dev'],
                ['icon' => 'code', 'label' => 'Tech', 'value' => 'Laravel, Nuxt.js'],
                ['icon' => 'translate', 'label' => 'Languages', 'value' => 'VN, EN, KR'],
            ],

            'scale_heading' => 'System Scale',
            'scale_description' => 'An EdTech ecosystem running for years on a clear Repository + Service Layer architecture.',
            'scale_stats' => [
                ['value' => '300+', 'label' => 'Admin Screens'],
                ['value' => '186', 'label' => 'Migrations'],
                ['value' => '76', 'label' => 'Models'],
                ['value' => '47+', 'label' => 'Repositories'],
                ['value' => '6', 'label' => 'Exercise Types'],
                ['value' => '35', 'label' => 'Vuex Modules'],
            ],

            'challenges_heading' => 'The Challenge',
            'challenges_description' => 'HanQuocNori needed to run as a complete ecosystem rather than a simple course website.',
            'challenges' => [
                ['icon' => 'video_library', 'color' => 'primary', 'title' => 'Diverse learning content', 'description' => '6 different exercise types plus vocabulary flashcards and DASH-streamed video.', 'wide' => false],
                ['icon' => 'quiz', 'color' => 'secondary', 'title' => 'Standalone online exams', 'description' => 'Running the ExamMeta → Exam → ExamConfig → ExamSchedule → TakeExam module.', 'wide' => false],
                ['icon' => 'payments', 'color' => 'gold', 'title' => 'E-commerce', 'description' => 'Cart, three payment methods, discount codes, and an affiliate program.', 'wide' => false],
                ['icon' => 'video_call', 'color' => 'primary', 'title' => '1-on-1 classes', 'description' => 'Slot-based booking, remaining-lesson tracking, live video calls.', 'wide' => false],
                ['icon' => 'admin_panel_settings', 'color' => 'secondary', 'title' => 'Administration at scale', 'description' => 'Multi-language support and real-time notifications across 300+ admin screens.', 'wide' => true],
            ],

            'feature_map_heading' => 'Feature Map',
            'feature_groups' => [
                ['title' => 'Learning', 'badge_label' => '6 EXERCISE TYPES', 'features' => ['DASH-streamed video', 'Multiple choice & fill-in-the-blank', 'Listening & reading', 'Vocabulary flashcards']],
                ['title' => 'Examination', 'badge_label' => 'EXAM ENGINE', 'features' => ['ExamMeta → TakeExam', 'Auto multiple-choice grading', 'Manual essay grading', 'Leaderboard']],
                ['title' => 'Commerce', 'badge_label' => '3 PAYMENT METHODS', 'features' => ['MoMo / COD / bank transfer', 'Discount codes & affiliate', 'Automatic access provisioning']],
                ['title' => '1-1 Class', 'badge_label' => 'STRINGEE VIDEO', 'features' => ['Slot-based booking', 'Per-session JWT video call', 'Post-session rating']],
                ['title' => 'Admin', 'badge_label' => '300+ SCREENS', 'features' => ['Repository + Service Layer', 'Real-time via Pusher', 'Multi-language VN/EN/KR']],
            ],

            'journey_heading' => 'Learner Journey',
            'journey_steps' => [
                ['title' => 'Discover', 'description' => 'Find the right course, book, or bundle'],
                ['title' => 'Enroll', 'description' => 'Pay via MoMo/COD/bank transfer'],
                ['title' => 'Learn theory', 'description' => 'DASH-streamed video & flashcards'],
                ['title' => 'Practice', 'description' => '6 types of interactive exercises'],
                ['title' => 'Take exams', 'description' => 'Standalone online exam system'],
                ['title' => '1-on-1 classes', 'description' => 'Book & video-call with a teacher'],
                ['title' => 'Track progress', 'description' => 'Leaderboard & learning reports'],
            ],

            'architecture_heading' => 'Technical Architecture',
            'architecture_layers' => [
                ['icon' => 'smartphone', 'title' => 'Frontend', 'subtitle' => 'Nuxt.js 2 (SSR)'],
                ['icon' => 'admin_panel_settings', 'title' => 'Admin', 'subtitle' => 'Vue.js 2 + Vuetify'],
                ['icon' => 'api', 'title' => 'API', 'subtitle' => 'Laravel 7'],
                ['icon' => 'settings_applications', 'title' => 'Service Layer', 'subtitle' => 'Repository Pattern'],
                ['icon' => 'storage', 'title' => 'Data', 'subtitle' => 'MySQL + Redis'],
            ],

            'tech_stack_groups' => [
                ['title' => 'Backend', 'items' => ['Laravel 7', 'PHP 7.2+', 'JWT (tymon/jwt-auth)', 'Redis']],
                ['title' => 'Admin', 'items' => ['Vue.js 2', 'Vuetify 2', 'Vuex', 'CKEditor 5']],
                ['title' => 'Front', 'items' => ['Nuxt.js 2 (SSR)', 'Video.js + dashjs', 'Pusher-js', 'nuxt-i18n']],
                ['title' => 'Integrations', 'items' => ['MoMo', 'Stringee', 'Infusionsoft CRM', 'PayPal']],
            ],

            'results_heading' => 'Results & Impact',
            'results' => [
                ['icon' => 'integration_instructions', 'color' => 'primary', 'value' => 'All-in-one', 'label' => 'LMS + exams + commerce + 1-on-1 classes in one ecosystem'],
                ['icon' => 'bolt', 'color' => 'secondary', 'value' => 'Automated', 'label' => 'Course access provisioning & MoMo payment confirmation'],
                ['icon' => 'architecture', 'color' => 'gold', 'value' => 'Scalable', 'label' => 'Repository + Service Layer architecture'],
            ],

            'lessons_quote' => 'The Repository + Service Layer architecture scaled cleanly across hundreds of admin screens and dozens of distinct business flows over the project\'s lifetime.',
            'lessons_citation' => '— The XO Engineering Team',

            'meta_title' => 'HanQuocNori — A Comprehensive Online Korean Learning Platform',
            'meta_description' => 'HanQuocNori case study: a Korean-learning EdTech platform with LMS, online exams, e-commerce, and 1-on-1 video classes.',
        ]);

        $this->seedSolutionModules($project, [
            [
                'vi' => [
                    'title' => 'LMS lõi & Ngân hàng bài tập',
                    'description' => 'Nội dung tổ chức theo Khóa học → Bài học → Bài tập với 6 loại bài tập khác nhau, cache Redis theo người dùng để tối ưu tốc độ tải.',
                    'features' => ['Video DASH streaming + audio + văn bản', '6 loại bài tập: trắc nghiệm, điền từ, nghe, đọc, dịch, hội thoại', 'Flashcard từ vựng', 'Cache Redis theo từng người dùng'],
                    'technical_note' => 'Backend Laravel 7 theo Repository Pattern + Service Layer (76 model, 47+ repository, 186 migration).',
                ],
                'en' => [
                    'title' => 'Core LMS & Exercise Bank',
                    'description' => 'Content organized as Course → Lesson → Exercise with 6 exercise types, per-user Redis caching to keep loading fast.',
                    'features' => ['DASH-streamed video + audio + text', '6 exercise types: multiple choice, fill-in-the-blank, listening, reading, translation, dialogue', 'Vocabulary flashcards', 'Per-user Redis caching'],
                    'technical_note' => 'Laravel 7 backend following the Repository Pattern + Service Layer (76 models, 47+ repositories, 186 migrations).',
                ],
            ],
            [
                'vi' => [
                    'title' => 'Thi trực tuyến & Lớp học 1-1',
                    'description' => 'Phân hệ thi độc lập kết hợp lớp học 1-1 qua video call Stringee.',
                    'features' => ['ExamMeta → Exam → ExamConfig → ExamSchedule → TakeExam', 'Chấm tự động trắc nghiệm, chấm tay tự luận', 'Đặt lịch 1-1 theo slot giáo viên', 'Video call JWT riêng từng phiên qua Stringee'],
                    'technical_note' => 'Thông báo real-time qua Pusher; tác vụ nặng (email, mã hoá video) chạy nền qua Laravel Queue + Redis, vận hành bằng Supervisor.',
                ],
                'en' => [
                    'title' => 'Online Exams & 1-on-1 Classes',
                    'description' => 'A standalone exam module combined with 1-on-1 classes via Stringee video calling.',
                    'features' => ['ExamMeta → Exam → ExamConfig → ExamSchedule → TakeExam', 'Automatic multiple-choice grading, manual essay grading', 'Slot-based 1-on-1 booking', 'Per-session JWT video calling via Stringee'],
                    'technical_note' => 'Real-time notifications via Pusher; heavy tasks (email, video encoding) run in the background via Laravel Queue + Redis, operated with Supervisor.',
                ],
            ],
        ]);
    }

    // Seiko LMS — theo docs/casestudy/seiko/*.md (project/business/technical overview).
    // status = published: tài liệu không nêu tên tổ chức/khách hàng cụ thể (chỉ mô tả
    // chung "trung tâm đào tạo ngoại ngữ") và không có dữ liệu nhạy cảm cần xin xác nhận.
    // scale_stats/feature counts dùng các con số kiến trúc đã có sẵn trong tài liệu, không
    // phải số liệu vận hành thật (số học sinh, số lớp, tỷ lệ điểm danh...) — đừng bịa số,
    // điền qua CMS khi có số liệu thật. featured_image/og_image để trống, cần ảnh chụp
    // màn hình thật.
    private function seedSeiko(Category $category): void
    {
        if (ProjectTranslation::where('slug', 'seiko-lms')->exists()) {
            return;
        }

        $project = Project::create([
            'category_id' => $category->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $project->translations()->create([
            'locale' => 'vi',
            'slug' => 'seiko-lms',
            'title' => 'Seiko LMS — Hệ thống quản lý học tập cho trung tâm đào tạo ngoại ngữ',
            'excerpt' => 'Số hóa toàn bộ quy trình vận hành trung tâm đào tạo ngoại ngữ: phân lớp, lịch học, bài tập/bài thi, điểm danh, nghỉ phép và báo cáo Excel trong cùng một hệ thống.',

            'hero_eyebrow' => 'EdTech / Training Center LMS',
            'hero_badges' => [
                ['icon' => 'school', 'label' => 'LMS'],
                ['icon' => 'fact_check', 'label' => 'Attendance'],
                ['icon' => 'assignment', 'label' => 'Assignments & Exams'],
                ['icon' => 'table_view', 'label' => 'Excel Import/Export'],
                ['icon' => 'notifications_active', 'label' => 'Real-time'],
            ],

            'snapshot_items' => [
                ['icon' => 'category', 'label' => 'Industry', 'value' => 'Language Training Center'],
                ['icon' => 'devices', 'label' => 'Type', 'value' => 'LMS Platform'],
                ['icon' => 'code', 'label' => 'Tech', 'value' => 'Laravel 9, Nuxt 3'],
                ['icon' => 'translate', 'label' => 'Languages', 'value' => 'VN, EN, JP, KR'],
            ],

            'scale_heading' => 'Quy mô hệ thống',
            'scale_description' => 'Số hóa toàn bộ vòng đời một kỳ học, từ tuyển sinh đến báo cáo.',
            'scale_stats' => [
                ['value' => '4', 'label' => 'Cấp vai trò'],
                ['value' => '5', 'label' => 'Trạng thái điểm danh'],
                ['value' => '7', 'label' => 'Loại báo cáo Excel'],
                ['value' => '12', 'label' => 'Loại sự kiện real-time'],
                ['value' => '36', 'label' => 'Pinia Store'],
            ],

            'challenges_heading' => 'Thách thức bài toán',
            'challenges_description' => 'Thay thế các thao tác thủ công rời rạc (Excel, giấy tờ) trong toàn bộ vòng đời một kỳ học.',
            'challenges' => [
                ['icon' => 'account_tree', 'color' => 'primary', 'title' => 'Học vụ đa cấp', 'description' => 'Kỳ học → khóa học → lớp học → buổi học, nhiều cấp độ song song (N1–N5).', 'wide' => false],
                ['icon' => 'assignment_turned_in', 'color' => 'secondary', 'title' => 'Bài tập & bài thi', 'description' => 'Theo dõi nộp bài đúng hạn/trễ, chấm điểm có kiểm soát thời điểm mở đáp án.', 'wide' => false],
                ['icon' => 'fact_check', 'color' => 'gold', 'title' => 'Điểm danh & nghỉ phép', 'description' => '5 trạng thái điểm danh gắn với quy trình xin nghỉ có minh chứng, phê duyệt.', 'wide' => false],
                ['icon' => 'table_view', 'color' => 'primary', 'title' => 'Import/Export Excel', 'description' => 'Nhập liệu hàng loạt học sinh & lịch học, xuất báo cáo vận hành.', 'wide' => false],
                ['icon' => 'notifications_active', 'color' => 'secondary', 'title' => 'Thông báo real-time', 'description' => '12 loại sự kiện nghiệp vụ đẩy tức thời tới đúng đối tượng.', 'wide' => true],
            ],

            'feature_map_heading' => 'Bản đồ chức năng',
            'feature_groups' => [
                ['title' => 'Học vụ', 'badge_label' => '4 CẤP', 'features' => ['Kỳ học → Khóa học → Lớp → Buổi học', 'UUID + soft delete', 'N1–N5 song song']],
                ['title' => 'Bài tập & Thi', 'badge_label' => 'MINI + COMPREHENSIVE', 'features' => ['Mini Test không giới hạn', 'Comprehensive 1 bài/buổi', 'Nộp nhiều file, nộp lại trước hạn']],
                ['title' => 'Điểm danh', 'badge_label' => '5 TRẠNG THÁI', 'features' => ['Có mặt / vắng có phép / không phép', 'Đến muộn / về sớm', 'Đơn xin nghỉ có minh chứng']],
                ['title' => 'Import/Export', 'badge_label' => '7 BÁO CÁO', 'features' => ['Import Excel 7 bước validate', 'Xuất lịch học, chấm công, tiến độ']],
                ['title' => 'Thông báo', 'badge_label' => '12 SỰ KIỆN', 'features' => ['Pusher WebSocket real-time', 'Badge đếm chưa đọc']],
            ],

            'journey_heading' => 'Hành trình vận hành một kỳ học',
            'journey_steps' => [
                ['title' => 'Tuyển sinh', 'description' => 'Import học sinh hàng loạt từ Excel'],
                ['title' => 'Phân lớp', 'description' => 'Xếp lớp theo cấp độ N1–N5'],
                ['title' => 'Xếp lịch', 'description' => 'Thời khóa biểu ca sáng/chiều'],
                ['title' => 'Giảng dạy', 'description' => 'Giao bài tập, tài liệu học tập'],
                ['title' => 'Điểm danh', 'description' => 'Ghi nhận 5 trạng thái mỗi buổi học'],
                ['title' => 'Chấm bài', 'description' => 'Mini Test & Comprehensive'],
                ['title' => 'Báo cáo', 'description' => 'Xuất 7 loại báo cáo Excel'],
            ],

            'architecture_heading' => 'Kiến trúc kỹ thuật',
            'architecture_layers' => [
                ['icon' => 'smartphone', 'title' => 'Landing Page', 'subtitle' => 'Nuxt 3 (SSR)'],
                ['icon' => 'admin_panel_settings', 'title' => 'Admin/Giảng viên', 'subtitle' => 'Vue 3 + Vite + Pinia'],
                ['icon' => 'api', 'title' => 'API', 'subtitle' => 'Laravel 9'],
                ['icon' => 'lock', 'title' => 'Auth', 'subtitle' => 'Sanctum, 4 vai trò'],
                ['icon' => 'storage', 'title' => 'Data', 'subtitle' => 'MySQL'],
            ],

            'tech_stack_groups' => [
                ['title' => 'Backend', 'items' => ['Laravel 9', 'PHP 7.3+/8.0+', 'Laravel Sanctum', 'Maatwebsite Excel']],
                ['title' => 'Admin', 'items' => ['Vue 3', 'Vite', 'TypeScript', 'Pinia (36 store)']],
                ['title' => 'Landing page', 'items' => ['Nuxt 3 (SSR)', '@nuxtjs/i18n', 'Tailwind CSS']],
                ['title' => 'Đa ngôn ngữ', 'items' => ['Tiếng Việt', 'English', '日本語', '한국어']],
            ],

            'results_heading' => 'Kết quả & Tác động',
            'results' => [
                ['icon' => 'sync_alt', 'color' => 'primary', 'value' => 'End-to-end', 'label' => 'Số hóa từ tuyển sinh đến báo cáo'],
                ['icon' => 'bolt', 'color' => 'secondary', 'value' => 'Tự động hoá', 'label' => 'Trạng thái bài tập, điểm danh, nghỉ phép'],
                ['icon' => 'table_view', 'color' => 'gold', 'value' => 'Không nhập tay', 'label' => 'Import/Export Excel quy mô lớn'],
            ],

            'lessons_quote' => 'Số hóa trọn vẹn quy trình vận hành một trung tâm đào tạo ngoại ngữ giúp thay thế các thao tác thủ công rời rạc trước đó bằng một hệ thống nhất quán.',
            'lessons_citation' => '— Đội ngũ Kỹ thuật XO',

            'meta_title' => 'Seiko LMS — Hệ thống quản lý học tập cho trung tâm đào tạo ngoại ngữ',
            'meta_description' => 'Case study Seiko LMS: hệ thống quản lý học tập số hóa phân lớp, lịch học, bài tập/bài thi, điểm danh và báo cáo cho trung tâm đào tạo ngoại ngữ.',
        ]);

        $project->translations()->create([
            'locale' => 'en',
            'slug' => 'seiko-lms',
            'title' => 'Seiko LMS — A Learning Management System for Language Training Centers',
            'excerpt' => 'Digitizing the full operating workflow of a language training center: class placement, scheduling, assignments/exams, attendance, leave requests, and Excel reporting in one system.',

            'hero_eyebrow' => 'EdTech / Training Center LMS',
            'hero_badges' => [
                ['icon' => 'school', 'label' => 'LMS'],
                ['icon' => 'fact_check', 'label' => 'Attendance'],
                ['icon' => 'assignment', 'label' => 'Assignments & Exams'],
                ['icon' => 'table_view', 'label' => 'Excel Import/Export'],
                ['icon' => 'notifications_active', 'label' => 'Real-time'],
            ],

            'snapshot_items' => [
                ['icon' => 'category', 'label' => 'Industry', 'value' => 'Language Training Center'],
                ['icon' => 'devices', 'label' => 'Type', 'value' => 'LMS Platform'],
                ['icon' => 'code', 'label' => 'Tech', 'value' => 'Laravel 9, Nuxt 3'],
                ['icon' => 'translate', 'label' => 'Languages', 'value' => 'VN, EN, JP, KR'],
            ],

            'scale_heading' => 'System Scale',
            'scale_description' => 'Digitizing the entire lifecycle of a term, from enrollment to reporting.',
            'scale_stats' => [
                ['value' => '4', 'label' => 'Role Tiers'],
                ['value' => '5', 'label' => 'Attendance States'],
                ['value' => '7', 'label' => 'Excel Report Types'],
                ['value' => '12', 'label' => 'Real-time Event Types'],
                ['value' => '36', 'label' => 'Pinia Stores'],
            ],

            'challenges_heading' => 'The Challenge',
            'challenges_description' => 'Replacing disconnected manual processes (spreadsheets, paperwork) across the entire lifecycle of a term.',
            'challenges' => [
                ['icon' => 'account_tree', 'color' => 'primary', 'title' => 'Multi-tier academics', 'description' => 'Term → course → class → class period, with multiple proficiency levels running in parallel (N1–N5).', 'wide' => false],
                ['icon' => 'assignment_turned_in', 'color' => 'secondary', 'title' => 'Assignments & exams', 'description' => 'Tracking on-time/late submissions, with controlled answer-reveal timing.', 'wide' => false],
                ['icon' => 'fact_check', 'color' => 'gold', 'title' => 'Attendance & leave requests', 'description' => 'Five attendance states tied to an evidence-based leave-request and approval workflow.', 'wide' => false],
                ['icon' => 'table_view', 'color' => 'primary', 'title' => 'Excel import/export', 'description' => 'Bulk student and schedule import, operational report exports.', 'wide' => false],
                ['icon' => 'notifications_active', 'color' => 'secondary', 'title' => 'Real-time notifications', 'description' => '12 business event types pushed instantly to the right recipients.', 'wide' => true],
            ],

            'feature_map_heading' => 'Feature Map',
            'feature_groups' => [
                ['title' => 'Academics', 'badge_label' => '4 TIERS', 'features' => ['Term → Course → Class → Period', 'UUID + soft delete', 'N1–N5 in parallel']],
                ['title' => 'Assignments & Exams', 'badge_label' => 'MINI + COMPREHENSIVE', 'features' => ['Unlimited Mini Tests', 'One Comprehensive exam per period', 'Multi-file submission, resubmit before deadline']],
                ['title' => 'Attendance', 'badge_label' => '5 STATES', 'features' => ['Present / excused / unexcused absence', 'Late / early leave', 'Evidence-based leave requests']],
                ['title' => 'Import/Export', 'badge_label' => '7 REPORTS', 'features' => ['7-step Excel import validation', 'Schedule, timesheet, progress exports']],
                ['title' => 'Notifications', 'badge_label' => '12 EVENTS', 'features' => ['Real-time via Pusher WebSocket', 'Unread-count badge']],
            ],

            'journey_heading' => 'A Term Lifecycle',
            'journey_steps' => [
                ['title' => 'Enrollment', 'description' => 'Bulk student import from Excel'],
                ['title' => 'Class placement', 'description' => 'Placed by proficiency level N1–N5'],
                ['title' => 'Scheduling', 'description' => 'Morning/afternoon timetables'],
                ['title' => 'Teaching', 'description' => 'Assignments and materials distributed'],
                ['title' => 'Attendance', 'description' => 'Five states recorded per class period'],
                ['title' => 'Grading', 'description' => 'Mini Test & Comprehensive exams'],
                ['title' => 'Reporting', 'description' => '7 types of Excel reports exported'],
            ],

            'architecture_heading' => 'Technical Architecture',
            'architecture_layers' => [
                ['icon' => 'smartphone', 'title' => 'Landing Page', 'subtitle' => 'Nuxt 3 (SSR)'],
                ['icon' => 'admin_panel_settings', 'title' => 'Admin/Teacher', 'subtitle' => 'Vue 3 + Vite + Pinia'],
                ['icon' => 'api', 'title' => 'API', 'subtitle' => 'Laravel 9'],
                ['icon' => 'lock', 'title' => 'Auth', 'subtitle' => 'Sanctum, 4 roles'],
                ['icon' => 'storage', 'title' => 'Data', 'subtitle' => 'MySQL'],
            ],

            'tech_stack_groups' => [
                ['title' => 'Backend', 'items' => ['Laravel 9', 'PHP 7.3+/8.0+', 'Laravel Sanctum', 'Maatwebsite Excel']],
                ['title' => 'Admin', 'items' => ['Vue 3', 'Vite', 'TypeScript', 'Pinia (36 stores)']],
                ['title' => 'Landing page', 'items' => ['Nuxt 3 (SSR)', '@nuxtjs/i18n', 'Tailwind CSS']],
                ['title' => 'Multi-language', 'items' => ['Vietnamese', 'English', 'Japanese', 'Korean']],
            ],

            'results_heading' => 'Results & Impact',
            'results' => [
                ['icon' => 'sync_alt', 'color' => 'primary', 'value' => 'End-to-end', 'label' => 'Digitized from enrollment to reporting'],
                ['icon' => 'bolt', 'color' => 'secondary', 'value' => 'Automated', 'label' => 'Assignment, attendance, and leave-request status'],
                ['icon' => 'table_view', 'color' => 'gold', 'value' => 'No manual entry', 'label' => 'Large-scale Excel import/export'],
            ],

            'lessons_quote' => 'Fully digitizing a language training center\'s operations replaced previously disconnected manual processes with one consistent system.',
            'lessons_citation' => '— The XO Engineering Team',

            'meta_title' => 'Seiko LMS — A Learning Management System for Language Training Centers',
            'meta_description' => 'Seiko LMS case study: an LMS digitizing class placement, scheduling, assignments/exams, attendance, and reporting for a language training center.',
        ]);

        $this->seedSolutionModules($project, [
            [
                'vi' => [
                    'title' => 'Học vụ phân cấp & Bài tập/Bài thi',
                    'description' => 'Kỳ học → Khóa học → Lớp học → Buổi học, nơi gắn kết tài liệu, bài tập, bài thi và điểm danh.',
                    'features' => ['Mini Test không giới hạn, Comprehensive tối đa 1 bài/buổi', 'Tự tính trạng thái đúng hạn/trễ/chưa nộp', 'Chấm điểm theo thang tự định nghĩa'],
                    'technical_note' => 'Toàn bộ bản ghi dùng UUID và soft delete để giữ lịch sử điểm danh/bài nộp khi học sinh rời lớp.',
                ],
                'en' => [
                    'title' => 'Hierarchical Academics & Assignments/Exams',
                    'description' => 'Term → Course → Class → Class Period, where materials, assignments, exams, and attendance are all attached.',
                    'features' => ['Unlimited Mini Tests, at most one Comprehensive exam per period', 'Automatic on-time/late/not-submitted status', 'Grading against a self-defined scale'],
                    'technical_note' => 'All records use UUIDs and soft deletes to preserve attendance/submission history after a student leaves a class.',
                ],
            ],
            [
                'vi' => [
                    'title' => 'Điểm danh, nghỉ phép & Import/Export',
                    'description' => 'Điểm danh 5 trạng thái gắn với quy trình xin nghỉ có minh chứng và phê duyệt.',
                    'features' => ['Import học sinh/lịch học hàng loạt từ Excel (7 bước validate)', 'Xuất 7 loại báo cáo Excel', 'Thông báo real-time qua Pusher WebSocket'],
                    'technical_note' => 'Import chạy nền qua Queue Job, xác thực email qua DNS MX trước khi tạo tài khoản.',
                ],
                'en' => [
                    'title' => 'Attendance, Leave Requests & Import/Export',
                    'description' => 'Five-state attendance tied to an evidence-based leave-request and approval workflow.',
                    'features' => ['Bulk Excel import of students/schedules (7-step validation)', '7 types of Excel report exports', 'Real-time notifications via Pusher WebSocket'],
                    'technical_note' => 'Import runs as a background Queue Job, verifying email via DNS MX lookup before account creation.',
                ],
            ],
        ]);
    }

    // Corporate L&D Platform — MINH HỌA/GIẢ ĐỊNH, theo docs/casestudy/corporate-ld/overview.md.
    // KHÔNG phải dự án thật: chưa có khách hàng doanh nghiệp/tổ chức thật nào triển khai
    // hệ thống này. Viết để phủ nhóm "Đào tạo nội bộ doanh nghiệp" trong menu "Dành cho ai"
    // (đề xuất trong docs/casestudy-gaps-proposal.md) khi chưa có dự án thật khớp bối cảnh.
    // Không seed hero_stats/scale_stats/results — không bịa số liệu vận hành. Khi có dự án
    // thật, thay thế toàn bộ nội dung case này (không chỉ điền thêm số liệu).
    private function seedCorporateLd(Category $category): void
    {
        if (ProjectTranslation::where('slug', 'corporate-ld-platform')->exists()) {
            return;
        }

        $project = Project::create([
            'category_id' => $category->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $project->translations()->create([
            'locale' => 'vi',
            'slug' => 'corporate-ld-platform',
            'title' => 'Corporate L&D Platform — Nền tảng đào tạo nội bộ gắn với tuân thủ',
            'excerpt' => '(Case minh họa) Mô hình nền tảng đào tạo nội bộ doanh nghiệp: onboarding tự động, đào tạo bắt buộc có theo dõi tuân thủ, gán khóa học theo cơ cấu tổ chức.',

            'hero_eyebrow' => '(Minh họa) Đào tạo nội bộ doanh nghiệp',
            'hero_badges' => [
                ['icon' => 'account_tree', 'label' => 'Org-based Assignment'],
                ['icon' => 'fact_check', 'label' => 'Compliance Tracking'],
                ['icon' => 'integration_instructions', 'label' => 'SCORM/xAPI'],
            ],

            'snapshot_items' => [
                ['icon' => 'category', 'label' => 'Industry', 'value' => 'Corporate L&D (minh họa)'],
                ['icon' => 'devices', 'label' => 'Type', 'value' => 'Platform'],
                ['icon' => 'code', 'label' => 'Tech', 'value' => 'Laravel'],
            ],

            'challenges_heading' => 'Thách thức bài toán',
            'challenges_description' => 'Nhiều doanh nghiệp vận hành đào tạo nội bộ rời rạc qua email, Excel và các buổi training thủ công.',
            'challenges' => [
                ['icon' => 'person_add', 'color' => 'primary', 'title' => 'Onboarding thủ công', 'description' => 'Nhân viên mới không có lộ trình tự động, HR phải giao từng khóa học thủ công.', 'wide' => false],
                ['icon' => 'fact_check', 'color' => 'secondary', 'title' => 'Tuân thủ không theo dõi được', 'description' => 'Đào tạo bắt buộc thiếu cơ chế hạn chót và nhắc nhở tự động.', 'wide' => false],
                ['icon' => 'account_tree', 'color' => 'gold', 'title' => 'Gán khóa học thủ công', 'description' => 'Không có cách gán tự động theo phòng ban/vai trò/cấp bậc.', 'wide' => false],
                ['icon' => 'summarize', 'color' => 'primary', 'title' => 'Thiếu báo cáo tuân thủ', 'description' => 'HR và quản lý không có báo cáo thời gian thực theo phòng ban.', 'wide' => true],
            ],

            'feature_map_heading' => 'Bản đồ chức năng (minh họa)',
            'feature_groups' => [
                ['title' => 'Onboarding', 'badge_label' => 'TỰ ĐỘNG', 'features' => ['Gán khóa học theo cơ cấu tổ chức', 'Lộ trình theo ngày vào làm']],
                ['title' => 'Compliance', 'badge_label' => 'THEO DÕI', 'features' => ['Hạn chót cá nhân hóa', 'Nhắc nhở email/nội bộ', 'Báo cáo audit Excel']],
                ['title' => 'Nội dung', 'badge_label' => 'SCORM/XAPI', 'features' => ['Nhập thư viện e-learning có sẵn']],
                ['title' => 'Báo cáo', 'badge_label' => 'THEO PHÒNG BAN', 'features' => ['Trạng thái tuân thủ real-time']],
            ],

            'journey_heading' => 'Hành trình nhân viên (minh họa)',
            'journey_steps' => [
                ['title' => 'Gia nhập', 'description' => 'Được gán lộ trình onboarding tự động'],
                ['title' => 'Học bắt buộc', 'description' => 'Hoàn thành khóa an toàn/bảo mật theo hạn chót'],
                ['title' => 'Nhắc nhở', 'description' => 'Nhận thông báo khi gần hạn'],
                ['title' => 'Hoàn thành', 'description' => 'Được ghi nhận tuân thủ'],
                ['title' => 'Audit', 'description' => 'HR xuất báo cáo theo phòng ban'],
            ],

            'architecture_heading' => 'Kiến trúc kỹ thuật (minh họa)',
            'architecture_layers' => [
                ['icon' => 'account_tree', 'title' => 'Org Structure', 'subtitle' => 'Nguồn dữ liệu gốc'],
                ['icon' => 'api', 'title' => 'API', 'subtitle' => 'Laravel + Sanctum'],
                ['icon' => 'admin_panel_settings', 'title' => 'Admin', 'subtitle' => 'Vue 3 + TypeScript'],
                ['icon' => 'summarize', 'title' => 'Reporting', 'subtitle' => 'Maatwebsite Excel'],
            ],

            'tech_stack_groups' => [
                ['title' => 'Backend', 'items' => ['Laravel', 'Sanctum', 'Spatie Permission', 'Maatwebsite Excel']],
                ['title' => 'Admin', 'items' => ['Vue 3', 'TypeScript']],
                ['title' => 'Tích hợp', 'items' => ['SCORM/xAPI player']],
            ],

            'lessons_quote' => 'Cùng là LMS nhưng mục tiêu nghiệp vụ khác nhau — trọng tâm ở đây là tỷ lệ tuân thủ và hiệu suất đào tạo gắn với KPI nhân sự, không phải tác động xã hội.',
            'lessons_citation' => '— Đội ngũ XO Edu Lab',

            'meta_title' => 'Corporate L&D Platform — Nền tảng đào tạo nội bộ doanh nghiệp (minh họa)',
            'meta_description' => 'Case study minh họa: nền tảng đào tạo nội bộ doanh nghiệp với onboarding tự động, theo dõi tuân thủ và gán khóa học theo cơ cấu tổ chức.',
        ]);

        $project->translations()->create([
            'locale' => 'en',
            'slug' => 'corporate-ld-platform',
            'title' => 'Corporate L&D Platform — Compliance-Driven Internal Training',
            'excerpt' => '(Illustrative case) A corporate internal-training platform model: automated onboarding, compliance-tracked mandatory training, and org-structure-based course assignment.',

            'hero_eyebrow' => '(Illustrative) Corporate Internal Training',
            'hero_badges' => [
                ['icon' => 'account_tree', 'label' => 'Org-based Assignment'],
                ['icon' => 'fact_check', 'label' => 'Compliance Tracking'],
                ['icon' => 'integration_instructions', 'label' => 'SCORM/xAPI'],
            ],

            'snapshot_items' => [
                ['icon' => 'category', 'label' => 'Industry', 'value' => 'Corporate L&D (illustrative)'],
                ['icon' => 'devices', 'label' => 'Type', 'value' => 'Platform'],
                ['icon' => 'code', 'label' => 'Tech', 'value' => 'Laravel'],
            ],

            'challenges_heading' => 'The Challenge',
            'challenges_description' => 'Many companies run internal training through disconnected email threads, spreadsheets, and manual sessions.',
            'challenges' => [
                ['icon' => 'person_add', 'color' => 'primary', 'title' => 'Manual onboarding', 'description' => 'No automated path for new hires — HR assigns courses to each person manually.', 'wide' => false],
                ['icon' => 'fact_check', 'color' => 'secondary', 'title' => 'Untracked compliance', 'description' => 'Mandatory training lacks deadline tracking and automated reminders.', 'wide' => false],
                ['icon' => 'account_tree', 'color' => 'gold', 'title' => 'Manual course assignment', 'description' => 'No way to auto-assign by department/role/level.', 'wide' => false],
                ['icon' => 'summarize', 'color' => 'primary', 'title' => 'No compliance reporting', 'description' => 'HR and managers lack real-time reporting by department.', 'wide' => true],
            ],

            'feature_map_heading' => 'Feature Map (illustrative)',
            'feature_groups' => [
                ['title' => 'Onboarding', 'badge_label' => 'AUTOMATED', 'features' => ['Course assignment by org structure', 'Path based on hire date']],
                ['title' => 'Compliance', 'badge_label' => 'TRACKED', 'features' => ['Personalized deadlines', 'Email/in-app reminders', 'Excel audit reports']],
                ['title' => 'Content', 'badge_label' => 'SCORM/XAPI', 'features' => ['Import existing e-learning library']],
                ['title' => 'Reporting', 'badge_label' => 'BY DEPARTMENT', 'features' => ['Real-time compliance status']],
            ],

            'journey_heading' => 'Employee Journey (illustrative)',
            'journey_steps' => [
                ['title' => 'Join', 'description' => 'Assigned an automated onboarding path'],
                ['title' => 'Mandatory training', 'description' => 'Complete safety/security courses by deadline'],
                ['title' => 'Reminder', 'description' => 'Notified as the deadline approaches'],
                ['title' => 'Complete', 'description' => 'Marked compliant'],
                ['title' => 'Audit', 'description' => 'HR exports reports by department'],
            ],

            'architecture_heading' => 'Technical Architecture (illustrative)',
            'architecture_layers' => [
                ['icon' => 'account_tree', 'title' => 'Org Structure', 'subtitle' => 'Source of truth'],
                ['icon' => 'api', 'title' => 'API', 'subtitle' => 'Laravel + Sanctum'],
                ['icon' => 'admin_panel_settings', 'title' => 'Admin', 'subtitle' => 'Vue 3 + TypeScript'],
                ['icon' => 'summarize', 'title' => 'Reporting', 'subtitle' => 'Maatwebsite Excel'],
            ],

            'tech_stack_groups' => [
                ['title' => 'Backend', 'items' => ['Laravel', 'Sanctum', 'Spatie Permission', 'Maatwebsite Excel']],
                ['title' => 'Admin', 'items' => ['Vue 3', 'TypeScript']],
                ['title' => 'Integrations', 'items' => ['SCORM/xAPI player']],
            ],

            'lessons_quote' => 'Same LMS category, different business objective — the focus here is compliance rate and training performance tied to HR KPIs, not social impact.',
            'lessons_citation' => '— The XO Edu Lab Team',

            'meta_title' => 'Corporate L&D Platform — Compliance-Driven Internal Training (illustrative)',
            'meta_description' => 'Illustrative case study: a corporate internal-training platform with automated onboarding, compliance tracking, and org-structure-based course assignment.',
        ]);

        $this->seedSolutionModules($project, [
            [
                'vi' => [
                    'title' => 'Gán khóa học theo cơ cấu tổ chức',
                    'description' => 'Sơ đồ tổ chức (phòng ban → vai trò → nhân viên) làm gốc để gán khóa học tự động theo quy tắc nghiệp vụ.',
                    'features' => ['Quy tắc gán theo phòng ban/vai trò', 'Hạn chót cá nhân hóa theo ngày vào làm'],
                    'technical_note' => 'Spatie Permission cho phân quyền theo vai trò tổ chức.',
                ],
                'en' => [
                    'title' => 'Org-Structure-Based Course Assignment',
                    'description' => 'The organizational chart (department → role → employee) as the source of truth for rule-based auto-assignment.',
                    'features' => ['Assignment rules by department/role', 'Personalized deadlines based on hire date'],
                    'technical_note' => 'Spatie Permission powers org-role-based access control.',
                ],
            ],
            [
                'vi' => [
                    'title' => 'Theo dõi tuân thủ & nội dung chuẩn công nghiệp',
                    'description' => 'Báo cáo tổng hợp trạng thái tuân thủ theo phòng ban, hỗ trợ nhập nội dung SCORM/xAPI có sẵn.',
                    'features' => ['Nhắc nhở tự động qua queue', 'Xuất Excel phục vụ audit', 'Nhập nội dung SCORM/xAPI'],
                    'technical_note' => 'Maatwebsite Excel cho báo cáo; SCORM/xAPI player cho nội dung e-learning chuẩn công nghiệp.',
                ],
                'en' => [
                    'title' => 'Compliance Tracking & Industry-Standard Content',
                    'description' => 'Aggregated compliance status reports by department, with support for importing existing SCORM/xAPI content.',
                    'features' => ['Automated queue-driven reminders', 'Excel exports for audits', 'SCORM/xAPI content import'],
                    'technical_note' => 'Maatwebsite Excel powers reporting; a SCORM/xAPI player handles industry-standard e-learning content.',
                ],
            ],
        ]);
    }

    // K12 Language Learning Platform — dựa trên dự án THẬT (đã trúng thầu, đang triển khai),
    // theo docs/casestudy/school-k12/overview.md. Nội dung ĐÃ ẨN DANH HOÀN TOÀN theo yêu cầu
    // NDA của tài liệu nguồn (RFP/BRD/Đề xuất kỹ thuật lưu trong cùng thư mục): không nêu tên
    // khách hàng, tên thương hiệu nền tảng, mã RFP, hay số liệu định danh (số tỉnh/đối tác cụ
    // thể...). Không seed results/results_heading — dự án chưa có xác nhận go-live/số liệu
    // vận hành để công bố công khai; scale_stats chỉ chứa ngưỡng/mục tiêu kỹ thuật đặt ra khi
    // thiết kế (NFR), không phải kết quả kinh doanh đã đạt được — đừng thêm số liệu "kết quả"
    // tới khi có xác nhận chính thức được phép công bố (xem overview.md để cập nhật đúng cách).
    private function seedSchoolK12(Category $category): void
    {
        if (ProjectTranslation::where('slug', 'k12-language-learning-platform')->exists()) {
            return;
        }

        $project = Project::create([
            'category_id' => $category->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $project->translations()->create([
            'locale' => 'vi',
            'slug' => 'k12-language-learning-platform',
            'title' => 'K12 Language Learning Platform — Nền tảng học tiếng Anh nhượng quyền đa cấp',
            'excerpt' => 'Nâng cấp nền tảng học tiếng Anh cho học sinh phổ thông sang mô hình nhượng quyền đa tỉnh: AI chấm điểm phát âm có giáo viên giám sát, phân quyền đa cấp Tổng bộ – Đối tác – Cơ sở, và SSO hợp nhất tài khoản.',

            'hero_eyebrow' => 'EdTech / K-12 Language Learning · Nhượng quyền đa tỉnh',
            'hero_badges' => [
                ['icon' => 'record_voice_over', 'label' => 'AI Read Aloud'],
                ['icon' => 'account_tree', 'label' => 'Multi-level RBAC'],
                ['icon' => 'verified_user', 'label' => 'SSO'],
                ['icon' => 'military_tech', 'label' => 'Gamification'],
                ['icon' => 'insights', 'label' => 'Analytics'],
            ],

            'snapshot_items' => [
                ['icon' => 'category', 'label' => 'Industry', 'value' => 'K-12 Language Learning (Franchise)'],
                ['icon' => 'devices', 'label' => 'Type', 'value' => 'Platform (Phase 3 Upgrade)'],
                ['icon' => 'account_tree', 'label' => 'Model', 'value' => 'Tổng bộ – Đối tác – Cơ sở'],
                ['icon' => 'code', 'label' => 'Tech', 'value' => 'Spring Boot 3, Nuxt/Vue 3'],
            ],

            'scale_heading' => 'Quy mô & Ngưỡng thiết kế hệ thống',
            'scale_description' => 'Nền tảng được thiết kế cho mô hình nhượng quyền đa cấp, với các ngưỡng hiệu năng đặt ra ngay từ giai đoạn kiến trúc.',
            'scale_stats' => [
                ['value' => '3', 'label' => 'Cấp phân quyền: Tổng bộ – Đối tác – Cơ sở'],
                ['value' => '5', 'label' => 'Nhóm quyền chuẩn hóa (RBAC)'],
                ['value' => '3', 'label' => 'Tiêu chí AI chấm điểm: phát âm, ngữ điệu, trôi chảy'],
                ['value' => '24h', 'label' => 'Ngưỡng thời gian trả kết quả AI'],
                ['value' => '<3s', 'label' => 'Ngưỡng tải trang dashboard'],
                ['value' => '<5s', 'label' => 'Ngưỡng xử lý bản ghi âm sau khi nộp'],
            ],

            'challenges_heading' => 'Thách thức bài toán',
            'challenges_description' => 'Nâng cấp một nền tảng đang vận hành thật lên mô hình nhượng quyền đa tỉnh, không được phép downtime hay mất dữ liệu lịch sử.',
            'challenges' => [
                ['icon' => 'sync_problem', 'color' => 'primary', 'title' => 'Tái cấu trúc dữ liệu không downtime', 'description' => 'Chuyển đổi mô hình nội dung từ "Tuần học" cố định sang "Bài học" linh hoạt trên dữ liệu lịch sử hàng chục nghìn học sinh.', 'wide' => false],
                ['icon' => 'account_tree', 'color' => 'secondary', 'title' => 'Phân quyền đa cấp không rò rỉ dữ liệu', 'description' => 'Ranh giới dữ liệu nghiêm ngặt giữa Tổng bộ – Đối tác – Cơ sở khi hệ thống mở rộng ra nhiều tỉnh thành.', 'wide' => false],
                ['icon' => 'record_voice_over', 'color' => 'gold', 'title' => 'AI chấm điểm phát âm cho trẻ em', 'description' => 'Giọng đọc trẻ em có âm sắc cao, tốc độ không đều — AI khó chính xác 100%, cần cơ chế giám sát của giáo viên.', 'wide' => false],
                ['icon' => 'verified_user', 'color' => 'primary', 'title' => 'SSO & xử lý xung đột tài khoản', 'description' => 'Hợp nhất đăng nhập trong khi vẫn phải xử lý an toàn các tài khoản đã tồn tại qua phương thức cũ.', 'wide' => false],
                ['icon' => 'leaderboard', 'color' => 'secondary', 'title' => 'Bảng xếp hạng & báo cáo không làm chậm hệ thống', 'description' => 'Tính toán thứ hạng và báo cáo trên hàng triệu bản ghi mà không ảnh hưởng tới trải nghiệm làm bài của học sinh.', 'wide' => true],
            ],

            'feature_map_heading' => 'Bản đồ chức năng',
            'feature_groups' => [
                ['title' => 'Read Aloud AI', 'badge_label' => 'AI PIPELINE', 'features' => ['Nghe → Ghi âm → Nộp bài → Đọc hiểu', 'Chấm 3 tiêu chí: phát âm, ngữ điệu, trôi chảy', 'Kho nhận xét mẫu', 'Giáo viên duyệt lại (human-in-the-loop)']],
                ['title' => 'Phân quyền đa cấp', 'badge_label' => 'PROVINCE–PARTNER–SCHOOL', 'features' => ['5 nhóm quyền chuẩn hóa', 'Gán thực thể theo Tổng bộ/Đối tác/Cơ sở', 'Chặn ngay từ tầng Controller']],
                ['title' => 'SSO', 'badge_label' => 'OIDC', 'features' => ['Đăng nhập hợp nhất', 'Xử lý xung đột tài khoản', 'Công cụ merge thủ công cho admin', 'Audit log đăng nhập']],
                ['title' => 'Nội dung giảng dạy', 'badge_label' => 'WEEK → LESSON', 'features' => ['Chuyển đổi cấu trúc Tuần sang Bài học', 'Quản lý khóa học & kho học liệu']],
                ['title' => 'Gamification & Báo cáo', 'badge_label' => 'PRE-COMPUTED', 'features' => ['Tích lũy sao, bảng xếp hạng', 'Dashboard theo từng vai trò', 'Báo cáo tính toán sẵn định kỳ']],
            ],

            'journey_heading' => 'Hành trình luyện nói của học sinh',
            'journey_steps' => [
                ['title' => 'Nghe mẫu', 'description' => 'Học sinh nghe đoạn văn mẫu cần luyện đọc'],
                ['title' => 'Ghi âm', 'description' => 'Ghi âm phần đọc của mình, có thể nghe lại trước khi nộp'],
                ['title' => 'Nộp bài', 'description' => 'Bài ghi âm được tải lên và đưa vào hàng đợi xử lý'],
                ['title' => 'AI chấm điểm', 'description' => 'AI phân tích phát âm, ngữ điệu, độ trôi chảy'],
                ['title' => 'Giáo viên duyệt', 'description' => 'Giáo viên nghe lại, xác nhận hoặc điều chỉnh điểm/nhận xét'],
                ['title' => 'Nhận kết quả', 'description' => 'Học sinh xem điểm chính thức và nhận xét bằng ngôn ngữ tự nhiên'],
            ],

            'architecture_heading' => 'Kiến trúc kỹ thuật',
            'architecture_layers' => [
                ['icon' => 'smartphone', 'title' => 'Frontend App', 'subtitle' => 'Vue 3 + Vite (SPA)'],
                ['icon' => 'web', 'title' => 'Landing/SSO', 'subtitle' => 'Nuxt 3 (SSR)'],
                ['icon' => 'api', 'title' => 'Backend API', 'subtitle' => 'Spring Boot 3'],
                ['icon' => 'security', 'title' => 'Auth', 'subtitle' => 'OIDC / Spring Security'],
                ['icon' => 'storage', 'title' => 'Data', 'subtitle' => 'PostgreSQL + Redis'],
            ],

            'tech_stack_groups' => [
                ['title' => 'Backend', 'items' => ['Spring Boot 3 (Java 21 LTS)', 'Spring Security OAuth2', 'Spring Data JPA', 'Spring Batch', 'Flyway']],
                ['title' => 'Frontend', 'items' => ['Vue 3 + Vite', 'Nuxt 3 (SSR)', 'Pinia']],
                ['title' => 'Data & Cache', 'items' => ['PostgreSQL (range partitioning)', 'Redis (cache-aside)']],
                ['title' => 'Hạ tầng & Tích hợp', 'items' => ['Message Queue (SQS-compatible)', 'Object Storage (S3-compatible)', 'AI Speech Service', 'GitHub Actions CI/CD']],
            ],

            'lessons_quote' => 'Nâng cấp một nền tảng giáo dục đang vận hành thật, cho hàng chục nghìn người dùng, đòi hỏi tư duy khác hẳn việc xây mới: từng thay đổi cấu trúc dữ liệu phải được thiết kế để không ai nhận ra hệ thống vừa "thay bánh xe khi xe đang chạy".',
            'lessons_citation' => '— Đội ngũ Kỹ thuật XO',

            'meta_title' => 'K12 Language Learning Platform — Nền tảng học tiếng Anh nhượng quyền đa cấp',
            'meta_description' => 'Case study: nâng cấp nền tảng học tiếng Anh K-12 sang mô hình nhượng quyền đa tỉnh với AI chấm điểm phát âm, phân quyền đa cấp và SSO.',
        ]);

        $project->translations()->create([
            'locale' => 'en',
            'slug' => 'k12-language-learning-platform',
            'title' => 'K12 Language Learning Platform — A Multi-Level Franchise English Learning Platform',
            'excerpt' => 'Upgrading a K-12 English learning platform to a multi-province franchise model: teacher-supervised AI pronunciation scoring, multi-level Province–Partner–School access control, and unified SSO.',

            'hero_eyebrow' => 'EdTech / K-12 Language Learning · Multi-Province Franchise',
            'hero_badges' => [
                ['icon' => 'record_voice_over', 'label' => 'AI Read Aloud'],
                ['icon' => 'account_tree', 'label' => 'Multi-level RBAC'],
                ['icon' => 'verified_user', 'label' => 'SSO'],
                ['icon' => 'military_tech', 'label' => 'Gamification'],
                ['icon' => 'insights', 'label' => 'Analytics'],
            ],

            'snapshot_items' => [
                ['icon' => 'category', 'label' => 'Industry', 'value' => 'K-12 Language Learning (Franchise)'],
                ['icon' => 'devices', 'label' => 'Type', 'value' => 'Platform (Phase 3 Upgrade)'],
                ['icon' => 'account_tree', 'label' => 'Model', 'value' => 'Province – Partner – School'],
                ['icon' => 'code', 'label' => 'Tech', 'value' => 'Spring Boot 3, Nuxt/Vue 3'],
            ],

            'scale_heading' => 'System Scale & Design Thresholds',
            'scale_description' => 'Designed for a multi-level franchise model, with performance thresholds set from the architecture stage onward.',
            'scale_stats' => [
                ['value' => '3', 'label' => 'Permission tiers: Province – Partner – School'],
                ['value' => '5', 'label' => 'Standardized RBAC role groups'],
                ['value' => '3', 'label' => 'AI scoring criteria: pronunciation, intonation, fluency'],
                ['value' => '24h', 'label' => 'AI result turnaround threshold'],
                ['value' => '<3s', 'label' => 'Dashboard page-load threshold'],
                ['value' => '<5s', 'label' => 'Recording processing threshold after submission'],
            ],

            'challenges_heading' => 'The Challenge',
            'challenges_description' => 'Upgrading a live platform to a multi-province franchise model, with zero tolerance for downtime or historical data loss.',
            'challenges' => [
                ['icon' => 'sync_problem', 'color' => 'primary', 'title' => 'Zero-downtime data restructuring', 'description' => 'Converting the content model from fixed "Weeks" to flexible "Lessons" across tens of thousands of students\' historical data.', 'wide' => false],
                ['icon' => 'account_tree', 'color' => 'secondary', 'title' => 'Multi-level access without data leaks', 'description' => 'Strict data boundaries between Province – Partner – School as the system expands to more provinces.', 'wide' => false],
                ['icon' => 'record_voice_over', 'color' => 'gold', 'title' => 'AI pronunciation scoring for children', 'description' => 'Children\'s voices are high-pitched with uneven pacing — AI can\'t be 100% accurate, so it needs teacher oversight.', 'wide' => false],
                ['icon' => 'verified_user', 'color' => 'primary', 'title' => 'SSO & account conflict handling', 'description' => 'Unifying login while still safely handling accounts that already existed under the old method.', 'wide' => false],
                ['icon' => 'leaderboard', 'color' => 'secondary', 'title' => 'Leaderboards & reporting without slowing the system', 'description' => 'Computing rankings and reports over millions of records without affecting students\' everyday learning experience.', 'wide' => true],
            ],

            'feature_map_heading' => 'Feature Map',
            'feature_groups' => [
                ['title' => 'Read Aloud AI', 'badge_label' => 'AI PIPELINE', 'features' => ['Listen → Record → Submit → Reading comprehension', '3 scoring criteria: pronunciation, intonation, fluency', 'Sample feedback bank', 'Teacher review (human-in-the-loop)']],
                ['title' => 'Multi-Level Access', 'badge_label' => 'PROVINCE–PARTNER–SCHOOL', 'features' => ['5 standardized role groups', 'Entity binding to Province/Partner/School', 'Blocked right at the Controller layer']],
                ['title' => 'SSO', 'badge_label' => 'OIDC', 'features' => ['Unified login', 'Account conflict handling', 'Manual merge tool for admins', 'Login audit log']],
                ['title' => 'Teaching Content', 'badge_label' => 'WEEK → LESSON', 'features' => ['Converting the Week structure to Lessons', 'Course & material library management']],
                ['title' => 'Gamification & Reporting', 'badge_label' => 'PRE-COMPUTED', 'features' => ['Star accumulation, leaderboards', 'Role-based dashboards', 'Periodic pre-computed reports']],
            ],

            'journey_heading' => 'A Student\'s Speaking-Practice Journey',
            'journey_steps' => [
                ['title' => 'Listen', 'description' => 'The student listens to a sample passage to practice reading'],
                ['title' => 'Record', 'description' => 'Records their own reading, with playback before submitting'],
                ['title' => 'Submit', 'description' => 'The recording is uploaded and queued for processing'],
                ['title' => 'AI scoring', 'description' => 'AI analyzes pronunciation, intonation, and fluency'],
                ['title' => 'Teacher review', 'description' => 'A teacher listens back and confirms or adjusts the score/feedback'],
                ['title' => 'Get results', 'description' => 'The student sees the final score and natural-language feedback'],
            ],

            'architecture_heading' => 'Technical Architecture',
            'architecture_layers' => [
                ['icon' => 'smartphone', 'title' => 'Frontend App', 'subtitle' => 'Vue 3 + Vite (SPA)'],
                ['icon' => 'web', 'title' => 'Landing/SSO', 'subtitle' => 'Nuxt 3 (SSR)'],
                ['icon' => 'api', 'title' => 'Backend API', 'subtitle' => 'Spring Boot 3'],
                ['icon' => 'security', 'title' => 'Auth', 'subtitle' => 'OIDC / Spring Security'],
                ['icon' => 'storage', 'title' => 'Data', 'subtitle' => 'PostgreSQL + Redis'],
            ],

            'tech_stack_groups' => [
                ['title' => 'Backend', 'items' => ['Spring Boot 3 (Java 21 LTS)', 'Spring Security OAuth2', 'Spring Data JPA', 'Spring Batch', 'Flyway']],
                ['title' => 'Frontend', 'items' => ['Vue 3 + Vite', 'Nuxt 3 (SSR)', 'Pinia']],
                ['title' => 'Data & Cache', 'items' => ['PostgreSQL (range partitioning)', 'Redis (cache-aside)']],
                ['title' => 'Infra & Integrations', 'items' => ['Message Queue (SQS-compatible)', 'Object Storage (S3-compatible)', 'AI Speech Service', 'GitHub Actions CI/CD']],
            ],

            'lessons_quote' => 'Upgrading a live education platform serving tens of thousands of real users demands a completely different mindset than building new: every data-model change has to be designed so nobody notices the wheels were changed while the car was still moving.',
            'lessons_citation' => '— The XO Engineering Team',

            'meta_title' => 'K12 Language Learning Platform — A Multi-Level Franchise English Learning Platform',
            'meta_description' => 'Case study: upgrading a K-12 English learning platform to a multi-province franchise model with AI pronunciation scoring, multi-level access control, and SSO.',
        ]);

        $this->seedSolutionModules($project, [
            [
                'vi' => [
                    'title' => 'Tái cấu trúc dữ liệu & Phân quyền đa cấp an toàn',
                    'description' => 'Kết hợp chiến lược Shadow Tables để chuyển đổi cấu trúc nội dung mà không gây downtime, với security interceptor chặn rò rỉ dữ liệu ngay từ tầng request.',
                    'features' => ['Shadow tables + auto-mapping có giám sát cho dữ liệu lịch sử', 'Chỉ mục theo Province/Partner/School ID', 'Phân vùng dữ liệu theo năm học (range partitioning)', 'Chặn truy cập trái phép ngay từ Controller, không chạm database'],
                    'technical_note' => 'Spring Boot 3 + Flyway cho migration versioned, Spring Batch xử lý backfill theo chunk trên PostgreSQL để tránh table lock trên các bảng hàng triệu bản ghi.',
                ],
                'en' => [
                    'title' => 'Data Restructuring & Safe Multi-Level Access',
                    'description' => 'A Shadow Tables strategy converts the content structure without downtime, paired with a security interceptor that blocks data leaks right at the request layer.',
                    'features' => ['Shadow tables + supervised auto-mapping for historical data', 'Indexing by Province/Partner/School ID', 'Time-based range partitioning by academic year', 'Unauthorized access blocked at the Controller layer, before it touches the database'],
                    'technical_note' => 'Spring Boot 3 + Flyway for versioned migrations, Spring Batch handles chunked backfills on PostgreSQL to avoid table locks on multi-million-row tables.',
                ],
            ],
            [
                'vi' => [
                    'title' => 'AI Read Aloud & SSO hợp nhất',
                    'description' => 'Pipeline chấm điểm bất đồng bộ kết hợp AI cấp doanh nghiệp với giáo viên duyệt lại, cùng luồng SSO OIDC xử lý xung đột tài khoản an toàn.',
                    'features' => ['Upload ghi âm qua presigned URL, xử lý nền qua message queue', 'Worker AI Speech tách riêng khỏi API chính', 'LLM sinh nhận xét tự nhiên từ điểm số thô', 'Giáo viên duyệt lại trước khi công bố điểm (human-in-the-loop)', 'SSO OIDC với công cụ merge tài khoản thủ công cho admin'],
                    'technical_note' => 'Spring Security OAuth2 Resource Server xác thực JWT qua JWKS; worker riêng dùng WebClient gọi dịch vụ AI Speech + LLM, tách khỏi luồng request chính để tránh kéo sập API khi dịch vụ AI chậm/lỗi.',
                ],
                'en' => [
                    'title' => 'AI Read Aloud & Unified SSO',
                    'description' => 'An asynchronous scoring pipeline pairs enterprise-grade AI with teacher review, alongside an OIDC SSO flow that safely handles account conflicts.',
                    'features' => ['Recordings uploaded via presigned URL, processed in the background via a message queue', 'A dedicated AI Speech worker, isolated from the main API', 'An LLM turns raw scores into natural-language feedback', 'Teacher review before scores are published (human-in-the-loop)', 'OIDC SSO with a manual account-merge tool for admins'],
                    'technical_note' => 'Spring Security OAuth2 Resource Server validates JWTs against JWKS; a dedicated worker uses WebClient to call the AI Speech + LLM services, isolated from the main request flow so a slow/failing AI service can\'t take down the core API.',
                ],
            ],
        ]);
    }

    // Tutor Suite — MINH HỌA/GIẢ ĐỊNH, theo docs/casestudy/independent-tutor/overview.md.
    // KHÔNG phải dự án thật: chưa có giáo viên/chuyên gia độc lập thật nào dùng hệ thống
    // này. Viết để phủ nhóm "Chuyên gia & Giáo viên độc lập" trong menu "Dành cho ai" (đề
    // xuất trong docs/casestudy-gaps-proposal.md) khi chưa có dự án thật khớp bối cảnh.
    // Không seed hero_stats/scale_stats/results — không bịa số liệu vận hành. Khi có dự án
    // thật, thay thế toàn bộ nội dung case này (không chỉ điền thêm số liệu).
    private function seedIndependentTutor(Category $category): void
    {
        if (ProjectTranslation::where('slug', 'tutor-suite')->exists()) {
            return;
        }

        $project = Project::create([
            'category_id' => $category->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $project->translations()->create([
            'locale' => 'vi',
            'slug' => 'tutor-suite',
            'title' => 'Tutor Suite — Công cụ self-serve cho gia sư & chuyên gia độc lập',
            'excerpt' => '(Case minh họa) Công cụ nhẹ giúp giáo viên/chuyên gia tự do tự quản lý học viên, lịch dạy 1-1 và công nợ học phí mà không cần đội vận hành riêng.',

            'hero_eyebrow' => '(Minh họa) Giáo viên & chuyên gia độc lập',
            'hero_badges' => [
                ['icon' => 'person', 'label' => 'Self-serve'],
                ['icon' => 'event_available', 'label' => 'Đặt lịch 1-1'],
                ['icon' => 'account_balance_wallet', 'label' => 'Công nợ học phí'],
            ],

            'snapshot_items' => [
                ['icon' => 'category', 'label' => 'Industry', 'value' => 'Independent Tutoring (minh họa)'],
                ['icon' => 'devices', 'label' => 'Type', 'value' => 'Self-serve SaaS'],
                ['icon' => 'code', 'label' => 'Tech', 'value' => 'Laravel'],
            ],

            'challenges_heading' => 'Thách thức bài toán',
            'challenges_description' => 'Giáo viên/chuyên gia tự do có bài toán khác hẳn các tổ chức lớn.',
            'challenges' => [
                ['icon' => 'storefront', 'color' => 'primary', 'title' => 'Không qua trung tâm', 'description' => 'Tự tạo lớp, quản lý danh sách học viên riêng mà không qua tổ chức trung gian.', 'wide' => false],
                ['icon' => 'account_balance_wallet', 'color' => 'secondary', 'title' => 'Công nợ nhỏ lẻ', 'description' => 'Theo dõi công nợ từng học viên mà không cần hệ thống kế toán phức tạp.', 'wide' => false],
                ['icon' => 'event_available', 'color' => 'gold', 'title' => 'Lịch dạy cá nhân', 'description' => 'Đặt lịch theo slot rảnh, tránh trùng lịch, nhắc lịch tự động.', 'wide' => false],
                ['icon' => 'bolt', 'color' => 'primary', 'title' => 'Không cần đội vận hành', 'description' => 'Công cụ đơn giản, không đòi hỏi hạ tầng IT riêng.', 'wide' => true],
            ],

            'feature_map_heading' => 'Bản đồ chức năng (minh họa)',
            'feature_groups' => [
                ['title' => 'Lớp học', 'badge_label' => 'TỰ TẠO', 'features' => ['Một học viên hoặc nhóm nhỏ', 'Không cần ai duyệt']],
                ['title' => 'Học viên', 'badge_label' => 'HỒ SƠ NHẸ', 'features' => ['Tên, liên hệ, gói đã mua', 'Cảnh báo sắp hết buổi/quá hạn']],
                ['title' => 'Lịch dạy', 'badge_label' => '1-1', 'features' => ['Mở khung giờ rảnh', 'Chặn trùng lịch tự động']],
                ['title' => 'Thanh toán', 'badge_label' => 'LINH HOẠT', 'features' => ['Ghi nhận thủ công hoặc cổng nhỏ lẻ']],
            ],

            'journey_heading' => 'Hành trình sử dụng (minh họa)',
            'journey_steps' => [
                ['title' => 'Đăng ký', 'description' => 'Giáo viên tự tạo tài khoản'],
                ['title' => 'Tạo lớp', 'description' => 'Thêm học viên hoặc nhóm nhỏ'],
                ['title' => 'Mở lịch', 'description' => 'Đăng khung giờ rảnh'],
                ['title' => 'Học viên đặt lịch', 'description' => 'Chọn slot, hệ thống chặn trùng'],
                ['title' => 'Dạy & ghi nhận', 'description' => 'Trừ dần vào gói đã mua'],
                ['title' => 'Theo dõi công nợ', 'description' => 'Cảnh báo khi gần hết buổi'],
            ],

            'architecture_heading' => 'Kiến trúc kỹ thuật (minh họa)',
            'architecture_layers' => [
                ['icon' => 'person', 'title' => 'Tutor', 'subtitle' => 'Tenant đơn vai trò'],
                ['icon' => 'calendar_month', 'title' => 'Scheduling', 'subtitle' => 'Chặn trùng lịch'],
                ['icon' => 'api', 'title' => 'API', 'subtitle' => 'Laravel + Sanctum'],
                ['icon' => 'account_balance_wallet', 'title' => 'Billing', 'subtitle' => 'Ghi nhận thủ công'],
            ],

            'tech_stack_groups' => [
                ['title' => 'Backend', 'items' => ['Laravel', 'Sanctum']],
                ['title' => 'Frontend', 'items' => ['Giao diện lịch tối giản', 'Tối ưu mobile']],
                ['title' => 'Thanh toán', 'items' => ['Ghi nhận thủ công', 'Cổng thanh toán nhỏ lẻ (tuỳ chọn)']],
            ],

            'lessons_quote' => 'Không phải mọi hệ thống EdTech đều cần đội vận hành riêng — với một giáo viên độc lập, công cụ phải nhỏ gọn như một SaaS cá nhân.',
            'lessons_citation' => '— Đội ngũ XO Edu Lab',

            'meta_title' => 'Tutor Suite — Công cụ self-serve cho giáo viên độc lập (minh họa)',
            'meta_description' => 'Case study minh họa: công cụ nhẹ cho gia sư/chuyên gia tự do quản lý học viên, lịch dạy 1-1 và công nợ học phí.',
        ]);

        $project->translations()->create([
            'locale' => 'en',
            'slug' => 'tutor-suite',
            'title' => 'Tutor Suite — A Self-Serve Tool for Independent Tutors & Experts',
            'excerpt' => '(Illustrative case) A lightweight tool letting independent tutors/experts manage their own students, 1-on-1 schedules, and tuition balances without an operations team.',

            'hero_eyebrow' => '(Illustrative) Independent Tutors & Experts',
            'hero_badges' => [
                ['icon' => 'person', 'label' => 'Self-serve'],
                ['icon' => 'event_available', 'label' => '1-on-1 Booking'],
                ['icon' => 'account_balance_wallet', 'label' => 'Tuition Balances'],
            ],

            'snapshot_items' => [
                ['icon' => 'category', 'label' => 'Industry', 'value' => 'Independent Tutoring (illustrative)'],
                ['icon' => 'devices', 'label' => 'Type', 'value' => 'Self-serve SaaS'],
                ['icon' => 'code', 'label' => 'Tech', 'value' => 'Laravel'],
            ],

            'challenges_heading' => 'The Challenge',
            'challenges_description' => 'Independent tutors/experts face a very different set of problems than large organizations.',
            'challenges' => [
                ['icon' => 'storefront', 'color' => 'primary', 'title' => 'No intermediary center', 'description' => 'Creating their own classes and managing their own student list directly.', 'wide' => false],
                ['icon' => 'account_balance_wallet', 'color' => 'secondary', 'title' => 'Small-scale balances', 'description' => 'Tracking each student\'s balance without a complex accounting system.', 'wide' => false],
                ['icon' => 'event_available', 'color' => 'gold', 'title' => 'Personal scheduling', 'description' => 'Booking against open time slots, avoiding double-booking, automatic reminders.', 'wide' => false],
                ['icon' => 'bolt', 'color' => 'primary', 'title' => 'No operations team needed', 'description' => 'A simple tool that doesn\'t require dedicated IT infrastructure.', 'wide' => true],
            ],

            'feature_map_heading' => 'Feature Map (illustrative)',
            'feature_groups' => [
                ['title' => 'Classes', 'badge_label' => 'SELF-CREATED', 'features' => ['A single student or small group', 'No approval needed']],
                ['title' => 'Students', 'badge_label' => 'LIGHTWEIGHT PROFILE', 'features' => ['Name, contact, purchased package', 'Low-balance/overdue warnings']],
                ['title' => 'Scheduling', 'badge_label' => '1-ON-1', 'features' => ['Open available time slots', 'Automatic double-booking prevention']],
                ['title' => 'Payments', 'badge_label' => 'FLEXIBLE', 'features' => ['Manual logging or a lightweight gateway']],
            ],

            'journey_heading' => 'Usage Journey (illustrative)',
            'journey_steps' => [
                ['title' => 'Sign up', 'description' => 'Tutor creates their own account'],
                ['title' => 'Create a class', 'description' => 'Add a student or small group'],
                ['title' => 'Open slots', 'description' => 'Publish available time slots'],
                ['title' => 'Student books', 'description' => 'Picks a slot, system prevents overlap'],
                ['title' => 'Teach & log', 'description' => 'Deducts from the purchased package'],
                ['title' => 'Track balance', 'description' => 'Warned when sessions are running low'],
            ],

            'architecture_heading' => 'Technical Architecture (illustrative)',
            'architecture_layers' => [
                ['icon' => 'person', 'title' => 'Tutor', 'subtitle' => 'Single-role tenant'],
                ['icon' => 'calendar_month', 'title' => 'Scheduling', 'subtitle' => 'Double-booking prevention'],
                ['icon' => 'api', 'title' => 'API', 'subtitle' => 'Laravel + Sanctum'],
                ['icon' => 'account_balance_wallet', 'title' => 'Billing', 'subtitle' => 'Manual logging'],
            ],

            'tech_stack_groups' => [
                ['title' => 'Backend', 'items' => ['Laravel', 'Sanctum']],
                ['title' => 'Frontend', 'items' => ['Minimal calendar UI', 'Mobile-optimized']],
                ['title' => 'Payments', 'items' => ['Manual logging', 'Lightweight payment gateway (optional)']],
            ],

            'lessons_quote' => 'Not every EdTech system needs a dedicated operations team — for an independent tutor, the tool has to be as lean as a personal SaaS.',
            'lessons_citation' => '— The XO Edu Lab Team',

            'meta_title' => 'Tutor Suite — A Self-Serve Tool for Independent Tutors & Experts (illustrative)',
            'meta_description' => 'Illustrative case study: a lightweight self-serve tool for independent tutors/experts to manage students, 1-on-1 scheduling, and tuition balances.',
        ]);

        $this->seedSolutionModules($project, [
            [
                'vi' => [
                    'title' => 'Mô hình self-serve một vai trò',
                    'description' => 'Giáo viên đăng ký, tự tạo "lớp" của mình và tự quản lý toàn bộ mà không cần ai duyệt hay cấp quyền.',
                    'features' => ['Không phân cấp Admin/Giảng viên/Học sinh', 'Mỗi giáo viên là một tenant dữ liệu độc lập'],
                    'technical_note' => 'Sanctum xác thực; dữ liệu tách biệt theo từng giáo viên.',
                ],
                'en' => [
                    'title' => 'Self-Serve, Single-Role Model',
                    'description' => 'A tutor signs up, creates their own "class", and manages everything themselves without anyone\'s approval.',
                    'features' => ['No Admin/Teacher/Student hierarchy', 'Each tutor is an independent data tenant'],
                    'technical_note' => 'Sanctum handles authentication; data is isolated per tutor.',
                ],
            ],
            [
                'vi' => [
                    'title' => 'Lịch dạy 1-1 & Công nợ nhẹ',
                    'description' => 'Giáo viên mở khung giờ rảnh, học viên đặt lịch, hệ thống chặn trùng lịch tự động và cảnh báo công nợ.',
                    'features' => ['Ghi nhận buổi học đã dạy để trừ gói', 'Cảnh báo khi học viên sắp hết buổi/quá hạn'],
                    'technical_note' => 'Giao diện lịch tối ưu cho dùng trên điện thoại.',
                ],
                'en' => [
                    'title' => '1-on-1 Scheduling & Lightweight Balances',
                    'description' => 'Tutors open time slots, students book them, and the system prevents double-booking and warns on balances.',
                    'features' => ['Logs taught sessions to deduct from packages', 'Warns when a student is low on sessions/overdue'],
                    'technical_note' => 'A calendar UI optimized for mobile use.',
                ],
            ],
        ]);
    }

    // Free Content Branding Platforms — 1 case study gộp chung 2 nền tảng học liệu miễn phí
    // (tailieutienghan.vn, toploigiai.vn) làm ví dụ minh họa cho cùng một chiến lược: dùng nội
    // dung bài học miễn phí để xây dựng thương hiệu/uy tín cho trung tâm hoặc chuyên gia cá
    // nhân. Theo docs/casestudy/free-content-branding/overview.md. Xác nhận từ chủ dự án
    // (2026-08-01): cả hai nền tảng do XO Edu phát triển. Nội dung challenges/feature_map/
    // journey lấy từ khảo sát trực tiếp 2 website công khai (không có tài liệu kỹ thuật nội bộ
    // như HanQuocNori/Seiko). Không seed hero_stats/scale_stats/architecture_layers/
    // tech_stack_groups/results — chưa xác nhận tech stack thật và không có số liệu vận hành
    // thật (traffic, số tài liệu, tỷ lệ chuyển đổi...), đừng bịa số. Footer tailieutienghan.vn
    // hiện ghi "Hàn Quốc Nori & EGLife Software" — cần xác nhận với chủ dự án xem có cần ghi
    // nhận tên khách hàng/đối tác trước khi công bố rộng rãi không. featured_image/og_image để
    // trống, cần ảnh chụp màn hình thật (mỗi solution module nên có ảnh riêng cho từng site).
    private function seedFreeContentBranding(Category $category): void
    {
        if (ProjectTranslation::where('slug', 'free-content-branding')->exists()) {
            return;
        }

        $project = Project::create([
            'category_id' => $category->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $project->translations()->create([
            'locale' => 'vi',
            'slug' => 'free-content-branding',
            'title' => 'Học liệu Miễn phí — Xây dựng Thương hiệu cho Trung tâm & Chuyên gia',
            'excerpt' => 'Xây dựng các nền tảng học liệu miễn phí (tài liệu tiếng Hàn, lời giải bài tập K-12) để thu hút lượng truy cập tự nhiên, xây dựng uy tín và nhận diện thương hiệu cho trung tâm đào tạo hoặc chuyên gia cá nhân.',

            'hero_eyebrow' => 'EdTech / Content-Led Branding',
            'hero_badges' => [
                ['icon' => 'menu_book', 'label' => 'Học liệu miễn phí'],
                ['icon' => 'travel_explore', 'label' => 'SEO / Organic Traffic'],
                ['icon' => 'verified', 'label' => 'Xây dựng thương hiệu'],
                ['icon' => 'shopping_cart', 'label' => 'Freemium'],
            ],

            'snapshot_items' => [
                ['icon' => 'category', 'label' => 'Industry', 'value' => 'EdTech / Content Marketing'],
                ['icon' => 'devices', 'label' => 'Type', 'value' => '2 nền tảng học liệu'],
                ['icon' => 'person_search', 'label' => 'Role', 'value' => 'Phát triển bởi XO Edu'],
            ],

            'challenges_heading' => 'Bài toán của trung tâm & chuyên gia cá nhân',
            'challenges_description' => 'Trung tâm đào tạo hoặc chuyên gia cá nhân cần một kênh xây dựng thương hiệu bền vững, không phụ thuộc hoàn toàn vào quảng cáo trả phí, để thu hút học viên tiềm năng.',
            'challenges' => [
                ['icon' => 'trending_down', 'color' => 'primary', 'title' => 'Phụ thuộc quảng cáo trả phí', 'description' => 'Chi phí quảng cáo tăng dần theo thời gian mà không tích lũy được tài sản thương hiệu lâu dài.', 'wide' => false],
                ['icon' => 'verified', 'color' => 'secondary', 'title' => 'Khó chứng minh uy tín chuyên môn', 'description' => 'Học viên cần thấy năng lực thật trước khi tin tưởng bỏ tiền ra học.', 'wide' => false],
                ['icon' => 'swap_horiz', 'color' => 'gold', 'title' => 'Thiếu cơ chế chuyển đổi', 'description' => 'Cần một funnel rõ ràng từ người đọc nội dung miễn phí sang khách hàng trả phí.', 'wide' => false],
            ],

            'feature_map_heading' => 'Mô hình Content-Led Branding',
            'feature_groups' => [
                ['title' => 'Thư viện nội dung miễn phí', 'badge_label' => 'SEO-FIRST', 'features' => ['Tài liệu/lời giải chất lượng cao', 'Tối ưu từ khóa tìm kiếm tự nhiên', 'Cập nhật liên tục theo nhu cầu thực tế']],
                ['title' => 'Xây dựng uy tín', 'badge_label' => 'TRUST', 'features' => ['Trang giới thiệu đội ngũ/giáo viên', 'Chuyên mục báo chí & giải thưởng', 'Cộng đồng hỏi đáp']],
                ['title' => 'Chuyển đổi có kiểm soát', 'badge_label' => 'FREEMIUM', 'features' => ['Nội dung miễn phí thu hút traffic', 'Tài liệu/khóa học premium tạo doanh thu', 'Tài khoản giữ chân người dùng quay lại']],
            ],

            'journey_heading' => 'Hành trình từ người đọc đến khách hàng',
            'journey_steps' => [
                ['title' => 'Tìm kiếm', 'description' => 'Người học tìm tài liệu/lời giải qua công cụ tìm kiếm'],
                ['title' => 'Trải nghiệm miễn phí', 'description' => 'Đọc/tải nội dung chất lượng, không rào cản'],
                ['title' => 'Tin tưởng thương hiệu', 'description' => 'Thấy đội ngũ giáo viên, uy tín, cộng đồng đứng sau nội dung'],
                ['title' => 'Chuyển đổi', 'description' => 'Đăng ký tài khoản, mua tài liệu premium hoặc khóa học'],
            ],

            'lessons_quote' => 'Nội dung miễn phí chất lượng cao là kênh xây dựng thương hiệu bền vững nhất cho một trung tâm hay chuyên gia cá nhân — traffic tự nhiên tích lũy theo thời gian thay vì biến mất khi ngừng chi ngân sách quảng cáo.',
            'lessons_citation' => '— Đội ngũ XO Edu Lab',

            'meta_title' => 'Học liệu Miễn phí — Xây dựng Thương hiệu cho Trung tâm & Chuyên gia',
            'meta_description' => 'Case study: xây dựng nền tảng học liệu miễn phí để thu hút traffic tự nhiên và xây dựng thương hiệu cho trung tâm đào tạo hoặc chuyên gia cá nhân.',
        ]);

        $project->translations()->create([
            'locale' => 'en',
            'slug' => 'free-content-branding',
            'title' => 'Free Learning Content — Building Brands for Centers & Independent Educators',
            'excerpt' => 'Building free learning-content platforms (Korean study materials, K-12 homework solutions) to drive organic traffic and build credibility and brand recognition for training centers or independent educators.',

            'hero_eyebrow' => 'EdTech / Content-Led Branding',
            'hero_badges' => [
                ['icon' => 'menu_book', 'label' => 'Free Content'],
                ['icon' => 'travel_explore', 'label' => 'SEO / Organic Traffic'],
                ['icon' => 'verified', 'label' => 'Brand Building'],
                ['icon' => 'shopping_cart', 'label' => 'Freemium'],
            ],

            'snapshot_items' => [
                ['icon' => 'category', 'label' => 'Industry', 'value' => 'EdTech / Content Marketing'],
                ['icon' => 'devices', 'label' => 'Type', 'value' => '2 content platforms'],
                ['icon' => 'person_search', 'label' => 'Role', 'value' => 'Built by XO Edu'],
            ],

            'challenges_heading' => 'The Challenge for Centers & Independent Educators',
            'challenges_description' => 'A training center or independent educator needs a sustainable way to build a brand — one that doesn\'t depend entirely on paid ads — to attract prospective learners.',
            'challenges' => [
                ['icon' => 'trending_down', 'color' => 'primary', 'title' => 'Reliance on paid ads', 'description' => 'Ad spend keeps rising over time without building any lasting brand asset.', 'wide' => false],
                ['icon' => 'verified', 'color' => 'secondary', 'title' => 'Hard to prove expertise', 'description' => 'Learners need to see real competence before trusting a paid offering.', 'wide' => false],
                ['icon' => 'swap_horiz', 'color' => 'gold', 'title' => 'No conversion mechanism', 'description' => 'Needed a clear funnel from free-content readers to paying customers.', 'wide' => false],
            ],

            'feature_map_heading' => 'The Content-Led Branding Model',
            'feature_groups' => [
                ['title' => 'Free Content Library', 'badge_label' => 'SEO-FIRST', 'features' => ['High-quality materials/solutions', 'Optimized for organic search', 'Continuously updated to match real demand']],
                ['title' => 'Credibility Building', 'badge_label' => 'TRUST', 'features' => ['Team/teacher introduction pages', 'Press & awards sections', 'Q&A community']],
                ['title' => 'Controlled Conversion', 'badge_label' => 'FREEMIUM', 'features' => ['Free content drives traffic', 'Premium materials/courses generate revenue', 'Accounts retain returning users']],
            ],

            'journey_heading' => 'From Reader to Customer',
            'journey_steps' => [
                ['title' => 'Search', 'description' => 'A learner finds materials/solutions via search engines'],
                ['title' => 'Free experience', 'description' => 'Reads/downloads quality content with no barrier'],
                ['title' => 'Trust the brand', 'description' => 'Sees the teacher team, credibility, and community behind the content'],
                ['title' => 'Convert', 'description' => 'Signs up, buys premium materials or a course'],
            ],

            'lessons_quote' => 'High-quality free content is the most durable brand-building channel for a center or independent educator — organic traffic compounds over time instead of vanishing the moment ad spend stops.',
            'lessons_citation' => '— The XO Edu Lab Team',

            'meta_title' => 'Free Learning Content — Building Brands for Centers & Independent Educators',
            'meta_description' => 'Case study: building free learning-content platforms to drive organic traffic and build brand credibility for training centers or independent educators.',
        ]);

        $this->seedSolutionModules($project, [
            [
                'vi' => [
                    'title' => 'Tài Liệu Tiếng Hàn — Học liệu & luyện thi TOPIK',
                    'description' => 'Nền tảng chia sẻ tài liệu, bài học và đề luyện thi TOPIK miễn phí cho người học tiếng Hàn tại Việt Nam (tailieutienghan.vn).',
                    'features' => ['Ngữ pháp/giao tiếp/nghe/đọc theo cấp độ', 'Ngân hàng đề luyện thi TOPIK', 'Tài khoản & giỏ hàng cho tài liệu premium'],
                    'technical_note' => 'Nội dung tổ chức theo kỹ năng và cấp độ để tối ưu tìm kiếm tự nhiên cho từng nhóm từ khóa luyện thi.',
                ],
                'en' => [
                    'title' => 'Tài Liệu Tiếng Hàn — Study Materials & TOPIK Prep',
                    'description' => 'A free platform sharing Korean-language study materials, lessons, and TOPIK practice tests for learners in Vietnam (tailieutienghan.vn).',
                    'features' => ['Grammar/communication/listening/reading by level', 'TOPIK practice test bank', 'Account & cart for premium materials'],
                    'technical_note' => 'Content organized by skill and level to target long-tail exam-prep search keywords.',
                ],
            ],
            [
                'vi' => [
                    'title' => 'Top Lời Giải — Lời giải bài tập K-12',
                    'description' => 'Nền tảng lời giải, bài giảng và tài liệu học tập bám sát 3 bộ sách giáo khoa mới cho học sinh lớp 1–12 (toploigiai.vn).',
                    'features' => ['Lời giải theo lớp/môn/bộ sách giáo khoa', 'Ngân hàng đề thi theo khối lớp/môn', 'Đội ngũ giáo viên biên soạn & kiểm duyệt nội dung'],
                    'technical_note' => 'Phân loại nội dung theo lớp × môn × bộ sách để phủ lượng lớn từ khóa tìm kiếm dài (long-tail).',
                ],
                'en' => [
                    'title' => 'Top Lời Giải — K-12 Homework Solutions',
                    'description' => 'A platform for homework solutions, lectures, and study materials aligned with all three new textbook curricula for grades 1-12 (toploigiai.vn).',
                    'features' => ['Solutions filtered by grade/subject/textbook set', 'Exam question bank by grade/subject', 'A teacher team authoring & reviewing content'],
                    'technical_note' => 'Content classified by grade x subject x textbook set to cover a large volume of long-tail search keywords.',
                ],
            ],
        ]);
    }
}
