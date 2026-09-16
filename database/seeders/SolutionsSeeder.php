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
                    'architecture_note' => 'Chúng tôi xây Online Exam Platform bằng cách kết hợp Exam Engine + Question Bank Engine (hai năng lực lõi trong mục Sản phẩm & Công nghệ) với giao diện tùy chỉnh theo yêu cầu của bạn.',
                    'features' => ['Question bank', 'Random exam', 'Random answer', 'Auto grading', 'Chấm tự luận', 'Analytics', 'Proctoring integration'],
                ],
                'en' => [
                    'title' => 'Online Exam Platform',
                    'subheading' => 'An online exam platform for schools, test-prep centers and EdTech startups.',
                    'problem' => 'Online exams are prone to cheating without a proper question bank, randomization and proctoring.',
                    'solution_overview' => 'Question bank, randomized exams, randomized answers, auto grading, essay grading and analytics.',
                    'architecture_note' => 'We build Online Exam Platform by combining Exam Engine + Question Bank Engine (two core capabilities under Products & Technology) with an interface customized to your requirements.',
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
                    'solution_overview' => 'AI tutor, AI chatbot, AI tạo đề, AI chấm luận và RAG cho tài liệu học tập.',
                    'architecture_note' => 'AI tutor là lớp giao diện hội thoại — để cá nhân hóa đúng theo năng lực từng học viên, nó cần dữ liệu mức độ thành thạo và lộ trình học từ Adaptive Learning. Nên triển khai cùng hoặc sau Adaptive Learning và Learning Analytics, không phải trước.',
                    'features' => ['AI tutor', 'AI chatbot', 'AI tạo đề', 'AI chấm luận', 'RAG tài liệu học tập'],
                ],
                'en' => [
                    'title' => 'AI Solutions for Education',
                    'subheading' => 'Bringing AI into education for EdTech startups, centers and schools.',
                    'problem' => 'Many organizations want to try AI but do not know where to start beyond a simple chatbot.',
                    'solution_overview' => 'AI tutor, AI chatbot, AI question generation, AI essay grading and RAG for learning materials.',
                    'architecture_note' => 'The AI tutor is a conversational interface layer — to personalize correctly for each learner, it needs mastery and learning-path data from Adaptive Learning. It should be deployed alongside or after Adaptive Learning and Learning Analytics, not before.',
                    'features' => ['AI tutor', 'AI chatbot', 'AI question generation', 'AI essay grading', 'RAG for learning materials'],
                ],
                'faqs' => [
                    [
                        'vi' => ['question' => 'AI chấm luận/chấm đề có chính xác đến mức nào?', 'answer' => 'AI đưa ra điểm và nhận xét đề xuất, nhưng giáo viên luôn có quyền xem lại và chỉnh sửa trước khi công bố cho học viên — không có luồng nào chấm điểm hoàn toàn tự động mà con người không kiểm soát được.'],
                        'en' => ['question' => 'How accurate is AI grading?', 'answer' => 'AI produces a suggested score and feedback, but teachers can always review and adjust it before it reaches learners — no flow grades fully automatically without human oversight.'],
                    ],
                    [
                        'vi' => ['question' => 'Dữ liệu học sinh được xử lý và lưu trữ ra sao?', 'answer' => 'Dữ liệu bài làm và tương tác của học sinh chỉ dùng để tạo phản hồi cho chính học sinh đó và cải thiện chất lượng gợi ý, không chia sẻ cho bên thứ ba; chi tiết lưu trữ/xử lý được thống nhất theo yêu cầu bảo mật cụ thể của từng dự án.'],
                        'en' => ['question' => 'How is student data processed and stored?', 'answer' => 'Student submissions and interaction data are used only to generate feedback for that student and improve recommendation quality — never shared with third parties; exact storage/processing details are agreed per project based on your security requirements.'],
                    ],
                    [
                        'vi' => ['question' => 'Vai trò của giáo viên/con người trong vòng lặp AI là gì?', 'answer' => 'Giáo viên là người duyệt cuối cùng cho mọi kết quả AI tạo ra (đề thi, điểm chấm luận, gợi ý học tập) — AI hỗ trợ giảm khối lượng công việc lặp lại, không thay thế vai trò ra quyết định của giáo viên.'],
                        'en' => ['question' => 'What is the human/teacher role in the AI loop?', 'answer' => 'Teachers are the final reviewer for anything AI produces (generated questions, essay scores, learning suggestions) — AI reduces repetitive workload, it does not replace the teacher\'s decision-making role.'],
                    ],
                ],
            ],
            [
                'slug' => 'learning-analytics',
                'vi' => [
                    'title' => 'Learning Analytics',
                    'subheading' => 'Dữ liệu học tập cho đơn vị đã có LMS/exam nhưng thiếu dữ liệu ra quyết định.',
                    'problem' => 'Có LMS và exam nhưng không biết học viên nào có nguy cơ bỏ học cho tới khi đã quá muộn.',
                    'solution_overview' => 'Dashboard theo dõi tiến độ, hành vi học tập, retention, completion rate và dashboard cho giáo viên.',
                    'architecture_note' => 'Dữ liệu hành vi học tập ở đây là nền tảng đầu vào cho AI Education (gợi ý cá nhân hóa) và Adaptive Learning (lộ trình theo năng lực) — nên triển khai Learning Analytics trước hai giải pháp đó để có dữ liệu sẵn sàng.',
                    'features' => ['Student progress', 'Learning behavior', 'Retention', 'Completion rate', 'Teacher dashboard'],
                ],
                'en' => [
                    'title' => 'Learning Analytics',
                    'subheading' => 'Learning data for organizations that have LMS/exam but lack decision-ready data.',
                    'problem' => 'You have an LMS and exams but do not know which learners are at risk until it is too late.',
                    'solution_overview' => 'Dashboards for progress, learning behavior, retention, completion rate and teacher dashboards.',
                    'architecture_note' => 'The learning-behavior data captured here is the foundational input for AI Education (personalized recommendations) and Adaptive Learning (ability-based paths) — deploy Learning Analytics first so that data is ready.',
                    'features' => ['Student progress', 'Learning behavior', 'Retention', 'Completion rate', 'Teacher dashboard'],
                ],
                'faqs' => [
                    [
                        'vi' => ['question' => 'Cần bao nhiêu dữ liệu học viên trước khi dashboard có ý nghĩa?', 'answer' => 'Dashboard hiển thị được ngay từ những lượt học đầu tiên, nhưng các chỉ báo rủi ro bỏ học (retention) cần tối thiểu vài tuần dữ liệu hành vi để đạt độ tin cậy tốt.'],
                        'en' => ['question' => 'How much learner data is needed before the dashboard is meaningful?', 'answer' => 'The dashboard shows data from the first learning sessions, but dropout-risk signals need at least a few weeks of behavior data to become reliable.'],
                    ],
                    [
                        'vi' => ['question' => 'Learning Analytics có bắt buộc trước khi làm AI Education/Adaptive Learning không?', 'answer' => 'Không bắt buộc phải tách thành 2 dự án riêng biệt — hoàn toàn có thể triển khai đồng thời — nhưng phần thu thập dữ liệu hành vi cần đi vào vận hành trước để các mô-đun AI phía sau có dữ liệu để học và gợi ý.'],
                        'en' => ['question' => 'Is Learning Analytics required before building AI Education/Adaptive Learning?', 'answer' => 'You do not need two separate sequential projects — they can be delivered together — but the behavior-data collection piece needs to go live first so the AI modules downstream have data to learn from and recommend on.'],
                    ],
                ],
            ],
            [
                'slug' => 'adaptive-learning',
                'vi' => [
                    'title' => 'Adaptive Learning',
                    'subheading' => 'Cá nhân hóa học tập cho nền tảng muốn tối ưu lộ trình theo từng học viên.',
                    'problem' => 'Lộ trình học "một cỡ cho tất cả" không phù hợp với năng lực khác nhau của từng học viên.',
                    'solution_overview' => 'Knowledge graph, skill mapping, phát hiện điểm yếu, cá nhân hóa lộ trình và recommendation.',
                    'architecture_note' => 'Đây là hạ tầng cá nhân hóa đứng sau AI tutor và mọi trải nghiệm học tập cần gợi ý theo năng lực — AI Education dùng lại dữ liệu và mô hình từ đây thay vì xây riêng một lớp recommendation khác.',
                    'features' => ['Knowledge graph', 'Skill mapping', 'Weakness detection', 'Personalized learning path', 'Recommendation'],
                ],
                'en' => [
                    'title' => 'Adaptive Learning',
                    'subheading' => 'Personalized learning for platforms that want to tailor paths per learner.',
                    'problem' => 'A one-size-fits-all learning path does not match learners with different ability levels.',
                    'solution_overview' => 'Knowledge graph, skill mapping, weakness detection, personalized learning paths and recommendations.',
                    'architecture_note' => 'This is the personalization infrastructure behind the AI tutor and any learning experience that needs ability-based suggestions — AI Education reuses this data and model instead of building a separate recommendation layer.',
                    'features' => ['Knowledge graph', 'Skill mapping', 'Weakness detection', 'Personalized learning path', 'Recommendation'],
                ],
                'faqs' => [
                    [
                        'vi' => ['question' => 'Cần bao nhiêu dữ liệu để knowledge graph hoạt động tốt?', 'answer' => 'Knowledge graph khởi tạo được ngay từ khung chương trình có sẵn (không cần dữ liệu học viên), nhưng phần phát hiện điểm yếu và gợi ý cá nhân hóa cần dữ liệu hành vi thực tế — nên triển khai cùng Learning Analytics.'],
                        'en' => ['question' => 'How much data does the knowledge graph need to work well?', 'answer' => 'The knowledge graph can be seeded from your existing curriculum structure with no learner data, but weakness detection and personalized suggestions need real behavior data — best deployed alongside Learning Analytics.'],
                    ],
                    [
                        'vi' => ['question' => 'Adaptive Learning khác gì với AI tutor của AI Education?', 'answer' => 'Adaptive Learning là hạ tầng dữ liệu và mô hình quyết định "học viên nên học gì tiếp theo"; AI Education dùng lại chính hạ tầng đó để tạo ra trải nghiệm hội thoại (chatbot/tutor) cho học viên — không phải hai hệ thống recommendation tách biệt.'],
                        'en' => ['question' => 'How is Adaptive Learning different from the AI tutor in AI Education?', 'answer' => 'Adaptive Learning is the data and model layer that decides "what should this learner study next"; AI Education reuses that same layer to power a conversational experience (chatbot/tutor) for learners — not two separate recommendation systems.'],
                    ],
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
                    'architecture_note' => $data[$locale]['architecture_note'] ?? null,
                    'meta_title' => $data[$locale]['title'],
                    'meta_description' => $data[$locale]['subheading'],
                ]);
            }

            foreach ($data['vi']['features'] as $i => $viFeature) {
                $feature = $solution->features()->create(['sort_order' => $i]);
                $feature->translations()->create(['locale' => 'vi', 'title' => $viFeature]);
                $feature->translations()->create(['locale' => 'en', 'title' => $data['en']['features'][$i] ?? $viFeature]);
            }

            // Default FAQ pair used when a solution has no page-specific FAQs of its own
            // (checklist #12: AI Education / Learning Analytics / Adaptive Learning each
            // have risk-specific FAQs defined above instead of this generic pair).
            $defaultFaqs = [
                [
                    'vi' => ['question' => 'Thời gian triển khai mất bao lâu?', 'answer' => 'Tùy phạm vi, MVP đầu tiên thường mất 6-12 tuần.'],
                    'en' => ['question' => 'How long does implementation take?', 'answer' => 'Depending on scope, the first MVP typically takes 6-12 weeks.'],
                ],
                [
                    'vi' => ['question' => 'Có thể tích hợp với hệ thống hiện tại không?', 'answer' => 'Có, chúng tôi thiết kế API để dễ tích hợp với hệ thống sẵn có của bạn.'],
                    'en' => ['question' => 'Can this integrate with our existing systems?', 'answer' => 'Yes, we design APIs to integrate smoothly with your existing systems.'],
                ],
            ];

            $faqs = $data['faqs'] ?? $defaultFaqs;

            foreach ($faqs as $i => $faq) {
                $faqModel = $solution->faqs()->create(['sort_order' => $i]);
                $faqModel->translations()->create(['locale' => 'vi', ...$faq['vi']]);
                $faqModel->translations()->create(['locale' => 'en', ...$faq['en']]);
            }
        }
    }
}
