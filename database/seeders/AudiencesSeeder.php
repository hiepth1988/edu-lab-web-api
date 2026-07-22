<?php

namespace Database\Seeders;

use App\Models\Audience;
use App\Models\Solution;
use Illuminate\Database\Seeder;

class AudiencesSeeder extends Seeder
{
    public function run(): void
    {
        if (Audience::exists()) {
            return;
        }

        $audiences = [
            [
                'slug' => 'independent-educators',
                'solutions' => ['lms', 'ai-education', 'adaptive-learning'],
                'vi' => [
                    'title' => 'Chuyên gia & Giáo viên độc lập',
                    'subheading' => 'Biến kiến thức chuyên môn thành một sản phẩm học tập có thể mở rộng quy mô.',
                    'pain_points' => 'Bạn có chuyên môn và học viên tin tưởng, nhưng nội dung đang nằm rải rác trong file PDF, nhóm Zalo hoặc các buổi dạy 1-1 không thể nhân bản. Việc tự xây nền tảng công nghệ vượt quá năng lực và thời gian của một cá nhân.',
                    'how_we_help' => 'XO giúp bạn cấu trúc lại chương trình học theo learning path rõ ràng, xây nền tảng LMS mang thương hiệu riêng, và cân nhắc đưa AI vào để cá nhân hóa trải nghiệm học — để bạn tập trung vào chuyên môn, phần công nghệ để XO lo.',
                ],
                'en' => [
                    'title' => 'Independent Educators',
                    'subheading' => 'Turn your expertise into a scalable, productized learning asset.',
                    'pain_points' => 'You have the expertise and the trust of your learners, but your content is scattered across PDFs, chat groups, and 1:1 sessions that don\'t scale. Building the technology yourself is beyond what one person can take on.',
                    'how_we_help' => 'XO helps you restructure your curriculum into a clear learning path, builds a branded LMS around it, and can bring in AI to personalize the experience — so you focus on your craft while XO handles the technology.',
                ],
            ],
            [
                'slug' => 'training-centers',
                'solutions' => ['school-management', 'online-exam-platform', 'lms'],
                'vi' => [
                    'title' => 'Trung tâm đào tạo',
                    'subheading' => 'Chuyển đổi mô hình vận hành truyền thống sang hybrid hoặc digital-first.',
                    'pain_points' => 'Quản lý học viên, giáo viên, lịch học và học phí vẫn dựa vào Excel hoặc nhiều công cụ rời rạc, gây sai sót và khó mở rộng khi trung tâm phát triển thêm chi nhánh hoặc lớp online.',
                    'how_we_help' => 'XO xây dựng hệ thống quản lý vận hành (học viên, lớp, lịch, điểm danh, học phí) hợp nhất với nền tảng thi trực tuyến và LMS, giúp trung tâm số hóa toàn bộ quy trình mà không phải đánh đổi trải nghiệm học viên.',
                ],
                'en' => [
                    'title' => 'Training Centers',
                    'subheading' => 'Move from spreadsheet operations to a hybrid or digital-first model.',
                    'pain_points' => 'Managing learners, teachers, schedules, and tuition still runs on spreadsheets or disconnected tools — error-prone and hard to scale as the center opens new branches or online classes.',
                    'how_we_help' => 'XO builds a unified operations system (students, classes, schedules, attendance, tuition) alongside an online exam platform and LMS, so the center can digitize its entire workflow without sacrificing the learner experience.',
                ],
            ],
            [
                'slug' => 'schools',
                'solutions' => ['school-management', 'online-exam-platform', 'learning-analytics'],
                'vi' => [
                    'title' => 'Trường học',
                    'subheading' => 'Hiện đại hóa hạ tầng công nghệ học tập cho toàn trường.',
                    'pain_points' => 'Ban giám hiệu cần một bức tranh tổng thể về tiến độ học tập của học sinh, nhưng dữ liệu đang nằm rải rác giữa sổ điểm giấy, phần mềm thi cũ và nhiều hệ thống không nói chuyện được với nhau.',
                    'how_we_help' => 'XO triển khai nền tảng quản lý trường học, thi trực tuyến và dashboard phân tích học tập dùng chung một dữ liệu gốc — giúp nhà trường ra quyết định dựa trên dữ liệu thay vì cảm tính.',
                ],
                'en' => [
                    'title' => 'Schools',
                    'subheading' => 'Modernize the learning technology infrastructure across the whole school.',
                    'pain_points' => 'Leadership needs a clear picture of student progress, but the data is scattered across paper gradebooks, legacy exam software, and systems that don\'t talk to each other.',
                    'how_we_help' => 'XO deploys a school management platform, online exams, and a learning analytics dashboard on one shared data model — helping schools make decisions based on data instead of guesswork.',
                ],
            ],
            [
                'slug' => 'edtech-startups',
                'solutions' => ['edtech-consulting', 'lms', 'ai-education'],
                'vi' => [
                    'title' => 'EdTech Startup',
                    'subheading' => 'Tìm một đội ngũ sản phẩm bên ngoài để xây dựng và ra mắt nhanh.',
                    'pain_points' => 'Founder có tầm nhìn sản phẩm rõ ràng nhưng thiếu đội ngũ kỹ thuật đủ kinh nghiệm về giáo dục để biến ý tưởng thành MVP đúng hạn, đúng ngân sách, và không chọn sai kiến trúc ngay từ đầu.',
                    'how_we_help' => 'XO tư vấn kiến trúc và roadmap sản phẩm, rồi trực tiếp xây dựng MVP trên nền LMS hoặc AI Education — hoạt động như một đội ngũ sản phẩm mở rộng, không phải một nhà thầu gia công thông thường.',
                ],
                'en' => [
                    'title' => 'EdTech Startups',
                    'subheading' => 'An external product team to help you build and launch fast.',
                    'pain_points' => 'Founders have a clear product vision but lack an in-house team with real education-technology experience to turn it into an MVP on time, on budget, and without picking the wrong architecture from day one.',
                    'how_we_help' => 'XO consults on architecture and product roadmap, then builds the MVP on an LMS or AI Education foundation — acting as an extended product team, not a typical outsourcing shop.',
                ],
            ],
            [
                'slug' => 'enterprise-learning',
                'solutions' => ['learning-analytics', 'ai-education', 'school-management'],
                'vi' => [
                    'title' => 'Đào tạo nội bộ doanh nghiệp',
                    'subheading' => 'Đo lường và tối ưu hiệu quả đào tạo ở quy mô toàn doanh nghiệp.',
                    'pain_points' => 'Doanh nghiệp đã có chương trình đào tạo nội bộ nhưng khó đo lường mức độ hoàn thành, hiệu quả tiếp thu và tác động thực tế tới hiệu suất công việc của nhân sự.',
                    'how_we_help' => 'XO xây dựng hệ thống quản lý đào tạo tích hợp learning analytics và gợi ý cá nhân hóa bằng AI, giúp đội L&D chứng minh được ROI của chương trình đào tạo bằng dữ liệu cụ thể.',
                ],
                'en' => [
                    'title' => 'Enterprise Learning Teams',
                    'subheading' => 'Measure and optimize training effectiveness at company scale.',
                    'pain_points' => 'The company already runs internal training programs but struggles to measure completion, real learning uptake, and the actual impact on employee performance.',
                    'how_we_help' => 'XO builds a training management system with integrated learning analytics and AI-driven personalization, helping the L&D team prove training ROI with concrete data.',
                ],
            ],
        ];

        foreach ($audiences as $index => $data) {
            $audience = Audience::create(['status' => 'published', 'sort_order' => $index]);

            foreach (['vi', 'en'] as $locale) {
                $audience->translations()->create([
                    'locale' => $locale,
                    'slug' => $data['slug'],
                    'title' => $data[$locale]['title'],
                    'subheading' => $data[$locale]['subheading'],
                    'pain_points' => $data[$locale]['pain_points'],
                    'how_we_help' => $data[$locale]['how_we_help'],
                    'meta_title' => $data[$locale]['title'],
                    'meta_description' => $data[$locale]['subheading'],
                ]);
            }

            $solutionIds = Solution::whereHas('translations', fn ($q) => $q->whereIn('slug', $data['solutions']))
                ->get()
                ->sortBy(fn ($solution) => array_search($solution->translation('vi')?->slug, $data['solutions']))
                ->pluck('id')
                ->values();

            $syncPayload = [];
            foreach ($solutionIds as $order => $solutionId) {
                $syncPayload[$solutionId] = ['sort_order' => $order];
            }

            $audience->solutions()->sync($syncPayload);
        }
    }
}
