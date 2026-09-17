<?php

namespace App\Console\Commands;

use App\Models\Solution;
use Illuminate\Console\Command;

// One-off command to push the IA-checklist content updates for the
// "learning-analytics" solution (architecture_approach, trust_safety,
// use_cases, at-risk-learner feature) into an already-seeded production
// database, since SolutionsSeeder only runs on an empty table.
class SyncLearningAnalyticsContent extends Command
{
    protected $signature = 'app:sync-learning-analytics-content';

    protected $description = 'Update the learning-analytics solution translations and features with the latest IA checklist content';

    public function handle(): int
    {
        $solution = Solution::whereHas('translations', fn ($q) => $q->where('slug', 'learning-analytics'))->first();

        if (! $solution) {
            $this->error('learning-analytics solution not found.');

            return self::FAILURE;
        }

        $content = [
            'vi' => [
                'architecture_note' => 'Đây là giải pháp trọn gói (dashboard + tư vấn triển khai theo nhu cầu của bạn), được xây trên cùng năng lực lõi với Learning Analytics Platform trong mục Sản phẩm & Công nghệ. Dữ liệu hành vi học tập ở đây cũng là nền tảng đầu vào cho AI Education (gợi ý cá nhân hóa) và Adaptive Learning (lộ trình theo năng lực) — nên triển khai Learning Analytics trước hai giải pháp đó để có dữ liệu sẵn sàng.',
                'architecture_approach' => [
                    ['title' => 'Thu thập hành vi học tập', 'description' => 'Ghi nhận sự kiện học tập (thời gian học, lượt xem bài giảng, số lần làm lại bài tập, tương tác trên khoá học) từ LMS/exam hiện có của bạn, không cần thay đổi hệ thống gốc.'],
                    ['title' => 'Mô hình rủi ro bỏ học', 'description' => 'Kết hợp các tín hiệu hành vi (tần suất học giảm dần, điểm số đi xuống, bỏ dở bài tập) thành một chỉ số rủi ro theo từng học viên, cập nhật liên tục thay vì báo cáo tổng kết cuối kỳ.'],
                    ['title' => 'Dashboard theo vai trò', 'description' => 'Giáo viên thấy được học viên cần can thiệp sớm; quản lý trung tâm/trường thấy được bức tranh retention và completion rate theo lớp, theo khoá học.'],
                ],
                'trust_safety' => [
                    ['question' => 'Chỉ số rủi ro bỏ học có chính xác 100% không?', 'answer' => 'Không — đây là chỉ báo sớm để giáo viên ưu tiên can thiệp, không phải một dự đoán tuyệt đối. Độ tin cậy tăng dần khi có nhiều dữ liệu hành vi hơn, và luôn cần giáo viên xác nhận trước khi hành động (ví dụ liên hệ phụ huynh).'],
                    ['question' => 'Dữ liệu hành vi học tập của học viên được dùng để làm gì?', 'answer' => 'Chỉ dùng để tính các chỉ số trong dashboard (tiến độ, rủi ro bỏ học, completion rate) phục vụ chính đơn vị vận hành khoá học — không chia sẻ cho bên thứ ba ngoài phạm vi thoả thuận triển khai.'],
                ],
                'use_cases' => [
                    ['audience' => 'Trung tâm đào tạo', 'description' => 'Phát hiện học viên có dấu hiệu bỏ học giữa khoá để giáo viên chủ động liên hệ trước khi họ thực sự nghỉ.'],
                    ['audience' => 'Trường học', 'description' => 'Dashboard cho ban giám hiệu theo dõi completion rate và retention theo từng lớp, từng môn.'],
                    ['audience' => 'EdTech Startup', 'description' => 'Có dữ liệu hành vi học tập làm nền trước khi đầu tư vào AI recommendation hay AI tutor.'],
                    ['audience' => 'Doanh nghiệp đào tạo nội bộ', 'description' => 'Theo dõi tiến độ hoàn thành chương trình đào tạo bắt buộc của nhân viên theo phòng ban.'],
                ],
                'features' => ['Student progress', 'Learning behavior', 'Cảnh báo học viên có nguy cơ', 'Retention', 'Completion rate', 'Teacher dashboard'],
            ],
            'en' => [
                'architecture_note' => 'This is a packaged solution (dashboards + implementation tailored to your needs), built on the same core capability as the Learning Analytics Platform under Products & Technology. The behavior data captured here is also the foundational input for AI Education (personalized recommendations) and Adaptive Learning (ability-based paths) — deploy Learning Analytics first so that data is ready.',
                'architecture_approach' => [
                    ['title' => 'Behavior data collection', 'description' => 'Captures learning events (time spent, lecture views, assignment retries, course interactions) from your existing LMS/exam system, with no changes needed to the source system.'],
                    ['title' => 'Dropout-risk model', 'description' => 'Combines behavior signals (declining study frequency, falling scores, abandoned assignments) into a per-learner risk score, updated continuously instead of only at end-of-term.'],
                    ['title' => 'Role-based dashboards', 'description' => 'Teachers see which learners need early intervention; center/school administrators see retention and completion rate across classes and courses.'],
                ],
                'trust_safety' => [
                    ['question' => 'Is the dropout-risk score 100% accurate?', 'answer' => 'No — it is an early signal to help teachers prioritize outreach, not an absolute prediction. Reliability improves as more behavior data accumulates, and a teacher always confirms before acting on it (e.g. contacting a parent).'],
                    ['question' => 'What is learner behavior data used for?', 'answer' => 'Only to compute the dashboard metrics (progress, dropout risk, completion rate) for the organization running the course — it is not shared with third parties outside the scope of the implementation agreement.'],
                ],
                'use_cases' => [
                    ['audience' => 'Training centers', 'description' => 'Spot learners showing dropout signals mid-course so teachers can reach out before they actually leave.'],
                    ['audience' => 'Schools', 'description' => 'A dashboard for school leadership to track completion rate and retention by class and subject.'],
                    ['audience' => 'EdTech startups', 'description' => 'Build a behavior-data foundation before investing in AI recommendations or an AI tutor.'],
                    ['audience' => 'Corporate training', 'description' => 'Track completion of mandatory employee training programs by department.'],
                ],
                'features' => ['Student progress', 'Learning behavior', 'At-risk learner alerts', 'Retention', 'Completion rate', 'Teacher dashboard'],
            ],
        ];

        foreach ($content as $locale => $data) {
            $translation = $solution->translations()->where('locale', $locale)->first();

            if (! $translation) {
                $this->warn("No {$locale} translation found for learning-analytics, skipping.");

                continue;
            }

            $translation->update([
                'architecture_note' => $data['architecture_note'],
                'architecture_approach' => $data['architecture_approach'],
                'trust_safety' => $data['trust_safety'],
                'use_cases' => $data['use_cases'],
            ]);
        }

        $solution->features()->delete();

        foreach ($content['vi']['features'] as $i => $viTitle) {
            $enTitle = $content['en']['features'][$i] ?? $viTitle;

            $feature = $solution->features()->create(['sort_order' => $i]);
            $feature->translations()->create(['locale' => 'vi', 'title' => $viTitle, 'description' => null, 'highlights' => []]);
            $feature->translations()->create(['locale' => 'en', 'title' => $enTitle, 'description' => null, 'highlights' => []]);
        }

        $this->info('learning-analytics content synced successfully.');

        return self::SUCCESS;
    }
}
