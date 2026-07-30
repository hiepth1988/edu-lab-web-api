<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectTranslation;
use Illuminate\Database\Seeder;

class ProjectsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedTopThi();
        $this->seedMsd();
        $this->seedHanQuocNori();
    }

    private function seedTopThi(): void
    {
        if (ProjectTranslation::where('slug', 'topthi')->exists()) {
            return;
        }

        $project = Project::create(['status' => 'published', 'published_at' => now()]);

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
    private function seedMsd(): void
    {
        if (ProjectTranslation::where('slug', 'msd-learning-platform')->exists()) {
            return;
        }

        $msd = Project::create(['status' => 'draft', 'published_at' => null]);

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
    private function seedHanQuocNori(): void
    {
        if (ProjectTranslation::where('slug', 'hanquocnori')->exists()) {
            return;
        }

        $project = Project::create(['status' => 'published', 'published_at' => now()]);

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
}
