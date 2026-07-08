<?php

namespace Database\Seeders;

use App\Models\Solution;
use Illuminate\Database\Seeder;

class SolutionsSeeder extends Seeder
{
    public function run(): void
    {
        if (Solution::exists()) {
            return;
        }

        $solutions = [
            [
                'slug' => 'lms',
                'vi' => [
                    'title' => 'Learning Management System (LMS)',
                    'subheading' => 'Nền tảng quản lý học tập cho trung tâm, trường học và doanh nghiệp đào tạo nội bộ.',
                    'problem' => 'Trung tâm và doanh nghiệp đào tạo gặp khó khi quản lý khóa học, học viên và tiến độ học tập rải rác trên nhiều công cụ rời rạc.',
                    'solution_overview' => 'Một nền tảng LMS thống nhất: video, live class, assignment, certificate, learning path và thanh toán.',
                    'features' => ['Quản lý khóa học', 'Video & live class', 'Assignment & chấm bài', 'Certificate', 'Learning path', 'Thanh toán', 'Mobile-friendly'],
                ],
                'en' => [
                    'title' => 'Learning Management System (LMS)',
                    'subheading' => 'A learning management platform for training centers, schools and corporate training.',
                    'problem' => 'Training centers and enterprises struggle to manage courses, learners and progress across disconnected tools.',
                    'solution_overview' => 'One unified LMS: video, live classes, assignments, certificates, learning paths and payments.',
                    'features' => ['Course management', 'Video & live class', 'Assignments & grading', 'Certificates', 'Learning paths', 'Payments', 'Mobile-friendly'],
                ],
            ],
            [
                'slug' => 'online-exam-platform',
                'vi' => [
                    'title' => 'Online Exam Platform',
                    'subheading' => 'Nền tảng thi trực tuyến cho trường học, trung tâm luyện thi và EdTech startup.',
                    'problem' => 'Thi trực tuyến dễ bị gian lận nếu không có ngân hàng câu hỏi, random đề và giám sát phù hợp.',
                    'solution_overview' => 'Question bank, random exam, random answer, auto grading, chấm tự luận và phân tích kết quả.',
                    'features' => ['Question bank', 'Random exam', 'Random answer', 'Auto grading', 'Chấm tự luận', 'Analytics', 'Proctoring integration'],
                ],
                'en' => [
                    'title' => 'Online Exam Platform',
                    'subheading' => 'An online exam platform for schools, test-prep centers and EdTech startups.',
                    'problem' => 'Online exams are prone to cheating without a proper question bank, randomization and proctoring.',
                    'solution_overview' => 'Question bank, randomized exams, randomized answers, auto grading, essay grading and analytics.',
                    'features' => ['Question bank', 'Random exam', 'Random answer', 'Auto grading', 'Essay grading', 'Analytics', 'Proctoring integration'],
                ],
            ],
            [
                'slug' => 'school-management',
                'vi' => [
                    'title' => 'School & Training Center Management',
                    'subheading' => 'Số hóa vận hành cho trung tâm đào tạo, trường tư và học viện.',
                    'problem' => 'Quản lý học viên, giáo viên, lịch học và học phí bằng Excel gây rủi ro sai sót và khó mở rộng.',
                    'solution_overview' => 'Một hệ thống quản lý học viên, giáo viên, lớp, lịch học, điểm danh, học phí và báo cáo.',
                    'features' => ['Quản lý học viên', 'Quản lý giáo viên', 'Lịch học', 'Điểm danh', 'Học phí', 'Báo cáo'],
                ],
                'en' => [
                    'title' => 'School & Training Center Management',
                    'subheading' => 'Digitize operations for training centers, private schools and academies.',
                    'problem' => 'Managing students, teachers, schedules and tuition with spreadsheets is error-prone and hard to scale.',
                    'solution_overview' => 'A system to manage students, teachers, classes, schedules, attendance, tuition and reporting.',
                    'features' => ['Student management', 'Teacher management', 'Scheduling', 'Attendance', 'Tuition', 'Reporting'],
                ],
            ],
            [
                'slug' => 'ai-education',
                'vi' => [
                    'title' => 'AI Solutions for Education',
                    'subheading' => 'Đưa AI vào giáo dục cho EdTech startup, trung tâm và trường học.',
                    'problem' => 'Nhiều đơn vị muốn thử AI nhưng chưa biết bắt đầu từ đâu ngoài một chatbot đơn giản.',
                    'solution_overview' => 'AI tutor, AI chatbot, AI tạo đề, AI chấm luận, AI recommendation và RAG cho tài liệu học tập.',
                    'features' => ['AI tutor', 'AI chatbot', 'AI tạo đề', 'AI chấm luận', 'AI recommendation', 'RAG tài liệu học tập'],
                ],
                'en' => [
                    'title' => 'AI Solutions for Education',
                    'subheading' => 'Bringing AI into education for EdTech startups, centers and schools.',
                    'problem' => 'Many organizations want to try AI but do not know where to start beyond a simple chatbot.',
                    'solution_overview' => 'AI tutor, AI chatbot, AI question generation, AI essay grading, AI recommendation and RAG for learning materials.',
                    'features' => ['AI tutor', 'AI chatbot', 'AI question generation', 'AI essay grading', 'AI recommendation', 'RAG for learning materials'],
                ],
            ],
            [
                'slug' => 'learning-analytics',
                'vi' => [
                    'title' => 'Learning Analytics',
                    'subheading' => 'Dữ liệu học tập cho đơn vị đã có LMS/exam nhưng thiếu dữ liệu ra quyết định.',
                    'problem' => 'Có LMS và exam nhưng không biết học viên nào có nguy cơ bỏ học cho tới khi đã quá muộn.',
                    'solution_overview' => 'Dashboard theo dõi tiến độ, hành vi học tập, retention, completion rate và dashboard cho giáo viên.',
                    'features' => ['Student progress', 'Learning behavior', 'Retention', 'Completion rate', 'Teacher dashboard'],
                ],
                'en' => [
                    'title' => 'Learning Analytics',
                    'subheading' => 'Learning data for organizations that have LMS/exam but lack decision-ready data.',
                    'problem' => 'You have an LMS and exams but do not know which learners are at risk until it is too late.',
                    'solution_overview' => 'Dashboards for progress, learning behavior, retention, completion rate and teacher dashboards.',
                    'features' => ['Student progress', 'Learning behavior', 'Retention', 'Completion rate', 'Teacher dashboard'],
                ],
            ],
            [
                'slug' => 'adaptive-learning',
                'vi' => [
                    'title' => 'Adaptive Learning',
                    'subheading' => 'Cá nhân hóa học tập cho nền tảng muốn tối ưu lộ trình theo từng học viên.',
                    'problem' => 'Lộ trình học "một cỡ cho tất cả" không phù hợp với năng lực khác nhau của từng học viên.',
                    'solution_overview' => 'Knowledge graph, skill mapping, phát hiện điểm yếu, cá nhân hóa lộ trình và recommendation.',
                    'features' => ['Knowledge graph', 'Skill mapping', 'Weakness detection', 'Personalized learning path', 'Recommendation'],
                ],
                'en' => [
                    'title' => 'Adaptive Learning',
                    'subheading' => 'Personalized learning for platforms that want to tailor paths per learner.',
                    'problem' => 'A one-size-fits-all learning path does not match learners with different ability levels.',
                    'solution_overview' => 'Knowledge graph, skill mapping, weakness detection, personalized learning paths and recommendations.',
                    'features' => ['Knowledge graph', 'Skill mapping', 'Weakness detection', 'Personalized learning path', 'Recommendation'],
                ],
            ],
            [
                'slug' => 'edtech-consulting',
                'vi' => [
                    'title' => 'Education Technology Consulting',
                    'subheading' => 'Tư vấn cho founder EdTech, CTO, trường/trung tâm chuẩn bị làm sản phẩm.',
                    'problem' => 'Chọn sai kiến trúc hoặc roadmap ban đầu khiến chi phí tái cấu trúc về sau rất tốn kém.',
                    'solution_overview' => 'Tư vấn kiến trúc, product roadmap, AI strategy, data architecture, scaling và security.',
                    'features' => ['Kiến trúc', 'Product roadmap', 'AI strategy', 'Data architecture', 'Scaling', 'Security'],
                ],
                'en' => [
                    'title' => 'Education Technology Consulting',
                    'subheading' => 'Consulting for EdTech founders, CTOs, schools and centers building a product.',
                    'problem' => 'Choosing the wrong architecture or roadmap early on makes later re-architecture very costly.',
                    'solution_overview' => 'Architecture consulting, product roadmap, AI strategy, data architecture, scaling and security.',
                    'features' => ['Architecture', 'Product roadmap', 'AI strategy', 'Data architecture', 'Scaling', 'Security'],
                ],
            ],
        ];

        foreach ($solutions as $index => $data) {
            $solution = Solution::create(['status' => 'published', 'sort_order' => $index]);

            foreach (['vi', 'en'] as $locale) {
                $solution->translations()->create([
                    'locale' => $locale,
                    'slug' => $data['slug'],
                    'title' => $data[$locale]['title'],
                    'subheading' => $data[$locale]['subheading'],
                    'problem' => $data[$locale]['problem'],
                    'solution_overview' => $data[$locale]['solution_overview'],
                    'meta_title' => $data[$locale]['title'],
                    'meta_description' => $data[$locale]['subheading'],
                ]);
            }

            foreach ($data['vi']['features'] as $i => $viFeature) {
                $feature = $solution->features()->create(['sort_order' => $i]);
                $feature->translations()->create(['locale' => 'vi', 'title' => $viFeature]);
                $feature->translations()->create(['locale' => 'en', 'title' => $data['en']['features'][$i] ?? $viFeature]);
            }

            $faqs = [
                [
                    'vi' => ['question' => 'Thời gian triển khai mất bao lâu?', 'answer' => 'Tùy phạm vi, MVP đầu tiên thường mất 6-12 tuần.'],
                    'en' => ['question' => 'How long does implementation take?', 'answer' => 'Depending on scope, the first MVP typically takes 6-12 weeks.'],
                ],
                [
                    'vi' => ['question' => 'Có thể tích hợp với hệ thống hiện tại không?', 'answer' => 'Có, chúng tôi thiết kế API để dễ tích hợp với hệ thống sẵn có của bạn.'],
                    'en' => ['question' => 'Can this integrate with our existing systems?', 'answer' => 'Yes, we design APIs to integrate smoothly with your existing systems.'],
                ],
            ];

            foreach ($faqs as $i => $faq) {
                $faqModel = $solution->faqs()->create(['sort_order' => $i]);
                $faqModel->translations()->create(['locale' => 'vi', ...$faq['vi']]);
                $faqModel->translations()->create(['locale' => 'en', ...$faq['en']]);
            }
        }
    }
}
