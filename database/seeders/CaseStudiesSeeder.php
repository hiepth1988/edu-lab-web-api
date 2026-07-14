<?php

namespace Database\Seeders;

use App\Models\CaseStudy;
use App\Models\CaseStudyTranslation;
use Illuminate\Database\Seeder;

class CaseStudiesSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedTopThi();
        $this->seedMsd();
    }

    private function seedTopThi(): void
    {
        if (CaseStudyTranslation::where('slug', 'topthi')->exists()) {
            return;
        }

        $case = CaseStudy::create(['status' => 'published', 'published_at' => now()]);

        $case->translations()->create([
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

        $case->translations()->create([
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
            $m = $case->metrics()->create(['value' => $metric['value'], 'sort_order' => $i]);
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
        if (CaseStudyTranslation::where('slug', 'msd-learning-platform')->exists()) {
            return;
        }

        $msd = CaseStudy::create(['status' => 'draft', 'published_at' => null]);

        $msd->translations()->create([
            'locale' => 'vi',
            'slug' => 'msd-learning-platform',
            'title' => 'MSD Learning Platform — Nền tảng học trực tuyến vì phát triển bền vững',
            'excerpt' => 'Xây dựng nền tảng LMS phục vụ đào tạo cộng đồng, hỗ trợ đo lường tác động xã hội (người học dân tộc thiểu số, người khuyết tật) và bảo vệ nội dung khóa học có bản quyền.',
            'problem' => 'MSD Vietnam (Viện Nghiên cứu Quản lý Phát triển bền vững, thành viên United Way Vietnam) cần một nền tảng học trực tuyến để triển khai các chương trình đào tạo cộng đồng về phát triển bền vững, phục vụ cả học viên phổ thông lẫn các nhóm yếu thế — dân tộc thiểu số, người khuyết tật — vốn là đối tượng ưu tiên trong báo cáo tác động với nhà tài trợ.

Ba bài toán cốt lõi đặt ra: bảo vệ nội dung video có bản quyền khỏi bị tải lậu hoặc tua nhanh để né học đủ bài; đo lường và báo cáo được mức độ tiếp cận tới các nhóm học viên yếu thế phục vụ báo cáo tài trợ; và tự động hóa việc nhắc học, cấp chứng chỉ để duy trì tỷ lệ hoàn thành khóa học mà không cần đội vận hành can thiệp thủ công cho từng học viên.',
            'solution_text' => 'Hệ thống được xây dựng theo kiến trúc 3 lớp tách biệt nhưng dùng chung một API trung tâm: msd-api (Laravel 11, PHP 8.2) xử lý toàn bộ nghiệp vụ và bảo mật, msd-front (Nuxt 3) là cổng học viên công khai tối ưu SEO/SSR, còn msd-admin (Vue 3 + Vite + TypeScript + Pinia) là khu vực quản trị nội bộ. Lõi LMS tổ chức nội dung theo mô hình Khóa học → Bài học (video, văn bản/hình ảnh, bài tập, flashcard) → ngân hàng câu hỏi có gắn độ khó, hỗ trợ câu hỏi âm thanh và tự luận, chấm điểm tự động; tiến độ học viên được theo dõi theo từng bài học và từng khóa học.

Điểm kỹ thuật trung tâm của dự án là pipeline bảo vệ nội dung video: mỗi video bài giảng khi tải lên chạy qua một hàng đợi xử lý nền theo các bước rõ ràng — tách phụ đề tự động bằng OpenAI Whisper cho tiếng Việt rồi dịch song ngữ sang tiếng Anh, gắn watermark, mã hóa sang định dạng streaming thích ứng HLS bằng ffmpeg, và tùy chọn đẩy lên lưu trữ AWS S3. Trình phát video tùy chỉnh dựa trên Video.js vô hiệu hóa thao tác tua nhanh để đảm bảo học viên xem đủ nội dung trước khi được công nhận hoàn thành — mô hình đào tạo theo kiểu chứng nhận hoàn thành thay vì chỉ tính lượt xem.

Chứng chỉ hoàn thành được cá nhân hóa và sinh tự động dạng PDF/PNG ngay khi học viên hoàn tất khóa học, thông qua công cụ thiết kế mẫu cho phép định vị các trường thông tin (họ tên, tên khóa học, thời lượng, ngày hoàn thành) trên ảnh nền. Song song đó, một engine thông báo chạy nền qua Redis kết hợp Pusher cho phép đội vận hành gửi thông báo tức thời hoặc lên lịch theo ngày/tuần/tháng, nhắm tới toàn bộ học viên, một khóa học cụ thể hoặc danh sách tùy chỉnh — kèm cơ chế tự động nhắc học viên không hoạt động trong một khoảng thời gian cấu hình được.

Khác với các nền tảng LMS thương mại thông thường, mô hình dữ liệu học viên của hệ thống ghi nhận thêm dân tộc, tình trạng khuyết tật và trình độ học vấn, phục vụ trực tiếp việc đo lường mức độ tiếp cận nhóm yếu thế và xuất báo cáo Excel cho công tác báo cáo tác động xã hội với nhà tài trợ. Đội vận hành MSD theo dõi toàn bộ hoạt động qua một dashboard báo cáo (tổng học viên, học viên hoạt động/hoàn thành, khóa học và giáo viên nổi bật, biểu đồ truy cập theo ngày). Nền tảng còn có cơ chế tìm kiếm nội bộ tự đồng bộ, không phụ thuộc dịch vụ bên thứ ba, cho khóa học, bài học, tài nguyên và tin tức; học viên xác thực qua Google OAuth, còn đội quản trị dùng hệ thống phân quyền theo vai trò riêng biệt (Spatie Permission, guard admin tách biệt hoàn toàn khỏi tài khoản học viên).',
            'result' => 'Nền tảng vận hành phục vụ chương trình đào tạo cộng đồng của MSD, với quy trình cấp chứng chỉ, gửi thông báo và bảo vệ nội dung video được tự động hóa gần như hoàn toàn, giảm tải vận hành thủ công cho đội ngũ. Dữ liệu học viên thuộc nhóm yếu thế — dân tộc thiểu số, người khuyết tật — được thu thập có hệ thống ngay từ mô hình dữ liệu gốc, hỗ trợ trực tiếp công tác báo cáo tác động xã hội với nhà tài trợ và United Way Vietnam.',
            'meta_title' => 'MSD Learning Platform — Nền tảng học trực tuyến vì phát triển bền vững',
            'meta_description' => 'Case study MSD Learning Platform: nền tảng LMS đào tạo cộng đồng, bảo vệ nội dung video bản quyền và đo lường tác động xã hội tới nhóm học viên yếu thế.',
        ]);

        $msd->translations()->create([
            'locale' => 'en',
            'slug' => 'msd-learning-platform',
            'title' => 'MSD Learning Platform — An Online Learning Platform for Sustainable Development',
            'excerpt' => 'Building an LMS platform for community training that measures social impact among ethnic-minority and disabled learners while protecting copyrighted course content.',
            'problem' => 'MSD Vietnam (Center for Sustainability Development Management Studies, a United Way Vietnam member) needed an online learning platform to run community training programs on sustainable development, serving both general learners and disadvantaged groups — ethnic minorities and people with disabilities — who are priority groups in impact reporting to donors.

Three core problems had to be solved: protecting copyrighted video content from piracy or fast-forwarding to skip required viewing; measuring and reporting reach to disadvantaged learner groups for donor impact reporting; and automating study reminders and certificate issuance to sustain completion rates without manual per-learner intervention from the operations team.',
            'solution_text' => 'The system was built as three separate layers sharing one central API: msd-api (Laravel 11, PHP 8.2) handles all business logic and security, msd-front (Nuxt 3) is the public learner portal optimized for SEO/SSR, and msd-admin (Vue 3 + Vite + TypeScript + Pinia) is the internal admin area. The LMS core organizes content as Course → Lesson (video, text/image, exercise, flashcard) → a question bank tagged by difficulty with support for audio and essay questions and automatic grading; learner progress is tracked per lesson and per course.

The project\'s central technical piece is the video-protection pipeline: every uploaded lecture video runs through a background processing queue with clear stages — automatic Vietnamese transcription via OpenAI Whisper followed by bilingual translation to English, watermarking, adaptive-streaming (HLS) encoding via ffmpeg, and optional push to AWS S3 storage. A custom Video.js-based player disables seeking so learners must watch the full content before being marked complete — a certification-of-completion model rather than simple view counting.

Completion certificates are personalized and auto-generated as PDF/PNG the moment a learner finishes a course, via a template designer that positions information fields (full name, course title, duration, completion date) on a background image. Alongside this, a background notification engine running on Redis with Pusher lets the operations team send notifications instantly or on a daily/weekly/monthly schedule, targeting all learners, a specific course, or a custom list — with automatic reminders for learners who have gone inactive for a configurable period.

Unlike typical commercial LMS platforms, the learner data model also records ethnicity, disability status, and education level, directly supporting reach measurement for disadvantaged groups and Excel exports for social-impact reporting to donors. The MSD operations team monitors activity through a reporting dashboard (total learners, active/completed learners, top courses and teachers, daily traffic charts). The platform also has a self-synchronizing internal search mechanism, with no third-party dependency, covering courses, lessons, resources, and news; learners authenticate via Google OAuth, while admins use a separate role-based permission system (Spatie Permission, with an admin guard fully separate from learner accounts).',
            'result' => 'The platform runs MSD\'s community training programs, with certificate issuance, notifications, and video content protection almost entirely automated, reducing manual operational load on the team. Data on disadvantaged learners — ethnic minorities, people with disabilities — is systematically captured from the ground up in the data model, directly supporting social-impact reporting to donors and United Way Vietnam.',
            'meta_title' => 'MSD Learning Platform — An Online Learning Platform for Sustainable Development',
            'meta_description' => 'MSD Learning Platform case study: a community-training LMS protecting copyrighted video content and measuring social impact among disadvantaged learners.',
        ]);
    }
}
