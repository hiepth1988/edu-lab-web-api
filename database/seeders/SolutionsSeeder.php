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
                    'subheading' => 'Đưa AI vào giáo dục — không chỉ là một chatbot đơn giản.',
                    'problem' => 'Nhiều đơn vị muốn thử AI nhưng chưa biết bắt đầu từ đâu ngoài một chatbot đơn giản.',
                    'solution_overview' => 'Một chatbot đơn thuần chỉ trả lời theo những gì mô hình đã học sẵn — nó không biết giáo trình cụ thể của bạn, không nhớ học viên nào đang yếu ở đâu, và có thể trả lời sai một cách rất tự tin. XO xây các giải pháp AI theo hai nguyên tắc: bám sát đúng tài liệu của bạn (không bịa nội dung ngoài giáo trình) và theo dõi được từng học viên (không trả lời như nhau cho mọi người). Cụ thể, chúng tôi triển khai 5 năng lực dưới đây, dùng riêng lẻ hoặc kết hợp tùy nhu cầu.',
                    'architecture_note' => 'AI tutor là lớp giao diện hội thoại — để cá nhân hóa đúng theo năng lực từng học viên, nó cần dữ liệu mức độ thành thạo và lộ trình học từ Adaptive Learning. Nên triển khai cùng hoặc sau Adaptive Learning và Learning Analytics, không phải trước.',
                    'architecture_approach' => [
                        ['title' => 'RAG — biết đúng nội dung', 'description' => 'Chia tài liệu thành đoạn nhỏ có gắn nhãn theo mục tiêu học tập, tìm kiếm kết hợp từ khoá + ngữ nghĩa, luôn kèm trích dẫn nguồn.'],
                        ['title' => 'Mastery Model — biết học viên đang ở đâu', 'description' => 'Theo dõi mức độ thành thạo theo từng chủ đề, chọn mô hình phù hợp với lượng dữ liệu hiện có thay vì áp mô hình phức tạp ngay từ đầu.'],
                        ['title' => 'Tầng sư phạm — biết nên phản hồi thế nào', 'description' => 'Chọn chiến lược phản hồi theo mức độ học viên, có cơ chế từ chối giải bài hộ khi phát hiện học viên đang copy đề bài để AI làm thay.'],
                    ],
                    'use_cases' => [
                        ['audience' => 'Trung tâm đào tạo', 'description' => 'AI chấm luận cho bài tập tự luận hàng tuần, giảm tải chấm bài thủ công.'],
                        ['audience' => 'Trường học', 'description' => 'AI tạo đề ôn tập bám sát đúng giáo trình từng lớp, RAG trả lời câu hỏi học viên đúng theo sách giáo khoa nhà trường dùng.'],
                        ['audience' => 'EdTech Startup', 'description' => 'RAG + AI chatbot làm lớp trợ lý học tập gắn vào sản phẩm sẵn có, không cần tự xây từ đầu.'],
                        ['audience' => 'Giáo viên & chuyên gia độc lập', 'description' => 'AI tạo đề rút ngắn thời gian soạn bài kiểm tra.'],
                    ],
                    'trust_safety' => [
                        ['question' => 'AI có thể sai không?', 'answer' => 'Có — mọi hệ thống AI tạo sinh đều có thể trả lời sai (hallucination). XO giảm rủi ro này bằng RAG (buộc AI bám vào tài liệu thật thay vì tự bịa) và luôn hiển thị trích dẫn nguồn để giáo viên/học viên tự kiểm chứng.'],
                        ['question' => 'Con người có vai trò gì trong quy trình?', 'answer' => 'Với các quyết định quan trọng — chấm điểm kỳ thi chính thức, ra đề thi chính thức — XO khuyến nghị và thiết kế hệ thống theo hướng AI hỗ trợ, giáo viên duyệt cuối, không để AI tự động quyết định một mình.'],
                    ],
                    'features' => [
                        [
                            'title' => 'AI Tutor',
                            'description' => 'Trợ lý học tập có trạng thái — theo dõi mức độ thành thạo của từng học viên, không chỉ trả lời câu hỏi rời rạc.',
                            'highlights' => [
                                'Theo dõi mastery của từng học viên theo từng chủ đề',
                                'Chọn cách phản hồi phù hợp: gợi mở Socratic cho học viên khá, giảng trực tiếp cho người mới, lùi dần ví dụ mẫu cho kỹ năng cần luyện',
                                'Dùng chung lớp dữ liệu cá nhân hoá với Adaptive Learning',
                            ],
                        ],
                        [
                            'title' => 'AI Chatbot',
                            'description' => 'Trợ lý hỏi-đáp nhẹ cho học viên, phụ huynh hoặc giáo viên — điểm chạm đầu tiên trước khi đầu tư vào AI Tutor đầy đủ.',
                            'highlights' => [
                                'Tra cứu nội dung khoá học, chính sách, lịch học, giải đáp thắc mắc thường gặp',
                                'Không theo dõi tiến độ học tập — nhẹ hơn AI Tutor',
                            ],
                        ],
                        [
                            'title' => 'AI tạo đề',
                            'description' => 'Sinh câu hỏi trắc nghiệm/tự luận từ chính tài liệu môn học của bạn, có vòng tự kiểm định chất lượng.',
                            'highlights' => [
                                'Bám sát giáo trình, bài tập, đề cũ của bạn — không sinh câu hỏi ngoài phạm vi',
                                'Tự kiểm định chất lượng trước khi đưa ra bộ đề cuối',
                                'Cách làm đã được kiểm chứng ở quy mô lớn trong nghiên cứu thực địa về độ tin cậy của đề thi do AI sinh',
                            ],
                        ],
                        [
                            'title' => 'AI chấm luận',
                            'description' => 'Chấm bài tự luận bằng cách kết hợp nhiều tín hiệu — không để AI "đọc và cho điểm" một mình.',
                            'highlights' => [
                                'Kết hợp đặc trưng ngôn ngữ (độ khó đọc, ngữ pháp, đa dạng từ vựng) với khả năng hiểu ngữ nghĩa của AI',
                                'Cho kết quả gần với người chấm nhất so với chỉ dùng một tín hiệu',
                                'Với kỳ thi có tính quyết định: AI chấm sơ bộ/bài tập thường xuyên, giáo viên duyệt cuối',
                            ],
                        ],
                        [
                            'title' => 'RAG cho tài liệu học tập',
                            'description' => 'Nền tảng đứng sau mọi tính năng AI ở trên — lý do XO không chỉ là "một chatbot đơn giản".',
                            'highlights' => [
                                'Truy xuất đúng đoạn tài liệu liên quan (sách giáo khoa, bài giảng, ngân hàng câu hỏi của bạn) trước khi AI trả lời',
                                'Luôn kèm trích dẫn nguồn để giáo viên/học viên kiểm chứng lại',
                                'Chatbot đơn thuần trả lời theo huấn luyện chung — hệ thống của XO trả lời theo đúng giáo trình của bạn',
                            ],
                        ],
                    ],
                ],
                'en' => [
                    'title' => 'AI Solutions for Education',
                    'subheading' => 'Bringing AI into education — not just another simple chatbot.',
                    'problem' => 'Many organizations want to try AI but do not know where to start beyond a simple chatbot.',
                    'solution_overview' => "A plain chatbot only answers from what the model already learned — it doesn't know your specific curriculum, doesn't remember which learner is struggling where, and can confidently give a wrong answer. XO builds AI solutions on two principles: staying grounded in your own material (no content invented outside the curriculum) and tracking each learner individually (not answering the same way for everyone). We deliver the 5 capabilities below, used individually or combined as needed.",
                    'architecture_note' => 'The AI tutor is a conversational interface layer — to personalize correctly for each learner, it needs mastery and learning-path data from Adaptive Learning. It should be deployed alongside or after Adaptive Learning and Learning Analytics, not before.',
                    'architecture_approach' => [
                        ['title' => 'RAG — knowing the right content', 'description' => 'Documents are chunked and tagged by learning objective, retrieved via hybrid keyword + semantic search, always with source citations.'],
                        ['title' => 'Mastery Model — knowing where the learner stands', 'description' => 'Tracks proficiency per topic, choosing a model that matches the data volume available rather than forcing a complex model from day one.'],
                        ['title' => 'Pedagogy layer — knowing how to respond', 'description' => "Picks a response strategy based on learner level, with a built-in refusal to just solve homework when it detects a learner copy-pasting a question for the AI to answer."],
                    ],
                    'use_cases' => [
                        ['audience' => 'Training centers', 'description' => 'AI essay grading for weekly assignments, cutting manual grading workload.'],
                        ['audience' => 'Schools', 'description' => "AI-generated review questions aligned to each class's exact curriculum; RAG answers learner questions strictly from the textbook the school uses."],
                        ['audience' => 'EdTech startups', 'description' => 'RAG + AI chatbot as a learning-assistant layer bolted onto an existing product, no need to build from scratch.'],
                        ['audience' => 'Independent educators & experts', 'description' => 'AI question generation cuts down time spent writing quizzes.'],
                    ],
                    'trust_safety' => [
                        ['question' => 'Can the AI be wrong?', 'answer' => 'Yes — every generative AI system can produce a wrong answer (hallucination). XO reduces this risk with RAG (forcing the AI to ground itself in real material instead of inventing content) and always surfaces source citations so teachers/learners can verify.'],
                        ['question' => "What is the human's role in the process?", 'answer' => 'For high-stakes decisions — grading an official exam, generating an official exam — XO recommends and designs the system so AI assists while a teacher gives final approval; AI never decides alone.'],
                    ],
                    'features' => [
                        [
                            'title' => 'AI Tutor',
                            'description' => 'A stateful learning assistant — tracks each learner\'s mastery instead of just answering isolated questions.',
                            'highlights' => [
                                "Tracks each learner's mastery per topic",
                                'Chooses the right response style: Socratic prompting for stronger learners, direct instruction for beginners, gradually fading worked examples for a skill in progress',
                                'Built on the same personalization data layer as Adaptive Learning',
                            ],
                        ],
                        [
                            'title' => 'AI Chatbot',
                            'description' => 'A lightweight Q&A assistant for learners, parents or teachers — a good first touchpoint before investing in a full AI Tutor.',
                            'highlights' => [
                                'Looks up course content, policies, schedules, and answers common questions',
                                'No progress tracking — lighter than AI Tutor',
                            ],
                        ],
                        [
                            'title' => 'AI Question Generation',
                            'description' => 'Generates multiple-choice/essay questions from your own subject material, with a self-verification pass.',
                            'highlights' => [
                                'Grounded in your curriculum, exercises and past exams — never generates outside that scope',
                                'Self-verifies quality before the final question set ships',
                                'Validated at scale in field studies on AI-generated exam reliability',
                            ],
                        ],
                        [
                            'title' => 'AI Essay Grading',
                            'description' => 'Grades essays by combining multiple signals — not just having the AI "read and score" alone.',
                            'highlights' => [
                                "Combines linguistic features (readability, grammar, vocabulary diversity) with the AI's semantic understanding",
                                'Produces results closest to a human grader compared to using a single signal',
                                'For high-stakes exams: AI handles preliminary/routine grading, a teacher gives final review',
                            ],
                        ],
                        [
                            'title' => 'RAG for Learning Materials',
                            'description' => 'The foundation behind every AI feature above — why XO is not "just another simple chatbot".',
                            'highlights' => [
                                'Retrieves the exact relevant material (textbooks, lectures, your question bank) before the AI answers',
                                'Always includes source citations so teachers/learners can verify',
                                'A plain chatbot answers from general training — XO\'s system answers from your actual curriculum',
                            ],
                        ],
                    ],
                ],
                'faqs' => [
                    [
                        'vi' => ['question' => 'AI có thể trả lời sai không, và XO xử lý thế nào?', 'answer' => 'Có thể, giống mọi hệ thống AI tạo sinh khác. XO giảm rủi ro bằng cách buộc AI trả lời dựa trên tài liệu thật của bạn (RAG) thay vì tự bịa, và luôn hiển thị nguồn để kiểm chứng.'],
                        'en' => ['question' => 'Can the AI answer incorrectly, and how does XO handle that?', 'answer' => 'Yes, like any generative AI system. XO reduces this risk by grounding AI answers in your real material (RAG) instead of inventing content, and always shows sources for verification.'],
                    ],
                    [
                        'vi' => ['question' => 'AI chấm luận/chấm đề có chính xác đến mức nào?', 'answer' => 'AI đưa ra điểm và nhận xét đề xuất, nhưng giáo viên luôn có quyền xem lại và chỉnh sửa trước khi công bố cho học viên — không có luồng nào chấm điểm hoàn toàn tự động mà con người không kiểm soát được.'],
                        'en' => ['question' => 'How accurate is AI grading?', 'answer' => 'AI produces a suggested score and feedback, but teachers can always review and adjust it before it reaches learners — no flow grades fully automatically without human oversight.'],
                    ],
                    [
                        'vi' => ['question' => 'Có cần giáo viên duyệt lại kết quả AI không?', 'answer' => 'Với các việc ảnh hưởng trực tiếp đến điểm số chính thức (chấm thi, ra đề thi chính thức), XO khuyến nghị và thiết kế để giáo viên duyệt cuối, AI chỉ hỗ trợ.'],
                        'en' => ['question' => 'Does a teacher need to review AI output?', 'answer' => 'For anything affecting official scores (grading exams, generating official exams), XO recommends and designs the system so a teacher gives final approval — AI only assists.'],
                    ],
                    [
                        'vi' => ['question' => 'Thời gian triển khai mất bao lâu?', 'answer' => 'Tùy phạm vi. RAG hoặc AI tạo đề thường có MVP trong 6-8 tuần; AI Tutor có mastery model đầy đủ thường mất 12-16 tuần.'],
                        'en' => ['question' => 'How long does implementation take?', 'answer' => 'Depending on scope. RAG or AI question generation typically reach an MVP in 6-8 weeks; a full AI Tutor with a mastery model typically takes 12-16 weeks.'],
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
                    'title' => 'Learning Analytics',
                    'subheading' => 'Learning data for organizations that have LMS/exam but lack decision-ready data.',
                    'problem' => 'You have an LMS and exams but do not know which learners are at risk until it is too late.',
                    'solution_overview' => 'Dashboards for progress, learning behavior, retention, completion rate and teacher dashboards.',
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
                    'architecture_approach' => $data[$locale]['architecture_approach'] ?? null,
                    'use_cases' => $data[$locale]['use_cases'] ?? null,
                    'trust_safety' => $data[$locale]['trust_safety'] ?? [],
                    'meta_title' => $data[$locale]['title'],
                    'meta_description' => $data[$locale]['subheading'],
                ]);
            }

            // Each feature entry is either a plain string (title only) or an
            // ['title' => ..., 'description' => ...] array for solutions that need
            // longer per-feature copy (e.g. ai-education).
            foreach ($data['vi']['features'] as $i => $viFeature) {
                $enFeature = $data['en']['features'][$i] ?? $viFeature;
                $viTitle = is_array($viFeature) ? $viFeature['title'] : $viFeature;
                $enTitle = is_array($enFeature) ? $enFeature['title'] : $enFeature;
                $viDescription = is_array($viFeature) ? ($viFeature['description'] ?? null) : null;
                $enDescription = is_array($enFeature) ? ($enFeature['description'] ?? null) : null;
                $viHighlights = is_array($viFeature) ? ($viFeature['highlights'] ?? []) : [];
                $enHighlights = is_array($enFeature) ? ($enFeature['highlights'] ?? []) : [];

                $feature = $solution->features()->create(['sort_order' => $i]);
                $feature->translations()->create(['locale' => 'vi', 'title' => $viTitle, 'description' => $viDescription, 'highlights' => $viHighlights]);
                $feature->translations()->create(['locale' => 'en', 'title' => $enTitle, 'description' => $enDescription, 'highlights' => $enHighlights]);
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
