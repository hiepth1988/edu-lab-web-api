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
            'problem' => 'Cần một nền tảng thi trực tuyến đáng tin cậy, chống gian lận và có khả năng phân tích kết quả học tập ở quy mô lớn.',
            'solution_text' => 'Xây dựng ngân hàng câu hỏi có gắn tag độ khó/kỹ năng, cơ chế random đề và đáp án, chấm điểm tự động, cùng dashboard phân tích hành vi làm bài.',
            'result' => 'Nền tảng vận hành ổn định, trở thành living lab để thử nghiệm Exam Engine, Knowledge Graph và Learning Analytics trước khi đóng gói thành sản phẩm riêng.',
            'meta_title' => 'TopThi — Living Lab cho Exam & AI Learning',
            'meta_description' => 'Case study TopThi: nền tảng thi trực tuyến làm bằng chứng năng lực Exam Engine và Learning Analytics.',
        ]);

        $project->translations()->create([
            'locale' => 'en',
            'slug' => 'topthi',
            'title' => 'TopThi — A Living Lab for Exam & AI Learning',
            'excerpt' => 'Building an online exam platform as proof of real-world execution for Exam Engine and Learning Analytics.',
            'problem' => 'Needed a trustworthy, cheat-resistant online exam platform capable of analyzing learning outcomes at scale.',
            'solution_text' => 'Built a question bank tagged by difficulty/skill, exam and answer randomization, automatic grading, and a dashboard analyzing exam-taking behavior.',
            'result' => 'The platform runs reliably and became a living lab to test Exam Engine, Knowledge Graph and Learning Analytics before packaging them into standalone products.',
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
    }

    // MSD Learning Platform — draft theo casestudy/msd/case-study-msd.md.
    // status = draft (không phải published): tài liệu nguồn yêu cầu xin xác nhận
    // MSD Vietnam trước khi đăng công khai (tên tổ chức + dữ liệu học viên nhạy cảm:
    // dân tộc, khuyết tật). Không seed metrics vì chưa có số liệu vận hành thật —
    // đừng bịa số, điền qua CMS khi có số liệu từ MSD. featured_image/og_image để
    // trống, cần ảnh chụp màn hình thật (xem checklist trong chat).
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
            'problem' => '<p>MSD Vietnam (Viện Nghiên cứu Quản lý Phát triển bền vững, thành viên United Way Vietnam từ 2021) cần một nền tảng học trực tuyến để triển khai các chương trình đào tạo cộng đồng về phát triển bền vững, hướng đến cả nhóm học viên phổ thông lẫn các nhóm yếu thế — dân tộc thiểu số, người khuyết tật — vốn là đối tượng ưu tiên trong báo cáo tác động với các nhà tài trợ. Bài toán đặt ra gồm:</p>
<ul>
<li>Quản lý nội dung khóa học đa định dạng (video, văn bản, bài tập, flashcard) với quy trình biên tập, kiểm duyệt rõ ràng cho đội ngũ vận hành.</li>
<li>Bảo vệ nội dung video có bản quyền/trả phí khỏi bị tải lậu hoặc tua nhanh để né học đủ bài — đảm bảo học viên học đúng, đủ nội dung trước khi được công nhận hoàn thành.</li>
<li>Đo lường và báo cáo được mức độ tiếp cận tới các nhóm học viên yếu thế (dân tộc, khuyết tật, trình độ học vấn) để phục vụ báo cáo tài trợ/tác động xã hội.</li>
<li>Gửi thông báo, nhắc nhở học tập đúng lúc, đúng đối tượng (theo khóa học, theo nhóm, hoặc toàn bộ học viên) để duy trì tỷ lệ hoàn thành khóa học.</li>
<li>Tự động cấp chứng chỉ hoàn thành và có khả năng mở rộng nội dung song ngữ để tăng khả năng tiếp cận.</li>
</ul>',
            'solution_text' => '<h3>Kiến trúc hệ thống</h3>
<p>Hệ thống được xây dựng theo kiến trúc 3 lớp tách biệt nhưng dùng chung một API trung tâm: <strong>msd-api</strong> (Laravel 11, PHP 8.2) xử lý toàn bộ nghiệp vụ và bảo mật, <strong>msd-front</strong> (Nuxt 3) là cổng học viên công khai tối ưu SEO/SSR, còn <strong>msd-admin</strong> (Vue 3 + Vite + TypeScript + Pinia) là khu vực quản trị nội bộ.</p>
<h3>LMS lõi: Khóa học – Bài học – Quiz</h3>
<p>Nội dung được tổ chức theo mô hình Khóa học → Bài học (4 loại: video, văn bản/hình ảnh, bài tập, flashcard) → ngân hàng câu hỏi có gắn độ khó, hỗ trợ câu hỏi âm thanh và tự luận, chấm điểm tự động. Tiến độ học viên được theo dõi theo từng bài học và từng khóa học.</p>
<h3>Bảo vệ nội dung video &amp; phụ đề song ngữ bằng AI</h3>
<p>Điểm kỹ thuật trung tâm của dự án là pipeline bảo vệ nội dung video: mỗi video bài giảng khi tải lên chạy qua một hàng đợi xử lý nền theo các bước rõ ràng — tách phụ đề tự động bằng <strong>OpenAI Whisper</strong> cho tiếng Việt rồi dịch song ngữ sang tiếng Anh, gắn watermark, mã hóa sang định dạng streaming thích ứng HLS bằng ffmpeg, và tùy chọn đẩy lên lưu trữ AWS S3. Trình phát video tùy chỉnh dựa trên Video.js vô hiệu hóa thao tác tua nhanh để đảm bảo học viên xem đủ nội dung trước khi được công nhận hoàn thành — mô hình đào tạo theo kiểu chứng nhận hoàn thành thay vì chỉ tính lượt xem.</p>
<h3>Chứng chỉ hoàn thành tự động</h3>
<p>Chứng chỉ được cá nhân hóa và sinh tự động dạng PDF/PNG ngay khi học viên hoàn tất khóa học, thông qua công cụ thiết kế mẫu cho phép định vị các trường thông tin (họ tên, tên khóa học, thời lượng, ngày hoàn thành) trên ảnh nền.</p>
<h3>Hệ thống thông báo có lịch trình</h3>
<p>Một engine thông báo chạy nền qua Redis kết hợp Pusher cho phép đội vận hành gửi thông báo tức thời hoặc lên lịch theo ngày/tuần/tháng, nhắm tới toàn bộ học viên, một khóa học cụ thể hoặc danh sách tùy chỉnh — kèm cơ chế tự động nhắc học viên không hoạt động trong một khoảng thời gian cấu hình được.</p>
<h3>Dữ liệu học viên &amp; đo lường tác động xã hội</h3>
<p>Khác với các nền tảng LMS thương mại thông thường, mô hình dữ liệu học viên của hệ thống ghi nhận thêm dân tộc, tình trạng khuyết tật và trình độ học vấn, phục vụ trực tiếp việc đo lường mức độ tiếp cận nhóm yếu thế và xuất báo cáo Excel cho công tác báo cáo tác động xã hội với nhà tài trợ.</p>
<h3>Dashboard báo cáo cho đội vận hành</h3>
<p>Đội vận hành MSD theo dõi toàn bộ hoạt động qua một dashboard báo cáo: tổng học viên, học viên hoạt động/hoàn thành, khóa học và giáo viên nổi bật, biểu đồ truy cập theo ngày.</p>
<h3>Nội dung CMS &amp; tìm kiếm nội bộ</h3>
<p>Nền tảng có các module CMS cho tin tức, tài nguyên, FAQ, banner, testimonial và hồ sơ giáo viên, cùng cơ chế tìm kiếm nội bộ tự đồng bộ — không phụ thuộc dịch vụ bên thứ ba — cho khóa học, bài học, tài nguyên và tin tức.</p>
<h3>Xác thực &amp; phân quyền</h3>
<p>Học viên xác thực qua Google OAuth, còn đội quản trị dùng hệ thống phân quyền theo vai trò riêng biệt (Spatie Permission), với guard admin tách biệt hoàn toàn khỏi tài khoản học viên.</p>
<h3>Công nghệ sử dụng</h3>
<ul>
<li><strong>Backend:</strong> Laravel 11, PHP 8.2, Sanctum, Spatie Permission, DomPDF + Intervention Image, Maatwebsite Excel, Redis, Pusher, Guzzle (gọi OpenAI Whisper), Google API Client.</li>
<li><strong>Admin:</strong> Vue 3, Vite, TypeScript, Pinia, Tailwind + SCSS, VeeValidate, Chart.js, FilePond, Quill, Vue Draggable.</li>
<li><strong>Front:</strong> Nuxt 3, Pinia, Nuxt i18n, Nuxt Sitemap + nuxt-jsonld, Google Sign-In, Firebase SDK, Video.js (kèm plugin tùy chỉnh chặn tua video), Pusher-js, Swiper.</li>
<li><strong>Hạ tầng &amp; tích hợp:</strong> MySQL, Redis (hàng đợi + cache), ffmpeg (mã hóa/watermark video), AWS S3 (lưu trữ tùy chọn), OpenAI Whisper + GPT-3.5 (phụ đề song ngữ).</li>
</ul>
<h3>Định hướng tiếp theo</h3>
<p>Đội dự án đang tiếp tục hoàn thiện bộ kiểm thử tự động và pipeline CI/CD cho cả 3 repo, đồng thời mở rộng các loại nội dung bài học (flashcard, văn bản/hình ảnh) và cơ chế lưu lịch sử làm bài kiểm tra.</p>',
            'result' => '<ul>
<li>Nền tảng vận hành ổn định phục vụ chương trình đào tạo cộng đồng của MSD, với khả năng mở rộng thêm khóa học/nội dung mới.</li>
<li>Quy trình cấp chứng chỉ, gửi thông báo và bảo vệ nội dung video được tự động hóa gần như hoàn toàn, giảm tải vận hành thủ công cho đội ngũ MSD.</li>
<li>Dữ liệu học viên theo nhóm yếu thế được thu thập có hệ thống ngay từ mô hình dữ liệu gốc, hỗ trợ trực tiếp công tác báo cáo tác động xã hội với nhà tài trợ/United Way Vietnam.</li>
</ul>
<p><em>Số liệu cụ thể (số học viên, tỷ lệ hoàn thành, số chứng chỉ đã cấp) sẽ được cập nhật sau khi có xác nhận và dữ liệu thực tế từ MSD.</em></p>',
            'meta_title' => 'MSD Learning Platform — Nền tảng học trực tuyến vì phát triển bền vững',
            'meta_description' => 'Case study MSD Learning Platform: nền tảng LMS đào tạo cộng đồng, bảo vệ nội dung video bản quyền và đo lường tác động xã hội tới nhóm học viên yếu thế.',
        ]);

        $msd->translations()->create([
            'locale' => 'en',
            'slug' => 'msd-learning-platform',
            'title' => 'MSD Learning Platform — An Online Learning Platform for Sustainable Development',
            'excerpt' => 'Building an LMS platform for community training that measures social impact among ethnic-minority and disabled learners while protecting copyrighted course content.',
            'problem' => '<p>MSD Vietnam (Center for Sustainability Development Management Studies, a United Way Vietnam member since 2021) needed an online learning platform to run community training programs on sustainable development, serving both general learners and disadvantaged groups — ethnic minorities and people with disabilities — who are priority groups in donor impact reporting. The core problems to solve were:</p>
<ul>
<li>Managing multi-format course content (video, text, exercises, flashcards) with a clear editorial and review workflow for the operations team.</li>
<li>Protecting copyrighted/paid video content from piracy or fast-forwarding to skip required viewing — ensuring learners actually watch the full content before being marked complete.</li>
<li>Measuring and reporting reach to disadvantaged learner groups (ethnicity, disability, education level) for donor/social-impact reporting.</li>
<li>Sending timely, targeted notifications and study reminders (by course, by group, or platform-wide) to sustain course completion rates.</li>
<li>Automatically issuing completion certificates, with room to expand into bilingual content for greater accessibility.</li>
</ul>',
            'solution_text' => '<h3>System architecture</h3>
<p>The system is built as three separate layers sharing one central API: <strong>msd-api</strong> (Laravel 11, PHP 8.2) handles all business logic and security, <strong>msd-front</strong> (Nuxt 3) is the public learner portal optimized for SEO/SSR, and <strong>msd-admin</strong> (Vue 3 + Vite + TypeScript + Pinia) is the internal admin area.</p>
<h3>LMS core: Courses – Lessons – Quizzes</h3>
<p>Content is organized as Course → Lesson (4 types: video, text/image, exercise, flashcard) → a question bank tagged by difficulty, supporting audio and essay questions with automatic grading. Learner progress is tracked per lesson and per course.</p>
<h3>Video protection &amp; AI-generated bilingual subtitles</h3>
<p>The project\'s central technical piece is the video-protection pipeline: every uploaded lecture video runs through a background processing queue with clear stages — automatic Vietnamese transcription via <strong>OpenAI Whisper</strong>, translated to English for bilingual subtitles, watermarking, adaptive-streaming (HLS) encoding via ffmpeg, and an optional push to AWS S3 storage. A custom Video.js-based player disables seeking so learners must watch the full content before being marked complete — a certification-of-completion model rather than simple view counting.</p>
<h3>Automatic completion certificates</h3>
<p>Certificates are personalized and auto-generated as PDF/PNG the moment a learner finishes a course, via a template designer that positions information fields (full name, course title, duration, completion date) on a background image.</p>
<h3>Scheduled notification engine</h3>
<p>A background notification engine running on Redis with Pusher lets the operations team send notifications instantly or on a daily/weekly/monthly schedule, targeting all learners, a specific course, or a custom list — with automatic reminders for learners who have gone inactive for a configurable period.</p>
<h3>Learner data &amp; social-impact measurement</h3>
<p>Unlike typical commercial LMS platforms, the learner data model also records ethnicity, disability status, and education level, directly supporting reach measurement for disadvantaged groups and Excel exports for donor impact reporting.</p>
<h3>Operations reporting dashboard</h3>
<p>The MSD operations team monitors activity through a reporting dashboard: total learners, active/completed learners, top courses and teachers, and daily traffic charts.</p>
<h3>CMS content &amp; internal search</h3>
<p>The platform includes CMS modules for news, resources, FAQs, banners, testimonials, and teacher profiles, plus a self-synchronizing internal search mechanism — with no third-party dependency — covering courses, lessons, resources, and news.</p>
<h3>Authentication &amp; access control</h3>
<p>Learners authenticate via Google OAuth, while admins use a separate role-based permission system (Spatie Permission), with an admin guard fully separate from learner accounts.</p>
<h3>Technology stack</h3>
<ul>
<li><strong>Backend:</strong> Laravel 11, PHP 8.2, Sanctum, Spatie Permission, DomPDF + Intervention Image, Maatwebsite Excel, Redis, Pusher, Guzzle (OpenAI Whisper), Google API Client.</li>
<li><strong>Admin:</strong> Vue 3, Vite, TypeScript, Pinia, Tailwind + SCSS, VeeValidate, Chart.js, FilePond, Quill, Vue Draggable.</li>
<li><strong>Front:</strong> Nuxt 3, Pinia, Nuxt i18n, Nuxt Sitemap + nuxt-jsonld, Google Sign-In, Firebase SDK, Video.js (with a custom seek-blocking plugin), Pusher-js, Swiper.</li>
<li><strong>Infrastructure &amp; integrations:</strong> MySQL, Redis (queue + cache), ffmpeg (video encoding/watermarking), AWS S3 (optional storage), OpenAI Whisper + GPT-3.5 (bilingual subtitles).</li>
</ul>
<h3>What\'s next</h3>
<p>The team is continuing to build out automated test coverage and a CI/CD pipeline across all three repositories, while expanding lesson content types (flashcards, text/image) and adding persistent quiz-attempt history.</p>',
            'result' => '<ul>
<li>The platform runs reliably for MSD\'s community training programs, with room to scale to new courses and content.</li>
<li>Certificate issuance, notifications, and video content protection are almost entirely automated, reducing manual operational load on the MSD team.</li>
<li>Data on disadvantaged learner groups is captured systematically from the ground up in the data model, directly supporting social-impact reporting to donors and United Way Vietnam.</li>
</ul>
<p><em>Specific figures (learner counts, completion rate, certificates issued) will be added once confirmed with real data from MSD.</em></p>',
            'meta_title' => 'MSD Learning Platform — An Online Learning Platform for Sustainable Development',
            'meta_description' => 'MSD Learning Platform case study: a community-training LMS protecting copyrighted video content and measuring social impact among disadvantaged learners.',
        ]);
    }

    // HanQuocNori — theo docs/hanquocnori/*.md (project/business/technical overview).
    // status = published: dự án đã vận hành nhiều năm (migrations 2020–2022), không có
    // dữ liệu nhạy cảm kiểu MSD. Không seed metrics vì tài liệu nguồn không có số liệu
    // vận hành thật (users, doanh thu, tỷ lệ hoàn thành...) — đừng bịa số, điền qua CMS
    // khi có số liệu thật từ chủ dự án. featured_image/og_image để trống, cần ảnh chụp
    // màn hình thật.
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
            'problem' => '<p>HanQuocNori cần một nền tảng học tiếng Hàn trực tuyến vận hành như một hệ sinh thái hoàn chỉnh thay vì chỉ là một website khóa học đơn thuần. Bài toán đặt ra gồm:</p>
