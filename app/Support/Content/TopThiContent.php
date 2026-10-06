<?php

namespace App\Support\Content;

// Editorial content for the TopThi case study (/our-work/topthi), per
// docs/edulab_topthi_page_update.md (2026-10-06). Shared by ProjectsSeeder (fresh
// installs) and the app:sync-topthi-content command (already-seeded databases),
// so the two never drift apart.
//
// - vi follows the doc verbatim.
// - en is an interim translation of vi, so the English page stops showing the
//   removed claims (40% / 10,000+ / 99.9%, anti-cheat, essay grading) until the
//   official en copy arrives — replace it when that lands.
// - hero_stats / results stay empty on purpose: no figure is published until the
//   project owner confirms it (doc mục 4). Do not fill them with estimates.
// - Images (featured/og/solution modules/gallery) are still pending (doc mục 5).
class TopThiContent
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function translations(): array
    {
        return [
            'vi' => [
                'title' => 'TopThi — Vòng học tập thích ứng cho luyện thi THPT và ĐGNL',
                'excerpt' => 'TopThi biến mỗi lần làm bài thành dữ liệu chẩn đoán, rồi dựa trên khoa học học tập để quyết định học sinh nên luyện gì tiếp theo. Đây là living lab của XO Edu Lab, đang vận hành thật tại topthi.vn.',

                'hero_eyebrow' => 'EdTech · Adaptive Learning · Living Lab',
                'hero_cta_label' => 'Xem TopThi đang chạy',
                'hero_cta_url' => 'https://topthi.vn',
                'hero_badges' => [
                    ['icon' => 'psychology', 'label' => 'Adaptive Learning'],
                    ['icon' => 'monitoring', 'label' => 'Learning Analytics'],
                    ['icon' => 'quiz', 'label' => 'Exam & Question Bank Engine'],
                    ['icon' => 'science', 'label' => 'Thiết kế dựa trên khoa học học tập'],
                ],
                'hero_stats' => [],

                'snapshot_items' => [
                    ['icon' => 'school', 'label' => 'Lĩnh vực', 'value' => 'EdTech · Luyện thi THPT và Đánh giá năng lực'],
                    ['icon' => 'layers', 'label' => 'Loại', 'value' => 'Nền tảng web (API + Admin + Front cho học sinh)'],
                    ['icon' => 'engineering', 'label' => 'Vai trò XO', 'value' => 'Toàn bộ vòng đời: kiến trúc, backend, admin, frontend, mô hình học tập'],
                    ['icon' => 'code', 'label' => 'Công nghệ', 'value' => 'Laravel, Nuxt 3, Vue 3, MySQL, Redis, Elasticsearch'],
                    ['icon' => 'check_circle', 'label' => 'Trạng thái', 'value' => 'Đang vận hành · phiên bản 4 (từ 09/2026)'],
                ],

                'challenges_heading' => 'Bài toán',
                'challenges_description' => 'Luyện đề nhiều chưa chắc đã tiến bộ. Muốn cá nhân hóa thật, hệ thống phải biết học sinh yếu ở kỹ năng nào, cho luyện đúng chỗ, và chứng minh được điểm tăng là thật.',
                'challenges' => [
                    ['icon' => 'query_stats', 'color' => 'primary', 'title' => 'Điểm tổng không nói học sinh yếu ở đâu', 'description' => 'Một con số 6,5 điểm không cho học sinh biết phải luyện gì tiếp theo. Cần đo năng lực theo từng kỹ năng.', 'wide' => false],
                    ['icon' => 'repeat', 'color' => 'secondary', 'title' => 'Luyện ngẫu nhiên tạo cảm giác tiến bộ', 'description' => 'Làm lại đề quen cho điểm luyện tăng đều nhưng dễ thành học tủ. Tiến bộ phải được đo trên câu hỏi mới.', 'wide' => false],
                    ['icon' => 'fact_check', 'color' => 'gold', 'title' => 'Kho câu hỏi lớn nhưng chưa có cấu trúc', 'description' => 'Hàng trăm nghìn câu chưa gắn kỹ năng, nhãn độ khó lệch, có câu sai đáp án. Không có dữ liệu sạch thì không cá nhân hóa được.', 'wide' => false],
                    ['icon' => 'speed', 'color' => 'primary', 'title' => 'Phân tích không được làm chậm việc nộp bài', 'description' => 'Mọi tính toán năng lực chạy nền qua hàng đợi. Học sinh nhận kết quả ngay, hồ sơ năng lực cập nhật ngay sau đó.', 'wide' => true],
                ],

                'journey_heading' => 'Vòng học tập TopThi',
                'journey_steps' => [
                    ['title' => '1. Làm bài', 'description' => 'Học sinh làm đề thi thật hoặc đề luyện 15 câu mỗi ngày. Mỗi câu trả lời được ghi thành một sự kiện học tập.'],
                    ['title' => '2. Chẩn đoán', 'description' => 'Hệ thống ước lượng năng lực theo từng kỹ năng bằng mô hình Elo/IRT, có hiệu chỉnh đoán mò. Độ khó câu hỏi được hiệu chỉnh hằng đêm từ dữ liệu làm bài.'],
                    ['title' => '3. Luyện đúng chỗ', 'description' => 'Đề luyện ưu tiên kỹ năng có trọng số cao trong đề thi mà học sinh còn yếu. Mỗi đề trộn 3–4 chủ đề, độ khó chọn để học sinh làm đúng khoảng 70%.'],
                    ['title' => '4. Ôn đúng lúc, sửa đúng lỗi', 'description' => 'Lịch ôn giãn cách theo từng kỹ năng. Khi chọn sai, học sinh thấy ngay lỗi tư duy của phương án mình vừa chọn.'],
                    ['title' => '5. Kiểm chứng tiến bộ thật', 'description' => 'Mỗi 7 ngày có một bài đánh giá bằng câu học sinh chưa từng gặp. Kết quả quay lại bước Chẩn đoán, và đề ngày mai được tính lại từ đó.'],
                ],
                'journey_note' => 'Đây là một vòng lặp: kết quả bước 5 quay về bước 2 (Chẩn đoán), và đề luyện ngày mai được tính lại từ đó.',

                'science_heading' => 'Khoa học phía sau',
                'science_description' => 'Mỗi quyết định thiết kế của TopThi dựa trên một cơ chế học tập đã được nghiên cứu kiểm chứng. Nguồn chính: Make It Stick (Brown, Roediger, McDaniel, 2014) và How People Learn I & II (National Academies, 2000, 2018).',
                'science_cards' => [
                    ['icon' => 'quiz', 'title' => 'Hiệu ứng kiểm tra', 'source' => 'Roediger & Karpicke, 2006', 'idea' => 'Tự nhớ lại giúp nhớ lâu hơn đọc lại. Trả lời sai rồi được phản hồi cũng là một lần học.', 'in_practice' => 'Học sinh luyện bằng đề ngắn mỗi ngày, không đọc lại tài liệu. Mỗi câu sai trở thành dữ liệu chẩn đoán.'],
                    ['icon' => 'event_repeat', 'title' => 'Luyện giãn cách', 'source' => 'Cepeda et al., 2006', 'idea' => 'Ôn cách quãng, vào lúc sắp quên, giúp nhớ bền hơn học dồn.', 'in_practice' => 'Lịch ôn SM-2 riêng cho từng kỹ năng của từng học sinh.'],
                    ['icon' => 'shuffle', 'title' => 'Luyện xen kẽ', 'source' => 'Rohrer & Taylor, 2007', 'idea' => 'Trộn nhiều dạng bài buộc người học nhận diện cần dùng cách giải nào, giống đề thi thật.', 'in_practice' => 'Mỗi đề luyện trộn 3–4 chủ đề, mỗi chủ đề ít nhất 2 câu.'],
                    ['icon' => 'trending_up', 'title' => 'Khó khăn mong muốn & vùng phát triển gần', 'source' => 'Bjork; Vygotsky', 'idea' => 'Bài phải đủ khó để người học cố gắng, nhưng vẫn vượt qua được.', 'in_practice' => 'Độ khó câu chọn để học sinh làm đúng khoảng 70%. Khi kiến thức nền còn yếu, hệ thống chuyển sang luyện kỹ năng tiên quyết.'],
                    ['icon' => 'feedback', 'title' => 'Đánh giá hình thành & phản hồi', 'source' => 'Black & Wiliam, 1998; Hattie & Timperley, 2007', 'idea' => 'Phản hồi có tác dụng khi cụ thể, đến đúng lúc và chỉ ra bước tiếp theo.', 'in_practice' => 'Học sinh nhận báo cáo theo kỹ năng, thấy lỗi tư duy dưới phương án chọn sai, và biết 3 chủ đề đáng đầu tư nhất.'],
                    ['icon' => 'analytics', 'title' => 'Đo năng lực & kiểm chứng', 'source' => 'IRT/Rasch; Bloom; Slavin', 'idea' => 'Năng lực và độ khó câu hỏi đặt trên cùng một thang đo. Tiến bộ phải được đo trên câu hỏi mới.', 'in_practice' => 'Hệ thống dùng mô hình Elo/IRT có hiệu chỉnh đoán mò, và đánh giá định kỳ bằng câu học sinh chưa từng gặp.'],
                ],
                'science_note' => 'TopThi tối ưu cho tiến bộ thật, không tối ưu cho cảm giác tiến bộ. Luyện tập hiệu quả thường thấy khó hơn và chậm hơn.',

                'feature_map_heading' => 'Bản đồ chức năng',
                'feature_groups' => [
                    ['title' => 'Question Bank Engine', 'badge_label' => 'Đang vận hành', 'features' => ['Bản đồ kỹ năng theo Môn · Lớp, bám chương trình GDPT 2018 và các kỳ thi ĐGNL', 'Gắn nhãn kỹ năng bằng AI, có người duyệt', 'Hiệu chỉnh độ khó từ dữ liệu làm bài thật', 'Tự phát hiện câu nghi sai đáp án, có kênh học sinh báo lỗi và AI kiểm tra lại', 'Câu nghi lỗi tự động bị loại khỏi đề tự sinh']],
                    ['title' => 'Exam Engine', 'badge_label' => 'Đang vận hành', 'features' => ['Đề cố định và đề sinh tự động theo từng học sinh', 'Đủ dạng câu GDPT 2018: trắc nghiệm, đúng/sai nhiều ý, trả lời ngắn, đọc hiểu theo chùm', 'Chấm tự động ngay khi nộp', 'Thời gian làm bài xác thực phía server, lưu snapshot đáp án']],
                    ['title' => 'Adaptive Learning Engine', 'badge_label' => 'Đang vận hành', 'features' => ['Mô hình năng lực Elo/IRT theo từng học sinh và từng kỹ năng', 'Test đầu vào phân bổ câu theo trọng số đề thi', 'Đề luyện hằng ngày xen kẽ chủ đề, độ khó vừa sức', 'Đồ thị kỹ năng tiên quyết: chuyển sang luyện kiến thức nền khi nền còn yếu', 'Lịch ôn giãn cách SM-2 tự chốt từ kết quả làm bài']],
                    ['title' => 'Learning Analytics', 'badge_label' => 'Đang vận hành', 'features' => ['Điểm dự kiến có khoảng tin cậy và 3 chủ đề đáng đầu tư nhất', 'Hồ sơ năng lực theo kỹ năng cho học sinh và cho admin', 'Báo cáo hiệu quả học tập: đánh giá định kỳ trước/sau, độ lệch của điểm dự kiến', 'Retention D1/D7/D30, nguồn vào đề thi']],
                ],

                'architecture_heading' => 'Kiến trúc hệ thống',
                'architecture_layers' => [
                    ['icon' => 'dns', 'title' => 'Backend API', 'subtitle' => 'Laravel, REST API cho Admin và Front'],
                    ['icon' => 'web', 'title' => 'Front học sinh', 'subtitle' => 'Nuxt 3 SSR'],
                    ['icon' => 'admin_panel_settings', 'title' => 'Admin', 'subtitle' => 'Vue 3 + TypeScript: quản lý nội dung, gắn nhãn, hàng đợi chất lượng câu hỏi'],
                    ['icon' => 'database', 'title' => 'Dữ liệu', 'subtitle' => 'MySQL, Redis, Elasticsearch có bộ phân tích tiếng Việt'],
                    ['icon' => 'psychology', 'title' => 'Learning Engine', 'subtitle' => 'Hàng đợi chạy nền: nhật ký sự kiện học tập chỉ ghi thêm, mô hình năng lực, hiệu chỉnh hằng đêm'],
                    ['icon' => 'smart_toy', 'title' => 'AI', 'subtitle' => 'OpenAI API dùng để gắn nhãn kỹ năng, kiểm tra đáp án và viết giải thích'],
                ],

                'tech_stack_groups' => [
                    ['title' => 'Backend', 'items' => ['Laravel', 'PHP 8', 'Laravel Queue', 'Sanctum']],
                    ['title' => 'Frontend', 'items' => ['Nuxt 3 (SSR)', 'Vue 3', 'Pinia', 'TailwindCSS']],
                    ['title' => 'Admin', 'items' => ['Vue 3', 'TypeScript', 'TailwindCSS']],
                    ['title' => 'Dữ liệu & hạ tầng', 'items' => ['MySQL', 'Redis', 'Elasticsearch', 'Supervisor', 'PM2']],
                    ['title' => 'Tích hợp', 'items' => ['OpenAI API', 'PayOS', 'Google Login']],
                ],

                'results_heading' => 'Đã đo được và đang kiểm chứng',
                'results' => [],

                'lessons_quote' => 'Mô hình đầu tiên của chúng tôi cộng điểm thành thạo theo số câu đã làm, nên học sinh đoán bừa vẫn được xếp loại "khá". Khi chuyển sang mô hình Elo/IRT, nhiều chủ đề trước đó bị đánh giá quá cao. Bài học: không có bản đồ kỹ năng và dữ liệu sạch thì AI không cá nhân hóa được gì, và phần lớn công sức nằm ở đó.',
                'lessons_citation' => '— Đội ngũ XO Edu Lab',

                'meta_title' => 'TopThi — Case study vòng học tập thích ứng dựa trên khoa học học tập | XO Edu Lab',
                'meta_description' => 'TopThi đo năng lực học sinh theo từng kỹ năng, sinh đề luyện cá nhân mỗi ngày, ôn giãn cách và kiểm chứng tiến bộ bằng đánh giá độc lập. Đây là living lab của XO Edu Lab.',
            ],

            'en' => [
                'title' => 'TopThi — An Adaptive Learning Loop for National High-School and Aptitude Exam Prep',
                'excerpt' => 'TopThi turns every attempt into diagnostic data, then uses learning science to decide what each student should practice next. It is XO Edu Lab\'s living lab, running in production at topthi.vn.',

                'hero_eyebrow' => 'EdTech · Adaptive Learning · Living Lab',
                'hero_cta_label' => 'See TopThi live',
                'hero_cta_url' => 'https://topthi.vn',
                'hero_badges' => [
                    ['icon' => 'psychology', 'label' => 'Adaptive Learning'],
                    ['icon' => 'monitoring', 'label' => 'Learning Analytics'],
                    ['icon' => 'quiz', 'label' => 'Exam & Question Bank Engine'],
                    ['icon' => 'science', 'label' => 'Grounded in learning science'],
                ],
                'hero_stats' => [],

                'snapshot_items' => [
                    ['icon' => 'school', 'label' => 'Domain', 'value' => 'EdTech · High-school graduation & aptitude exam prep'],
                    ['icon' => 'layers', 'label' => 'Type', 'value' => 'Web platform (API + Admin + Student Front)'],
                    ['icon' => 'engineering', 'label' => 'XO\'s role', 'value' => 'Full lifecycle: architecture, backend, admin, frontend, learning model'],
                    ['icon' => 'code', 'label' => 'Tech', 'value' => 'Laravel, Nuxt 3, Vue 3, MySQL, Redis, Elasticsearch'],
                    ['icon' => 'check_circle', 'label' => 'Status', 'value' => 'Live · version 4 (since 09/2026)'],
                ],

                'challenges_heading' => 'The Problem',
                'challenges_description' => 'Doing more practice tests does not guarantee progress. Real personalization means knowing which skill a student is weak in, targeting practice there, and proving that score gains are real.',
                'challenges' => [
                    ['icon' => 'query_stats', 'color' => 'primary', 'title' => 'A total score doesn\'t show where a student is weak', 'description' => 'A 6.5 doesn\'t tell a student what to practice next. Ability has to be measured per skill.', 'wide' => false],
                    ['icon' => 'repeat', 'color' => 'secondary', 'title' => 'Random practice creates an illusion of progress', 'description' => 'Redoing familiar tests pushes practice scores up but drifts into memorizing answers. Progress has to be measured on new questions.', 'wide' => false],
                    ['icon' => 'fact_check', 'color' => 'gold', 'title' => 'A large question bank with no structure', 'description' => 'Hundreds of thousands of questions with no skill tags, skewed difficulty labels and some wrong answer keys. Without clean data there is no personalization.', 'wide' => false],
                    ['icon' => 'speed', 'color' => 'primary', 'title' => 'Analysis must never slow down submission', 'description' => 'All ability computation runs in background queues. Students get results instantly; their skill profile updates right after.', 'wide' => true],
                ],

                'journey_heading' => 'The TopThi Learning Loop',
                'journey_steps' => [
                    ['title' => '1. Practice', 'description' => 'Students take real exams or a 15-question daily practice set. Every answer is logged as a learning event.'],
                    ['title' => '2. Diagnose', 'description' => 'The system estimates ability per skill with an Elo/IRT model that corrects for guessing. Question difficulty is recalibrated nightly from attempt data.'],
                    ['title' => '3. Target practice', 'description' => 'Practice prioritizes high-weight exam skills the student is still weak in. Each set mixes 3–4 topics, with difficulty chosen for roughly 70% correct.'],
                    ['title' => '4. Review on time, fix the misconception', 'description' => 'Spaced review is scheduled per skill. On a wrong answer, the student immediately sees the misconception behind the option they picked.'],
                    ['title' => '5. Verify real progress', 'description' => 'Every 7 days an assessment uses questions the student has never seen. Results feed back into Diagnose, and tomorrow\'s set is recomputed from there.'],
                ],
                'journey_note' => 'This is a loop: step 5 feeds back into step 2 (Diagnose), and tomorrow\'s practice set is recomputed from there.',

                'science_heading' => 'The Science Behind It',
                'science_description' => 'Every TopThi design decision rests on a learning mechanism backed by research. Main sources: Make It Stick (Brown, Roediger, McDaniel, 2014) and How People Learn I & II (National Academies, 2000, 2018).',
                'science_cards' => [
                    ['icon' => 'quiz', 'title' => 'Testing effect', 'source' => 'Roediger & Karpicke, 2006', 'idea' => 'Retrieving from memory makes learning stick better than rereading. Getting it wrong and then receiving feedback is also learning.', 'in_practice' => 'Students practice with short daily sets instead of rereading material. Every wrong answer becomes diagnostic data.'],
                    ['icon' => 'event_repeat', 'title' => 'Spaced practice', 'source' => 'Cepeda et al., 2006', 'idea' => 'Reviewing at intervals, just before forgetting, produces more durable memory than cramming.', 'in_practice' => 'An SM-2 review schedule for each skill of each student.'],
                    ['icon' => 'shuffle', 'title' => 'Interleaving', 'source' => 'Rohrer & Taylor, 2007', 'idea' => 'Mixing problem types forces learners to identify which method applies, just like a real exam.', 'in_practice' => 'Each practice set mixes 3–4 topics, with at least 2 questions per topic.'],
                    ['icon' => 'trending_up', 'title' => 'Desirable difficulty & zone of proximal development', 'source' => 'Bjork; Vygotsky', 'idea' => 'Work should be hard enough to demand effort, yet still achievable.', 'in_practice' => 'Question difficulty targets roughly 70% correct. When foundations are weak, the system switches to prerequisite skills.'],
                    ['icon' => 'feedback', 'title' => 'Formative assessment & feedback', 'source' => 'Black & Wiliam, 1998; Hattie & Timperley, 2007', 'idea' => 'Feedback works when it is specific, timely and points to the next step.', 'in_practice' => 'Students get a per-skill report, see the misconception under a wrong option, and know the 3 topics most worth their time.'],
                    ['icon' => 'analytics', 'title' => 'Measurement & verification', 'source' => 'IRT/Rasch; Bloom; Slavin', 'idea' => 'Ability and question difficulty sit on the same scale. Progress must be measured on new questions.', 'in_practice' => 'An Elo/IRT model with guessing correction, plus periodic assessments using questions the student has never seen.'],
                ],
                'science_note' => 'TopThi optimizes for real progress, not the feeling of progress. Effective practice often feels harder and slower.',

                'feature_map_heading' => 'Feature Map',
                'feature_groups' => [
                    ['title' => 'Question Bank Engine', 'badge_label' => 'Live', 'features' => ['Skill map by Subject · Grade, aligned with the 2018 national curriculum and aptitude exams', 'AI skill tagging with human review', 'Difficulty calibrated from real attempt data', 'Automatic detection of suspected wrong answer keys, with student error reports and AI re-checks', 'Suspect questions are automatically excluded from generated sets']],
                    ['title' => 'Exam Engine', 'badge_label' => 'Live', 'features' => ['Fixed exams and per-student generated sets', 'All 2018-curriculum question formats: multiple choice, multi-part true/false, short answer, grouped reading', 'Instant automatic grading on submit', 'Server-verified exam timing, with an answer snapshot']],
                    ['title' => 'Adaptive Learning Engine', 'badge_label' => 'Live', 'features' => ['Elo/IRT ability model per student and per skill', 'Placement test weighted by exam blueprint', 'Daily interleaved practice at the right difficulty', 'Prerequisite skill graph: switches to foundations when they are weak', 'SM-2 spaced review scheduled from attempt results']],
                    ['title' => 'Learning Analytics', 'badge_label' => 'Live', 'features' => ['Predicted score with a confidence interval and the 3 topics most worth investing in', 'Per-skill ability profile for students and admins', 'Learning-outcomes report: pre/post periodic assessments, predicted-score error', 'D1/D7/D30 retention, exam traffic sources']],
                ],

                'architecture_heading' => 'System Architecture',
                'architecture_layers' => [
                    ['icon' => 'dns', 'title' => 'Backend API', 'subtitle' => 'Laravel, REST API for Admin and Front'],
                    ['icon' => 'web', 'title' => 'Student Front', 'subtitle' => 'Nuxt 3 SSR'],
                    ['icon' => 'admin_panel_settings', 'title' => 'Admin', 'subtitle' => 'Vue 3 + TypeScript: content management, tagging, question-quality queue'],
                    ['icon' => 'database', 'title' => 'Data', 'subtitle' => 'MySQL, Redis, Elasticsearch with a Vietnamese analyzer'],
                    ['icon' => 'psychology', 'title' => 'Learning Engine', 'subtitle' => 'Background queues: append-only learning event log, ability model, nightly calibration'],
                    ['icon' => 'smart_toy', 'title' => 'AI', 'subtitle' => 'OpenAI API for skill tagging, answer-key checks and explanations'],
                ],

                'tech_stack_groups' => [
                    ['title' => 'Backend', 'items' => ['Laravel', 'PHP 8', 'Laravel Queue', 'Sanctum']],
                    ['title' => 'Frontend', 'items' => ['Nuxt 3 (SSR)', 'Vue 3', 'Pinia', 'TailwindCSS']],
                    ['title' => 'Admin', 'items' => ['Vue 3', 'TypeScript', 'TailwindCSS']],
                    ['title' => 'Data & infrastructure', 'items' => ['MySQL', 'Redis', 'Elasticsearch', 'Supervisor', 'PM2']],
                    ['title' => 'Integrations', 'items' => ['OpenAI API', 'PayOS', 'Google Login']],
                ],

                'results_heading' => 'Measured, and still being verified',
                'results' => [],

                'lessons_quote' => 'Our first model added mastery points for every question attempted, so a student guessing at random could still be rated "good". When we switched to an Elo/IRT model, many topics turned out to have been overrated. The lesson: without a skill map and clean data, AI cannot personalize anything — and that is where most of the effort goes.',
                'lessons_citation' => '— The XO Edu Lab Team',

                'meta_title' => 'TopThi — Case study: an adaptive learning loop grounded in learning science | XO Edu Lab',
                'meta_description' => 'TopThi measures student ability per skill, generates a personal practice set every day, schedules spaced review and verifies progress with independent assessments. XO Edu Lab\'s living lab.',
            ],
        ];
    }

    /**
     * Same shape as ProjectsSeeder::seedSolutionModules(). Images pending (doc mục 5, ảnh #3–#5).
     *
     * @return array<int, array{image: string|null, vi: array<string, mixed>, en: array<string, mixed>}>
     */
    public static function solutionModules(): array
    {
        return [
            [
                'image' => null,
                'vi' => [
                    'title' => 'Đề luyện cá nhân mỗi ngày',
                    'description' => 'Mỗi lần học sinh mở trang Luyện tập, hệ thống tính lại đề hôm nay từ toàn bộ lịch sử làm bài. Không có lịch 30 ngày soạn sẵn.',
                    'technical_note' => 'Ưu tiên = trọng số kỹ năng trong đề thi × (1 − mức thành thạo). Độ khó câu đặt cho xác suất đúng khoảng 70%. Không lặp lại câu đã làm trong 14 ngày.',
                    'features' => ['15 câu, 3–4 chủ đề xen kẽ', 'Điểm dự kiến theo môn', 'Ưu tiên kỹ năng đến hạn ôn'],
                ],
                'en' => [
                    'title' => 'A personal practice set every day',
                    'description' => 'Each time a student opens the Practice page, today\'s set is recomputed from their full attempt history. There is no pre-written 30-day plan.',
                    'technical_note' => 'Priority = skill weight in the exam × (1 − mastery). Question difficulty targets roughly a 70% chance of answering correctly. No question repeats within 14 days.',
                    'features' => ['15 questions, 3–4 interleaved topics', 'Predicted score per subject', 'Prioritizes skills due for review'],
                ],
            ],
            [
                'image' => null,
                'vi' => [
                    'title' => 'Xem lại bài: biết vì sao sai',
                    'description' => 'Mỗi phương án sai gắn với một lỗi tư duy điển hình. Học sinh chọn sai sẽ thấy giải thích ngay dưới phương án mình vừa chọn.',
                    'technical_note' => 'Giải thích do AI viết chỉ được đăng khi AI tự giải ra đúng đáp án trong cơ sở dữ liệu. Admin duyệt và gỡ được. Học sinh đánh giá 👍/👎 cho từng giải thích.',
                    'features' => ['Ghi chú lỗi tư duy theo phương án', 'Báo lỗi câu hỏi', 'Giải thích có kiểm chứng'],
                ],
                'en' => [
                    'title' => 'Review: know why you got it wrong',
                    'description' => 'Every wrong option is linked to a typical misconception. A student who picks it sees the explanation right under the option they chose.',
                    'technical_note' => 'An AI-written explanation is only published when the AI independently arrives at the answer key stored in the database. Admins can review and remove it. Students rate each explanation 👍/👎.',
                    'features' => ['Per-option misconception notes', 'Report a question error', 'Verified explanations'],
                ],
            ],
            [
                'image' => null,
                'vi' => [
                    'title' => 'Đo tiến bộ trên câu hỏi mới',
                    'description' => 'Bài đánh giá định kỳ lấy từ một ngân hàng câu riêng và loại mọi câu học sinh đã gặp, nên điểm tăng không phải do quen đề.',
                    'technical_note' => 'Ngân hàng câu luyện tập và ngân hàng câu đánh giá được tách riêng. Hệ thống có sẵn báo cáo liều–đáp ứng (số đề luyện đã làm so với mức tăng điểm đánh giá) và chế độ A/B tùy chọn.',
                    'features' => ['Đánh giá 7 ngày/lần', 'Năng lực theo từng kỹ năng', 'Báo cáo hiệu quả cho admin'],
                ],
                'en' => [
                    'title' => 'Measuring progress on new questions',
                    'description' => 'Periodic assessments draw from a separate question bank and exclude every question the student has seen, so score gains are not just familiarity.',
                    'technical_note' => 'The practice bank and the assessment bank are kept separate. The system includes a dose–response report (practice sets completed vs. assessment score gain) and an optional A/B mode.',
                    'features' => ['Assessment every 7 days', 'Ability per skill', 'Outcomes report for admins'],
                ],
            ],
        ];
    }

    /**
     * Copy for the "topthi" row in Products (menu "Sản phẩm & Công nghệ").
     *
     * @return array<string, array{role_summary: string, description: string}>
     */
    public static function productCopy(): array
    {
        return [
            'vi' => [
                'role_summary' => 'Living lab của XO Edu Lab: vòng học tập thích ứng đang vận hành thật tại topthi.vn',
                'description' => 'TopThi là nền tảng luyện thi THPT và ĐGNL đang vận hành, nơi Question Bank Engine, Exam Engine, Adaptive Learning Engine (mô hình năng lực Elo/IRT, đồ thị kỹ năng tiên quyết) và Learning Analytics chạy trên dữ liệu học sinh thật trước khi đóng gói thành sản phẩm.',
            ],
            'en' => [
                'role_summary' => 'XO Edu Lab\'s living lab: an adaptive learning loop running in production at topthi.vn',
                'description' => 'TopThi is a live exam-prep platform where the Question Bank Engine, Exam Engine, Adaptive Learning Engine (Elo/IRT ability model, prerequisite skill graph) and Learning Analytics run on real student data before being packaged into products.',
            ],
        ];
    }

    /**
     * Products that already run in production at TopThi (doc mục 1: move out of
     * "Đang phát triển"). Stage values are displayed as-is on /products pages.
     *
     * @return array<string, string>
     */
    public static function pilotProductStages(): array
    {
        return [
            'ai-learning-engine' => 'pilot-at-topthi',
            'knowledge-graph-engine' => 'pilot-at-topthi',
        ];
    }
}