<ul>
<li>Quản lý nội dung học tập đa dạng (video DASH streaming, audio, tài liệu) theo cấu trúc khóa học → bài học → bài tập, với 6 loại bài tập khác nhau (trắc nghiệm, điền từ, nghe hiểu, đọc hiểu, dịch thuật, hội thoại) và flashcard từ vựng.</li>
<li>Vận hành một hệ thống thi trực tuyến độc lập (ExamMeta → Exam → ExamConfig → ExamSchedule → TakeExam) với chấm điểm tự động cho trắc nghiệm và quy trình chấm tay cho phần tự luận.</li>
<li>Triển khai thương mại điện tử đầy đủ: giỏ hàng, 3 phương thức thanh toán (online, COD, MoMo), mã giảm giá, và một chương trình affiliate cho phép người dùng giới thiệu và nhận hoa hồng.</li>
<li>Tổ chức lớp học 1-1 với giáo viên: đặt lịch theo slot khả dụng, quản lý số buổi học còn lại của từng học viên, gọi video trực tiếp, và ràng buộc thời gian hủy lịch.</li>
<li>Quản trị nội dung và người dùng ở quy mô lớn, hỗ trợ đa ngôn ngữ (Việt, Anh, Hàn) và thông báo real-time giữa giáo viên và học viên.</li>
</ul>',
            'solution_text' => '<h3>Kiến trúc hệ thống</h3>
<p>Hệ thống được xây dựng theo mô hình 3 lớp: <strong>api-hanquocnori</strong> (Laravel 7) là API trung tâm xử lý toàn bộ nghiệp vụ, <strong>front-hanquocnori</strong> (Nuxt.js 2, SSR) là website công khai phục vụ người học, và <strong>admin-hanquocnori</strong> (Vue.js 2 + Vuetify) là khu vực quản trị nội bộ với hơn 300 màn hình và 100+ route quản lý.</p>
<p>Phía backend áp dụng Repository Pattern kết hợp Service Layer (Controller → Service → Repository → Eloquent Model), với 76 model, 47+ repository và 186 migration — tách bạch rõ ràng giữa xử lý nghiệp vụ và truy xuất dữ liệu để dễ mở rộng qua nhiều năm vận hành.</p>
<h3>LMS lõi: Khóa học – Bài học – Bài tập – Flashcard</h3>
<p>Nội dung được tổ chức theo Khóa học → Bài học (video DASH streaming, audio, văn bản) → Bài tập (6 loại: trắc nghiệm, điền từ, nghe hiểu, đọc hiểu, dịch thuật, hội thoại). Tiến độ học tập được tính theo từng bài học rồi tổng hợp lên tiến độ khóa học, với cache Redis theo từng người dùng để tối ưu tốc độ tải menu bài học.</p>
<h3>Hệ thống thi trực tuyến độc lập</h3>
<p>Một phân hệ thi được thiết kế riêng với cấu trúc phân cấp ExamMeta (kỳ thi) → Exam (đề thi) → ExamConfig (cấu hình nghe/đọc/viết) → ExamSchedule (lịch thi) → TakeExam (lần thi của học viên). Bài trắc nghiệm được chấm điểm tự động ngay khi nộp bài; bài tự luận được đánh dấu chờ và có màn hình riêng cho giáo viên/admin chấm thủ công. Hệ thống cũng cung cấp bảng xếp hạng theo điểm thi.</p>
<h3>Thương mại điện tử & Affiliate</h3>
<p>Luồng mua hàng hỗ trợ khóa học, sách và combo, với 3 phương thức thanh toán (chuyển khoản, COD, MoMo qua webhook callback) và cơ chế áp mã giảm giá/affiliate được kiểm tra thời hạn, số lần sử dụng và ràng buộc không tự dùng mã của chính mình. Khi thanh toán MoMo thành công, hệ thống tự động cấp quyền truy cập khóa học (CourseManager) với ngày kích hoạt và ngày hết hạn.</p>
<h3>Lớp học 1-1 & video call</h3>
<p>Học viên mua gói buổi học (ComboCoursesOneOne), sau đó đặt lịch vào các slot giáo viên mở sẵn (BookLesson), với ràng buộc không trùng lịch và chỉ được hủy trước một khoảng thời gian cấu hình được. Video call 1-1 được tích hợp qua Stringee bằng JWT token sinh riêng cho từng phiên học, kèm cơ chế lưu lại conversation ID để tra cứu ghi hình, và học viên đánh giá buổi học/giáo viên sau khi kết thúc.</p>
<h3>Real-time, cache & queue</h3>
<p>Thông báo real-time (đặt lịch, hủy lịch, tin nhắn) được đẩy qua Pusher. Các tác vụ tốn thời gian — gửi email xác nhận mua hàng, gửi email hủy lịch học, xử lý/mã hóa video — chạy nền qua Laravel Queue với Redis, vận hành production bằng Supervisor.</p>
<h3>Công nghệ sử dụng</h3>
<ul>
<li><strong>Backend:</strong> Laravel 7, PHP 7.2+, JWT (tymon/jwt-auth), Laravel Socialite (Facebook/Google OAuth), Redis, AWS S3, Pusher, Guzzle.</li>
<li><strong>Admin:</strong> Vue.js 2, Vuetify 2, Vuex, CKEditor 5, Vee-validate, Chart.js.</li>
<li><strong>Front:</strong> Nuxt.js 2 (SSR), Vuetify, Vuex (35 module), nuxt-i18n, Video.js + videojs-contrib-dash + dashjs, Pusher-js, Google Analytics/GTM, sitemap tự động.</li>
<li><strong>Tích hợp:</strong> MoMo (thanh toán), Stringee (video call), Infusionsoft CRM, PayPal, Facebook/Google OAuth.</li>
</ul>',
            'result' => '<ul>
<li>Một nền tảng EdTech vận hành liên tục nhiều năm, tích hợp trọn vẹn LMS, thi trực tuyến, thương mại điện tử và lớp học 1-1 trong cùng một hệ sinh thái thay vì các hệ thống rời rạc.</li>
<li>Quy trình cấp quyền truy cập khóa học, chấm thi trắc nghiệm và thanh toán MoMo được tự động hóa hoàn toàn, giảm tải thao tác thủ công cho đội vận hành.</li>
<li>Kiến trúc Repository + Service Layer rõ ràng giúp hệ thống mở rộng ổn định qua hàng trăm màn hình quản trị và hàng chục nghiệp vụ khác nhau trong suốt vòng đời dự án.</li>
</ul>',
            'meta_title' => 'HanQuocNori — Nền tảng học tiếng Hàn trực tuyến toàn diện',
            'meta_description' => 'Case study HanQuocNori: nền tảng EdTech học tiếng Hàn với LMS, thi trực tuyến, thương mại điện tử và lớp học 1-1 qua video call.',
        ]);

        $project->translations()->create([
            'locale' => 'en',
            'slug' => 'hanquocnori',
            'title' => 'HanQuocNori — A Comprehensive Online Korean Learning Platform',
            'excerpt' => 'Building a full-stack Korean-learning EdTech platform: LMS, online exams, e-commerce, and 1-on-1 video classes with teachers.',
            'problem' => '<p>HanQuocNori needed an online Korean-learning platform that runs as a complete ecosystem rather than a simple course website. The core problems to solve were:</p>
<ul>
<li>Managing diverse learning content (DASH-streamed video, audio, documents) structured as course → lesson → exercise, with 6 exercise types (multiple choice, fill-in-the-blank, listening, reading, translation, dialogue) plus vocabulary flashcards.</li>
<li>Running a standalone online exam system (ExamMeta → Exam → ExamConfig → ExamSchedule → TakeExam) with automatic grading for multiple-choice sections and a manual grading workflow for essay sections.</li>
<li>Delivering full e-commerce: shopping cart, three payment methods (online transfer, COD, MoMo), discount codes, and an affiliate program letting users refer others and earn commission.</li>
<li>Running 1-on-1 teacher classes: booking against available time slots, tracking each learner\'s remaining lesson credits, live video calls, and cancellation-deadline rules.</li>
<li>Administering content and users at scale, with multi-language support (Vietnamese, English, Korean) and real-time notifications between teachers and learners.</li>
</ul>',
            'solution_text' => '<h3>System architecture</h3>
<p>The system follows a three-layer model: <strong>api-hanquocnori</strong> (Laravel 7) is the central API handling all business logic, <strong>front-hanquocnori</strong> (Nuxt.js 2, SSR) is the public learner-facing website, and <strong>admin-hanquocnori</strong> (Vue.js 2 + Vuetify) is the internal admin area with 300+ screens and 100+ management routes.</p>
<p>The backend follows a Repository Pattern combined with a Service Layer (Controller → Service → Repository → Eloquent Model), with 76 models, 47+ repositories, and 186 migrations — a clear separation between business logic and data access that kept the system maintainable across years of operation.</p>
<h3>Core LMS: courses, lessons, exercises, flashcards</h3>
<p>Content is organized as Course → Lesson (DASH-streamed video, audio, text) → Exercise (6 types: multiple choice, fill-in-the-blank, listening, reading, translation, dialogue). Learning progress is computed per lesson and rolled up to course-level progress, with per-user Redis caching to keep the lesson menu fast.</p>
<h3>Standalone online exam system</h3>
<p>A dedicated exam module uses a hierarchical structure: ExamMeta (overall exam event) → Exam (specific test) → ExamConfig (listening/reading/writing configuration) → ExamSchedule → TakeExam (a learner\'s attempt). Multiple-choice answers are graded automatically on submission; essay answers are flagged as pending and routed to a dedicated screen for teachers/admins to grade manually. The system also provides a leaderboard ranked by exam score.</p>
<h3>E-commerce & affiliate program</h3>
<p>The purchase flow covers courses, books, and bundles, with three payment methods (bank transfer, COD, and MoMo via webhook callback) and a discount/affiliate-code engine that validates expiry, usage limits, and a rule preventing users from applying their own affiliate code. On a successful MoMo payment, the system automatically grants course access (CourseManager) with an activation date and expiration date.</p>
<h3>1-on-1 classes & video calling</h3>
<p>Learners purchase a lesson-credit package (ComboCoursesOneOne), then book into teacher-published time slots (BookLesson), with double-booking prevention and a configurable cancellation deadline. 1-on-1 video calling is integrated via Stringee using a JWT token generated per session, with the conversation ID stored for recording lookup, and learners rate the session/teacher afterward.</p>
<h3>Real-time, caching & queues</h3>
<p>Real-time notifications (bookings, cancellations, messages) are pushed via Pusher. Time-consuming tasks — purchase confirmation emails, cancellation emails, video processing — run in the background via Laravel Queue with Redis, operated in production through Supervisor.</p>
<h3>Technology stack</h3>
<ul>
<li><strong>Backend:</strong> Laravel 7, PHP 7.2+, JWT (tymon/jwt-auth), Laravel Socialite (Facebook/Google OAuth), Redis, AWS S3, Pusher, Guzzle.</li>
<li><strong>Admin:</strong> Vue.js 2, Vuetify 2, Vuex, CKEditor 5, Vee-validate, Chart.js.</li>
<li><strong>Front:</strong> Nuxt.js 2 (SSR), Vuetify, Vuex (35 modules), nuxt-i18n, Video.js + videojs-contrib-dash + dashjs, Pusher-js, Google Analytics/GTM, automatic sitemap.</li>
<li><strong>Integrations:</strong> MoMo (payment), Stringee (video calling), Infusionsoft CRM, PayPal, Facebook/Google OAuth.</li>
</ul>',
            'result' => '<ul>
<li>An EdTech platform that has run continuously for years, integrating LMS, online exams, e-commerce, and 1-on-1 classes into a single ecosystem instead of disconnected systems.</li>
<li>Course-access provisioning, multiple-choice grading, and MoMo payment confirmation are fully automated, reducing manual operational overhead.</li>
<li>The Repository + Service Layer architecture scaled cleanly across hundreds of admin screens and dozens of distinct business flows over the project\'s lifetime.</li>
</ul>',
            'meta_title' => 'HanQuocNori — A Comprehensive Online Korean Learning Platform',
            'meta_description' => 'HanQuocNori case study: a Korean-learning EdTech platform with LMS, online exams, e-commerce, and 1-on-1 video classes.',
        ]);
    }

    // Seiko LMS — theo docs/casestudy/seiko/*.md (project/business/technical overview).
    // status = published: tài liệu không nêu tên tổ chức/khách hàng cụ thể (chỉ mô tả
    // chung "trung tâm đào tạo ngoại ngữ") và không có dữ liệu nhạy cảm cần xin xác nhận.
    // Không seed metrics vì tài liệu nguồn không có số liệu vận hành thật (số học sinh,
    // số lớp, tỷ lệ điểm danh...) — đừng bịa số, điền qua CMS khi có số liệu thật.
    // featured_image/og_image để trống, cần ảnh chụp màn hình thật.
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
            'problem' => '<p>Trung tâm đào tạo ngoại ngữ cần một hệ thống quản lý học tập (LMS) để thay thế các thao tác thủ công rời rạc (Excel, giấy tờ, trao đổi tay đôi) trong toàn bộ vòng đời một kỳ học — từ tuyển sinh, phân lớp, xếp lịch, giảng dạy, đến chấm bài, điểm danh và báo cáo. Bài toán đặt ra gồm:</p>
<ul>
<li>Tổ chức học vụ theo nhiều cấp: kỳ học → khóa học → lớp học → buổi học, với nhiều giảng viên và học sinh gán linh hoạt vào từng lớp, hỗ trợ nhiều cấp độ (N1–N5) song song.</li>
<li>Giao bài tập, bài thi (Mini Test và Comprehensive) kèm tài liệu học tập đa định dạng, theo dõi trạng thái nộp bài đúng hạn/trễ/chưa nộp, và chấm điểm thủ công có kiểm soát thời điểm mở đáp án.</li>
<li>Điểm danh từng buổi học (có mặt, vắng có phép/không phép, đến muộn, về sớm) gắn liền với quy trình xin nghỉ có minh chứng và phê duyệt cho cả học sinh lẫn giảng viên.</li>
<li>Nhập liệu hàng loạt học sinh và lịch học từ Excel để giảm thao tác nhập tay khi tuyển sinh quy mô lớn, cùng khả năng xuất báo cáo Excel (lịch học, chấm công, tiến độ học tập) phục vụ vận hành.</li>
<li>Thông báo tức thời, đúng đối tượng (giảng viên/học sinh) cho từng sự kiện nghiệp vụ — nộp đơn nghỉ, giao bài, nộp bài, duyệt đơn — để giảm độ trễ thông tin giữa các bên.</li>
</ul>',
            'solution_text' => '<h3>Kiến trúc hệ thống</h3>
<p>Hệ thống gồm 3 phần: <strong>lmsapi-develop</strong> (Laravel 9) là API trung tâm, <strong>lmsadmin-develop</strong> (Vue 3 + Vite + TypeScript + Pinia) là khu vực quản trị/giảng dạy nội bộ, và <strong>landingpage-develop</strong> (Nuxt 3, SSR) là trang giới thiệu công khai cho học sinh tiềm năng đăng ký. Xác thực dùng Laravel Sanctum với 4 cấp vai trò (Admin, Sub-Admin, Giảng viên, Học sinh), được gate ở tầng middleware riêng cho từng nhóm route (admin, giảng viên, học sinh, hoặc kết hợp).</p>
<h3>Cấu trúc học vụ phân cấp</h3>
<p>Dữ liệu học vụ được mô hình hóa theo Kỳ học (Project) → Khóa học (Course) → Lớp học (ClassRoom) → Buổi học (PeriodClass), với quan hệ nhiều-nhiều giữa lớp và giảng viên/học sinh. Mỗi buổi học là nơi gắn kết tài liệu, bài tập, bài thi và điểm danh, hỗ trợ cả ca sáng lẫn ca chiều trong cùng một ngày. Toàn bộ bản ghi dùng UUID và xóa mềm (soft delete), đảm bảo lịch sử điểm danh/bài nộp không mất khi học sinh rời lớp.</p>
<h3>Bài tập & bài thi</h3>
<p>Giảng viên tạo bài tập (Vocabulary/Grammar/Reading) và bài thi (Mini Test không giới hạn số lượng, Comprehensive tối đa 1 bài/buổi học) với đề và đáp án dạng file hoặc link, thời gian trễ cho phép cấu hình được. Học sinh nộp bài (hỗ trợ nhiều file, có thể nộp lại trước hạn), hệ thống tự tính trạng thái đúng hạn/trễ/chưa nộp dựa trên mốc thời gian. Giảng viên chấm điểm theo thang điểm tự định nghĩa và kiểm soát thời điểm mở khóa đáp án cho học sinh xem.</p>
<h3>Điểm danh & quy trình nghỉ phép</h3>
<p>Giảng viên điểm danh từng buổi học với 5 trạng thái (có mặt, vắng có phép, vắng không phép, đến muộn, về sớm — ghi cả số phút muộn/sớm). Học sinh và giảng viên đều có thể nộp đơn xin nghỉ kèm minh chứng, đi qua quy trình duyệt/từ chối có ghi lý do, tự động tạo bản ghi điểm danh tương ứng khi được duyệt.</p>
<h3>Import/Export dữ liệu</h3>
<p>Import học sinh và lịch học hàng loạt từ Excel, xử lý nền qua Queue Job với 7 bước validate (khóa học/lớp tồn tại, email và mã học sinh không trùng, định dạng và độ dài trường, xác thực email qua DNS MX) trước khi tạo tài khoản — cho phép tuyển sinh quy mô lớn mà không mất trải nghiệm nhập liệu từng người. Song song đó, hệ thống xuất được 7 loại báo cáo Excel: lịch học, tiến độ học tập, kết quả bài tập, chấm công giảng viên/học sinh, danh sách học sinh và chi tiết buổi học.</p>
<h3>Thông báo real-time</h3>
<p>12 loại sự kiện nghiệp vụ (nộp đơn nghỉ, duyệt/từ chối, hủy buổi học, đổi giảng viên, giao/nộp bài tập và bài thi...) được ghi nhận vào database và đẩy tức thời qua Pusher WebSocket tới đúng đối tượng liên quan, kèm badge đếm thông báo chưa đọc cập nhật real-time trên giao diện.</p>
<h3>Công nghệ sử dụng</h3>
<ul>
<li><strong>Backend:</strong> Laravel 9, PHP 7.3+/8.0+, Laravel Sanctum, Maatwebsite Excel, PHPMailer/SMTP2GO, Pusher.</li>
<li><strong>Admin:</strong> Vue 3, Vite, TypeScript, Pinia (36 store), TinyMCE, VeeValidate, Tailwind CSS.</li>
<li><strong>Landing page:</strong> Nuxt 3 (SSR), Pinia, @nuxtjs/i18n, Tailwind CSS.</li>
<li><strong>Đa ngôn ngữ:</strong> Việt, Anh, Nhật, Hàn — phù hợp đặc thù trung tâm đào tạo ngoại ngữ.</li>
</ul>',
            'result' => '<ul>
<li>Số hóa trọn vẹn quy trình vận hành một trung tâm đào tạo ngoại ngữ, từ tuyển sinh, phân lớp, giảng dạy đến báo cáo, thay thế các thao tác thủ công rời rạc trước đó.</li>
<li>Quy trình bài tập/bài thi, điểm danh và nghỉ phép được chuẩn hóa và tự động cập nhật trạng thái, giảm đáng kể khối lượng thao tác thủ công cho giảng viên và đội vận hành.</li>
<li>Import/Export Excel giúp xử lý tuyển sinh và báo cáo ở quy mô lớn mà không cần nhập liệu thủ công từng dòng.</li>
</ul>',
            'meta_title' => 'Seiko LMS — Hệ thống quản lý học tập cho trung tâm đào tạo ngoại ngữ',
            'meta_description' => 'Case study Seiko LMS: hệ thống quản lý học tập số hóa phân lớp, lịch học, bài tập/bài thi, điểm danh và báo cáo cho trung tâm đào tạo ngoại ngữ.',
        ]);

        $project->translations()->create([
            'locale' => 'en',
            'slug' => 'seiko-lms',
            'title' => 'Seiko LMS — A Learning Management System for Language Training Centers',
            'excerpt' => 'Digitizing the full operating workflow of a language training center: class placement, scheduling, assignments/exams, attendance, leave requests, and Excel reporting in one system.',
            'problem' => '<p>A language training center needed a learning management system (LMS) to replace disconnected manual processes (spreadsheets, paperwork, one-off communication) across the entire lifecycle of a term — from enrollment, class placement, and scheduling, to teaching, grading, attendance, and reporting. The core problems to solve were:</p>
<ul>
<li>Organizing academic structure across multiple levels: term → course → class → class period, with teachers and students flexibly assigned to classes and support for multiple proficiency levels (N1–N5) running in parallel.</li>
<li>Assigning exercises and exams (Mini Test and Comprehensive) alongside multi-format learning materials, tracking submission status (on-time/late/not submitted), and manual grading with controlled answer-reveal timing.</li>
<li>Taking attendance per class period (present, excused absence, unexcused absence, late, early leave) tied to an evidence-based leave-request and approval workflow for both students and teachers.</li>
<li>Bulk-importing students and class schedules from Excel to cut down manual data entry during large-scale enrollment, plus exporting Excel reports (schedules, timesheets, learning progress) for operations.</li>
<li>Sending instant, correctly targeted notifications (teacher/student) for each business event — leave requests, assignments, submissions, approvals — to reduce information lag between parties.</li>
</ul>',
            'solution_text' => '<h3>System architecture</h3>
<p>The system consists of three parts: <strong>lmsapi-develop</strong> (Laravel 9) is the central API, <strong>lmsadmin-develop</strong> (Vue 3 + Vite + TypeScript + Pinia) is the internal admin/teaching area, and <strong>landingpage-develop</strong> (Nuxt 3, SSR) is the public landing page for prospective students to register. Authentication uses Laravel Sanctum with a four-tier role system (Admin, Sub-Admin, Lecturer, Student), gated by dedicated middleware per route group (admin, lecturer, student, or combined).</p>
<h3>Hierarchical academic structure</h3>
<p>Academic data is modeled as Term (Project) → Course → ClassRoom → PeriodClass (class period), with many-to-many relationships between classes and teachers/students. Each class period is where materials, exercises, exams, and attendance are attached, supporting both morning and afternoon sessions on the same day. All records use UUIDs and soft deletes, ensuring attendance/submission history is preserved even after a student leaves a class.</p>
<h3>Assignments &amp; exams</h3>
<p>Teachers create exercises (Vocabulary/Grammar/Reading) and exams (unlimited Mini Tests, at most one Comprehensive exam per period) with questions and answers as files or links, and a configurable late-submission grace period. Students submit work (multiple files supported, resubmission allowed before the deadline), and the system automatically computes on-time/late/not-submitted status based on the deadline. Teachers grade against a self-defined scoring scale and control when answers become visible to students.</p>
<h3>Attendance &amp; leave-request workflow</h3>
<p>Teachers take attendance per class period across five states (present, excused absence, unexcused absence, late, early leave — recording exact minutes late/early). Both students and teachers can submit leave requests with supporting evidence, routed through an approval/rejection workflow with a required reason, automatically creating the corresponding attendance record once approved.</p>
<h3>Data import/export</h3>
<p>Bulk student and schedule import from Excel runs as a background Queue Job with a 7-step validation pipeline (course/class existence, unique email and student ID, field format and length, email verified via DNS MX lookup) before account creation — enabling large-scale enrollment without one-by-one manual entry. The system also exports 7 Excel report types: schedules, learning progress, exercise results, teacher/student timesheets, student rosters, and class-period details.</p>
<h3>Real-time notifications</h3>
<p>12 business event types (leave requests, approvals/rejections, class cancellations, teacher reassignment, assignment/exam creation and submission...) are recorded to the database and pushed instantly via Pusher WebSocket to the correct recipients, with a real-time unread-count badge in the UI.</p>
<h3>Technology stack</h3>
<ul>
<li><strong>Backend:</strong> Laravel 9, PHP 7.3+/8.0+, Laravel Sanctum, Maatwebsite Excel, PHPMailer/SMTP2GO, Pusher.</li>
<li><strong>Admin:</strong> Vue 3, Vite, TypeScript, Pinia (36 stores), TinyMCE, VeeValidate, Tailwind CSS.</li>
<li><strong>Landing page:</strong> Nuxt 3 (SSR), Pinia, @nuxtjs/i18n, Tailwind CSS.</li>
<li><strong>Multi-language:</strong> Vietnamese, English, Japanese, Korean — matching the needs of a language training center.</li>
</ul>',
            'result' => '<ul>
<li>Fully digitized the operations of a language training center, from enrollment and class placement to teaching and reporting, replacing previously disconnected manual processes.</li>
<li>Standardized and automated status tracking for assignments/exams, attendance, and leave requests, significantly cutting manual workload for teachers and operations staff.</li>
<li>Excel import/export enables large-scale enrollment and reporting without row-by-row manual data entry.</li>
</ul>',
            'meta_title' => 'Seiko LMS — A Learning Management System for Language Training Centers',
            'meta_description' => 'Seiko LMS case study: an LMS digitizing class placement, scheduling, assignments/exams, attendance, and reporting for a language training center.',
        ]);
    }

    // Corporate L&D Platform — MINH HỌA/GIẢ ĐỊNH, theo docs/casestudy/corporate-ld/overview.md.
    // KHÔNG phải dự án thật: chưa có khách hàng doanh nghiệp/tổ chức thật nào triển khai
    // hệ thống này. Viết để phủ nhóm "Đào tạo nội bộ doanh nghiệp" trong menu "Dành cho ai"
    // (đề xuất trong docs/casestudy-gaps-proposal.md) khi chưa có dự án thật khớp bối cảnh.
    // Không seed metrics — không bịa số liệu vận hành. Khi có dự án thật, thay thế toàn bộ
    // nội dung case này (không chỉ điền thêm số liệu).
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
            'problem' => '<p><em>Đây là case study minh họa mô tả năng lực giải pháp cho nhóm khách hàng "Đào tạo nội bộ doanh nghiệp", chưa phải dự án đã triển khai thật.</em></p>
<p>Nhiều doanh nghiệp vận hành đào tạo nội bộ rời rạc qua email, file Excel và các buổi training thủ công, dẫn tới các bài toán:</p>
<ul>
<li>Nhân viên mới không có lộ trình onboarding tự động — HR phải giao từng khóa học thủ công theo từng người.</li>
<li>Các khóa đào tạo bắt buộc (an toàn lao động, quy định nội bộ, bảo mật dữ liệu...) không có cơ chế theo dõi hạn chót và nhắc nhở tự động, dẫn tới tỷ lệ hoàn thành thấp mà không ai biết cho tới kỳ audit.</li>
<li>Không có cách gán khóa học tự động theo phòng ban/vai trò/cấp bậc — mọi việc gán khóa đều làm tay.</li>
<li>HR và quản lý không có báo cáo tuân thủ theo thời gian thực để biết ai đã hoàn thành, ai còn thiếu, theo từng phòng ban.</li>
</ul>',
            'solution_text' => '<p><em>Phần mô tả dưới đây là hướng giải pháp minh họa, không phải mô tả một hệ thống đã triển khai thật.</em></p>
<h3>Cơ cấu tổ chức làm trung tâm dữ liệu</h3>
<p>Thay vì mô hình "khóa học công khai, học viên tự đăng ký" của trung tâm đào tạo, hệ thống lấy sơ đồ tổ chức (phòng ban → vai trò → nhân viên) làm gốc để gán khóa học tự động theo quy tắc nghiệp vụ (ví dụ: mọi nhân viên phòng Kỹ thuật phải hoàn thành khóa An toàn lao động trong 30 ngày kể từ ngày vào làm).</p>
<h3>Theo dõi tuân thủ (compliance tracking)</h3>
<p>Mỗi khóa bắt buộc có hạn chót cá nhân hóa theo ngày gia nhập hoặc ngày được gán, hệ thống tự động nhắc qua email/thông báo nội bộ khi gần hạn, và cung cấp báo cáo tổng hợp trạng thái tuân thủ cho quản lý/HR theo phòng ban, xuất được Excel phục vụ audit.</p>
<h3>Tích hợp nội dung chuẩn công nghiệp</h3>
<p>Hỗ trợ nhập nội dung theo chuẩn SCORM/xAPI để tận dụng thư viện bài giảng e-learning có sẵn của doanh nghiệp, thay vì buộc phải tạo lại nội dung từ đầu trên nền tảng.</p>
<h3>Khác biệt với mô hình NGO/cộng đồng</h3>
<p>Khác với các nền tảng đào tạo cộng đồng đo lường tác động xã hội, trọng tâm ở đây là tỷ lệ tuân thủ và hiệu suất đào tạo gắn với KPI nhân sự — cùng là LMS nhưng mục tiêu nghiệp vụ và đối tượng dữ liệu khác nhau.</p>
<h3>Công nghệ minh họa</h3>
<ul>
<li><strong>Backend:</strong> Laravel, Sanctum, Spatie Permission (phân quyền theo vai trò tổ chức), Maatwebsite Excel (báo cáo tuân thủ), queue cho nhắc nhở tự động.</li>
<li><strong>Admin:</strong> Vue 3 + TypeScript, biểu đồ báo cáo tuân thủ.</li>
<li><strong>Tích hợp:</strong> SCORM/xAPI player cho nội dung e-learning chuẩn công nghiệp.</li>
</ul>',
            'result' => '<p><em>Chưa có số liệu vận hành thật — đây là case minh họa. Khi có dự án thật khớp bối cảnh đào tạo nội bộ doanh nghiệp, phần kết quả sẽ được thay thế bằng số liệu thực tế qua CMS.</em></p>',
            'meta_title' => 'Corporate L&D Platform — Nền tảng đào tạo nội bộ doanh nghiệp (minh họa)',
            'meta_description' => 'Case study minh họa: nền tảng đào tạo nội bộ doanh nghiệp với onboarding tự động, theo dõi tuân thủ và gán khóa học theo cơ cấu tổ chức.',
        ]);

        $project->translations()->create([
            'locale' => 'en',
            'slug' => 'corporate-ld-platform',
            'title' => 'Corporate L&D Platform — Compliance-Driven Internal Training',
            'excerpt' => '(Illustrative case) A corporate internal-training platform model: automated onboarding, compliance-tracked mandatory training, and org-structure-based course assignment.',
            'problem' => '<p><em>This is an illustrative case study describing the kind of solution the team could deliver for the "Corporate Internal Training" audience segment — not a project that has actually been delivered.</em></p>
<p>Many companies run internal training through disconnected email threads, spreadsheets, and manual sessions, leading to:</p>
<ul>
<li>No automated onboarding path for new hires — HR has to manually assign courses to each person.</li>
<li>Mandatory compliance courses (workplace safety, internal policy, data security...) lack deadline tracking and automated reminders, resulting in low completion rates nobody notices until audit time.</li>
<li>No way to auto-assign courses by department/role/level — every assignment is done manually.</li>
<li>HR and managers lack real-time compliance reporting to know who has completed training and who hasn\'t, broken down by department.</li>
</ul>',
            'solution_text' => '<p><em>The description below is an illustrative solution direction, not a description of an already-deployed system.</em></p>
<h3>Org structure as the data backbone</h3>
<p>Instead of the "public course, self-enrollment" model used by training centers, the system uses the organizational chart (department → role → employee) as the source of truth to auto-assign courses based on business rules (e.g., every Engineering employee must complete Workplace Safety training within 30 days of their start date).</p>
<h3>Compliance tracking</h3>
<p>Each mandatory course has a personalized deadline based on hire date or assignment date, the system automatically sends email/in-app reminders as deadlines approach, and provides aggregated compliance status reports for managers/HR by department, exportable to Excel for audits.</p>
<h3>Industry-standard content integration</h3>
<p>Supports SCORM/xAPI content import to leverage a company\'s existing e-learning library, instead of forcing content to be rebuilt from scratch on the platform.</p>
<h3>Contrast with the NGO/community model</h3>
<p>Unlike community-training platforms that measure social impact, the focus here is compliance rate and training performance tied to HR KPIs — same LMS category, different business objective and data model.</p>
<h3>Illustrative technology stack</h3>
<ul>
<li><strong>Backend:</strong> Laravel, Sanctum, Spatie Permission (org-role-based access), Maatwebsite Excel (compliance reporting), queue-driven automated reminders.</li>
<li><strong>Admin:</strong> Vue 3 + TypeScript, compliance reporting dashboards.</li>
<li><strong>Integrations:</strong> SCORM/xAPI player for industry-standard e-learning content.</li>
</ul>',
            'result' => '<p><em>No real operating figures yet — this is an illustrative case. Once a real project matching the corporate L&D context exists, this section will be replaced with actual data via the CMS.</em></p>',
            'meta_title' => 'Corporate L&D Platform — Compliance-Driven Internal Training (illustrative)',
            'meta_description' => 'Illustrative case study: a corporate internal-training platform with automated onboarding, compliance tracking, and org-structure-based course assignment.',
        ]);
    }

    // School Management System — MINH HỌA/GIẢ ĐỊNH, theo docs/casestudy/school-k12/overview.md.
    // KHÔNG phải dự án thật: chưa có trường học/tổ chức giáo dục thật nào triển khai hệ
    // thống này. Viết để phủ nhóm "Trường học" trong menu "Dành cho ai" (đề xuất trong
    // docs/casestudy-gaps-proposal.md) khi chưa có dự án thật khớp bối cảnh. Không seed
    // metrics — không bịa số liệu vận hành. Khi có dự án thật, thay thế toàn bộ nội dung
    // case này (không chỉ điền thêm số liệu).
    private function seedSchoolK12(Category $category): void
    {
        if (ProjectTranslation::where('slug', 'school-management-system')->exists()) {
            return;
        }

        $project = Project::create([
            'category_id' => $category->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $project->translations()->create([
            'locale' => 'vi',
            'slug' => 'school-management-system',
            'title' => 'School Management System — Hệ thống quản lý trường học K-12',
            'excerpt' => '(Case minh họa) Mô hình hệ thống quản lý trường học theo năm học/học kỳ cố định: sổ điểm, học bạ điện tử, liên lạc phụ huynh–giáo viên, thời khóa biểu chính quy.',
            'problem' => '<p><em>Đây là case study minh họa mô tả năng lực giải pháp cho nhóm khách hàng "Trường học", chưa phải dự án đã triển khai thật.</em></p>
<p>Trường phổ thông hoặc trung tâm giáo dục vận hành theo mô hình chính quy có những đặc thù khác hẳn trung tâm đào tạo ngoại ngữ hay nền tảng LMS linh hoạt:</p>
<ul>
<li>Học vụ tổ chức theo năm học → học kỳ → lớp cố định, khác mô hình "khóa học mở linh hoạt" có thể bắt đầu bất kỳ lúc nào của trung tâm đào tạo.</li>
<li>Một học sinh học nhiều môn song song trong cùng học kỳ, mỗi môn có giáo viên và thời khóa biểu riêng — không phải một khóa học đơn lẻ.</li>
<li>Cần sổ điểm, học bạ điện tử, xếp loại học lực/hạnh kiểm theo đúng quy chế đánh giá của nhà trường.</li>
<li>Phụ huynh cần được thông báo tình hình học tập, điểm số, hạnh kiểm của con em — một kênh liên lạc phụ huynh–giáo viên chính thức, vai trò không tồn tại trong các hệ thống LMS trung tâm.</li>
<li>Thời khóa biểu cố định cho cả năm học, phân công giáo viên theo môn/lớp — khác lịch dạy linh hoạt theo slot của mô hình 1-1 hoặc lớp mở.</li>
</ul>',
            'solution_text' => '<p><em>Phần mô tả dưới đây là hướng giải pháp minh họa, không phải mô tả một hệ thống đã triển khai thật.</em></p>
<h3>Cấu trúc học vụ theo năm học</h3>
<p>Mô hình phân cấp: Năm học → Học kỳ → Khối lớp → Lớp học cố định (sĩ số, giáo viên chủ nhiệm) → Môn học (mỗi môn có giáo viên bộ môn và thời khóa biểu riêng trong tuần). Một học sinh gắn với một lớp cố định suốt học kỳ và học nhiều môn song song, khác với khái niệm "khóa học" của trung tâm đào tạo.</p>
<h3>Sổ điểm &amp; học bạ điện tử</h3>
<p>Giáo viên bộ môn nhập điểm theo cột điểm quy định (miệng, 15 phút, 1 tiết, học kỳ...), hệ thống tự tính điểm trung bình môn và xếp loại học lực/hạnh kiểm theo quy chế, tổng hợp thành học bạ điện tử theo từng học kỳ/năm học.</p>
<h3>Liên lạc phụ huynh–giáo viên</h3>
<p>Phụ huynh có tài khoản riêng (không phải tài khoản học sinh), xem được điểm số, thời khóa biểu, thông báo nghỉ học/vi phạm, và có kênh trao đổi trực tiếp với giáo viên chủ nhiệm.</p>
<h3>Thời khóa biểu chính quy</h3>
<p>Thời khóa biểu được lập cho cả học kỳ/năm học, phân công giáo viên cố định theo môn và lớp, khác với lịch học linh hoạt theo slot đăng ký của mô hình trung tâm hoặc gia sư 1-1.</p>
<h3>Công nghệ minh họa</h3>
<ul>
<li><strong>Backend:</strong> Laravel, Sanctum, phân quyền đa vai trò (Admin/Giáo viên/Học sinh/Phụ huynh).</li>
<li><strong>Admin/Giáo viên:</strong> giao diện nhập điểm, quản lý thời khóa biểu dạng lịch.</li>
<li><strong>Phụ huynh:</strong> cổng thông tin riêng, thông báo qua email/push.</li>
<li><strong>Báo cáo:</strong> xuất học bạ, bảng điểm dạng PDF/Excel theo học kỳ.</li>
</ul>',
            'result' => '<p><em>Chưa có số liệu vận hành thật — đây là case minh họa. Khi có dự án thật khớp bối cảnh trường học K-12, phần kết quả sẽ được thay thế bằng số liệu thực tế qua CMS.</em></p>',
            'meta_title' => 'School Management System — Hệ thống quản lý trường học K-12 (minh họa)',
            'meta_description' => 'Case study minh họa: hệ thống quản lý trường học theo năm học/học kỳ, sổ điểm, học bạ điện tử và liên lạc phụ huynh–giáo viên.',
        ]);

        $project->translations()->create([
            'locale' => 'en',
            'slug' => 'school-management-system',
            'title' => 'School Management System — A K-12 School Management Platform',
            'excerpt' => '(Illustrative case) A school management system model built around fixed academic years/semesters: gradebooks, digital report cards, parent–teacher communication, and formal timetabling.',
            'problem' => '<p><em>This is an illustrative case study describing the kind of solution the team could deliver for the "Schools" audience segment — not a project that has actually been delivered.</em></p>
<p>A K-12 school or a formally run education center has needs quite different from a language training center or a flexible LMS platform:</p>
<ul>
<li>Academics are organized as academic year → semester → fixed class, unlike the "flexible open enrollment" model of a training center that can start a course at any time.</li>
<li>A student takes multiple subjects in parallel within the same semester, each with its own teacher and timetable — not a single course.</li>
<li>Needs a gradebook, digital report cards, and academic/conduct ranking that follow the school\'s official grading policy.</li>
<li>Parents need visibility into their child\'s academic standing, grades, and conduct — a formal parent–teacher communication channel that doesn\'t exist in the center-based LMS cases.</li>
<li>Timetables are fixed for the whole academic year, with teachers assigned per subject/class — unlike the flexible slot-based scheduling of 1-on-1 or open-enrollment models.</li>
</ul>',
            'solution_text' => '<p><em>The description below is an illustrative solution direction, not a description of an already-deployed system.</em></p>
<h3>Academic-year-based structure</h3>
<p>Hierarchical model: Academic Year → Semester → Grade Level → Fixed Class (roster, homeroom teacher) → Subject (each with its own teacher and weekly timetable). A student belongs to one fixed class for the whole semester while taking multiple subjects in parallel, unlike the "course" concept used by training centers.</p>
<h3>Gradebook &amp; digital report cards</h3>
<p>Subject teachers enter grades against the school\'s defined grading columns (oral, quiz, test, semester exam...), the system automatically computes subject averages and academic/conduct rankings per policy, compiled into a digital report card per semester/academic year.</p>
<h3>Parent–teacher communication</h3>
<p>Parents get their own accounts (separate from student accounts) to view grades, timetables, absence/conduct notices, and a direct communication channel with the homeroom teacher.</p>
<h3>Formal timetabling</h3>
<p>Timetables are built for the whole semester/academic year, with teachers permanently assigned per subject and class, unlike the flexible slot-based scheduling used by training centers or 1-on-1 tutoring.</p>
<h3>Illustrative technology stack</h3>
<ul>
<li><strong>Backend:</strong> Laravel, Sanctum, multi-role access control (Admin/Teacher/Student/Parent).</li>
<li><strong>Admin/Teacher:</strong> grade-entry UI, calendar-based timetable management.</li>
<li><strong>Parent portal:</strong> dedicated portal, email/push notifications.</li>
<li><strong>Reporting:</strong> report card and grade sheet export as PDF/Excel per semester.</li>
</ul>',
            'result' => '<p><em>No real operating figures yet — this is an illustrative case. Once a real project matching the K-12 school context exists, this section will be replaced with actual data via the CMS.</em></p>',
            'meta_title' => 'School Management System — A K-12 School Management Platform (illustrative)',
            'meta_description' => 'Illustrative case study: a K-12 school management system with academic-year structure, gradebooks, digital report cards, and parent–teacher communication.',
        ]);
    }

    // Tutor Suite — MINH HỌA/GIẢ ĐỊNH, theo docs/casestudy/independent-tutor/overview.md.
    // KHÔNG phải dự án thật: chưa có giáo viên/chuyên gia độc lập thật nào dùng hệ thống
    // này. Viết để phủ nhóm "Chuyên gia & Giáo viên độc lập" trong menu "Dành cho ai" (đề
    // xuất trong docs/casestudy-gaps-proposal.md) khi chưa có dự án thật khớp bối cảnh.
    // Không seed metrics — không bịa số liệu vận hành. Khi có dự án thật, thay thế toàn bộ
    // nội dung case này (không chỉ điền thêm số liệu).
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
            'problem' => '<p><em>Đây là case study minh họa mô tả năng lực giải pháp cho nhóm khách hàng "Chuyên gia & Giáo viên độc lập", chưa phải dự án đã triển khai thật.</em></p>
<p>Giáo viên/chuyên gia tự do có bài toán khác hẳn các tổ chức lớn:</p>
<ul>
<li>Cần tự tạo lớp, quản lý danh sách học viên riêng mà không qua trung tâm hay tổ chức trung gian.</li>
<li>Thu học phí nhỏ lẻ theo buổi/gói buổi học, cần theo dõi công nợ từng học viên (đã đóng bao nhiêu, còn nợ bao nhiêu buổi) mà không cần hệ thống kế toán phức tạp.</li>
<li>Cần lịch dạy cá nhân: đặt lịch với từng học viên theo slot rảnh, tránh trùng lịch, nhắc lịch tự động.</li>
<li>Cần công cụ đơn giản, không đòi hỏi vận hành hạ tầng riêng — khác hoàn toàn với các hệ thống lớn vốn cần đội IT/vận hành.</li>
</ul>',
            'solution_text' => '<p><em>Phần mô tả dưới đây là hướng giải pháp minh họa, không phải mô tả một hệ thống đã triển khai thật.</em></p>
<h3>Mô hình self-serve, một vai trò duy nhất</h3>
<p>Không có phân cấp Admin/Giảng viên/Học sinh như hệ thống lớn — giáo viên đăng ký tài khoản, tự tạo "lớp" của mình (một học viên hoặc một nhóm nhỏ), tự quản lý toàn bộ mà không cần ai duyệt hay cấp quyền.</p>
<h3>Quản lý học viên &amp; công nợ nhẹ</h3>
<p>Mỗi học viên có hồ sơ đơn giản (tên, liên hệ, gói học đã mua), giáo viên ghi nhận buổi học đã dạy để trừ dần vào gói, hệ thống tự cảnh báo khi học viên sắp hết buổi hoặc quá hạn thanh toán.</p>
<h3>Lịch dạy cá nhân &amp; đặt lịch 1-1</h3>
<p>Giáo viên mở các khung giờ rảnh, học viên đặt lịch vào khung giờ đó, hệ thống chặn trùng lịch tự động — tương tự phần lớp 1-1 trong các nền tảng lớn nhưng thu nhỏ lại: không qua trung tâm trung gian, giáo viên tự vận hành công cụ của mình như một SaaS cá nhân.</p>
<h3>Khác biệt cốt lõi</h3>
<p>Đây là mô hình self-serve SaaS — giáo viên/chuyên gia là khách hàng trực tiếp sử dụng công cụ cho chính mình, không phải hệ thống được một tổ chức lớn mua về vận hành nội bộ cho hàng trăm/nghìn người dùng.</p>
<h3>Công nghệ minh họa</h3>
<ul>
<li><strong>Backend:</strong> Laravel, Sanctum (mỗi giáo viên là một tenant dữ liệu độc lập).</li>
<li><strong>Frontend:</strong> giao diện lịch đơn giản, tối ưu cho dùng trên điện thoại.</li>
<li><strong>Thanh toán:</strong> ghi nhận thủ công hoặc tích hợp cổng thanh toán nhỏ lẻ tùy nhu cầu thực tế.</li>
</ul>',
            'result' => '<p><em>Chưa có số liệu vận hành thật — đây là case minh họa. Khi có dự án thật khớp bối cảnh giáo viên/chuyên gia độc lập, phần kết quả sẽ được thay thế bằng số liệu thực tế qua CMS.</em></p>',
            'meta_title' => 'Tutor Suite — Công cụ self-serve cho giáo viên độc lập (minh họa)',
            'meta_description' => 'Case study minh họa: công cụ nhẹ cho gia sư/chuyên gia tự do quản lý học viên, lịch dạy 1-1 và công nợ học phí.',
        ]);

        $project->translations()->create([
            'locale' => 'en',
            'slug' => 'tutor-suite',
            'title' => 'Tutor Suite — A Self-Serve Tool for Independent Tutors & Experts',
            'excerpt' => '(Illustrative case) A lightweight tool letting independent tutors/experts manage their own students, 1-on-1 schedules, and tuition balances without an operations team.',
            'problem' => '<p><em>This is an illustrative case study describing the kind of solution the team could deliver for the "Independent Educators & Experts" audience segment — not a project that has actually been delivered.</em></p>
<p>Independent tutors/experts face a very different set of problems than large organizations:</p>
<ul>
<li>Need to create their own classes and manage their own student list without going through a training center or intermediary.</li>
<li>Collect small, per-session or per-package tuition and track each student\'s balance (sessions paid vs. remaining) without a complex accounting system.</li>
<li>Need a personal teaching schedule: booking with individual students against open time slots, avoiding double-booking, and automatic reminders.</li>
<li>Need a simple tool that doesn\'t require running dedicated infrastructure — unlike large systems that need a dedicated IT/operations team.</li>
</ul>',
            'solution_text' => '<p><em>The description below is an illustrative solution direction, not a description of an already-deployed system.</em></p>
<h3>Self-serve, single-role model</h3>
<p>No Admin/Teacher/Student hierarchy like large systems — a tutor signs up, creates their own "class" (a single student or a small group), and manages everything themselves without needing anyone\'s approval or permission grant.</p>
<h3>Lightweight student &amp; balance management</h3>
<p>Each student has a simple profile (name, contact, purchased package), the tutor logs each session taught to deduct from the package, and the system automatically warns when a student is running low on sessions or overdue on payment.</p>
<h3>Personal scheduling &amp; 1-on-1 booking</h3>
<p>The tutor opens available time slots, students book into those slots, and the system automatically prevents double-booking — conceptually similar to the 1-on-1 class feature in larger platforms, but scaled down: no intermediary center, the tutor runs their own tool like a personal SaaS.</p>
<h3>Core differentiator</h3>
<p>This is a self-serve SaaS model — the tutor/expert is the direct customer using the tool for themselves, not a system purchased and operated internally by a large organization for hundreds or thousands of users.</p>
<h3>Illustrative technology stack</h3>
<ul>
<li><strong>Backend:</strong> Laravel, Sanctum (each tutor as an independent data tenant).</li>
<li><strong>Frontend:</strong> a simple calendar UI, optimized for mobile use.</li>
<li><strong>Payments:</strong> manual logging or a lightweight payment gateway integration depending on actual needs.</li>
</ul>',
            'result' => '<p><em>No real operating figures yet — this is an illustrative case. Once a real project matching the independent tutor/expert context exists, this section will be replaced with actual data via the CMS.</em></p>',
            'meta_title' => 'Tutor Suite — A Self-Serve Tool for Independent Tutors & Experts (illustrative)',
            'meta_description' => 'Illustrative case study: a lightweight self-serve tool for independent tutors/experts to manage students, 1-on-1 scheduling, and tuition balances.',
        ]);
    }
}
