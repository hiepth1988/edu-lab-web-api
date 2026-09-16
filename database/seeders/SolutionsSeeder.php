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
                    'architecture_approach' => "Ba lớp đứng sau mọi giải pháp AI của XO:\n\n1. RAG — biết đúng nội dung: chia tài liệu thành đoạn nhỏ có gắn nhãn theo mục tiêu học tập, tìm kiếm kết hợp từ khoá + ngữ nghĩa, luôn kèm trích dẫn nguồn.\n2. Mastery Model — biết học viên đang ở đâu: theo dõi mức độ thành thạo theo từng chủ đề, chọn mô hình phù hợp với lượng dữ liệu hiện có thay vì áp mô hình phức tạp ngay từ đầu.\n3. Tầng sư phạm — biết nên phản hồi thế nào: chọn chiến lược phản hồi theo mức độ học viên, có cơ chế từ chối giải bài hộ khi phát hiện học viên đang copy đề bài để AI làm thay.",
                    'use_cases' => "Trung tâm đào tạo: AI chấm luận cho bài tập tự luận hàng tuần, giảm tải chấm bài thủ công.\nTrường học: AI tạo đề ôn tập bám sát đúng giáo trình từng lớp, RAG trả lời câu hỏi học viên đúng theo sách giáo khoa nhà trường dùng.\nEdTech Startup: RAG + AI chatbot làm lớp trợ lý học tập gắn vào sản phẩm sẵn có, không cần tự xây từ đầu.\nGiáo viên & chuyên gia độc lập: AI tạo đề rút ngắn thời gian soạn bài kiểm tra.",
                    'trust_safety' => [
                        ['question' => 'AI có thể sai không?', 'answer' => 'Có — mọi hệ thống AI tạo sinh đều có thể trả lời sai (hallucination). XO giảm rủi ro này bằng RAG (buộc AI bám vào tài liệu thật thay vì tự bịa) và luôn hiển thị trích dẫn nguồn để giáo viên/học viên tự kiểm chứng.'],
                        ['question' => 'Con người có vai trò gì trong quy trình?', 'answer' => 'Với các quyết định quan trọng — chấm điểm kỳ thi chính thức, ra đề thi chính thức — XO khuyến nghị và thiết kế hệ thống theo hướng AI hỗ trợ, giáo viên duyệt cuối, không để AI tự động quyết định một mình.'],
                    ],
                    'features' => [
                        ['title' => 'AI Tutor', 'description' => 'Trợ lý học tập có trạng thái — không chỉ trả lời câu hỏi rời rạc mà theo dõi mức độ thành thạo (mastery) của từng học viên theo từng chủ đề, rồi chọn cách phản hồi phù hợp: gợi mở kiểu Socratic cho học viên khá, giảng trực tiếp cho người mới, hoặc lùi dần ví dụ mẫu cho kỹ năng cần luyện tập. AI Tutor được xây trên cùng lớp dữ liệu cá nhân hoá với Adaptive Learning.'],
                        ['title' => 'AI Chatbot', 'description' => 'Trợ lý hỏi-đáp cho học viên, phụ huynh hoặc giáo viên — tra cứu nội dung khoá học, chính sách, lịch học, giải đáp thắc mắc thường gặp. Nhẹ hơn AI Tutor: không theo dõi tiến độ học tập, phù hợp làm điểm chạm đầu tiên trước khi đầu tư vào một trợ lý học tập đầy đủ.'],
                        ['title' => 'AI tạo đề', 'description' => 'Sinh câu hỏi trắc nghiệm/tự luận từ chính tài liệu môn học của bạn (giáo trình, bài tập, đề cũ), có vòng tự kiểm định chất lượng trước khi đưa ra bộ đề cuối. Cách làm này đã được kiểm chứng ở quy mô lớn trong các nghiên cứu thực địa về độ tin cậy của đề thi do AI sinh.'],
                        ['title' => 'AI chấm luận', 'description' => 'Chấm bài tự luận bằng cách kết hợp nhiều tín hiệu — không chỉ để AI "đọc và cho điểm" một mình. Cách làm cho kết quả gần với người chấm nhất là kết hợp đặc trưng ngôn ngữ (độ khó đọc, ngữ pháp, đa dạng từ vựng) với khả năng hiểu ngữ nghĩa của AI. Với các kỳ thi có tính quyết định, XO khuyến nghị dùng AI để chấm sơ bộ/chấm bài tập thường xuyên, giữ giáo viên làm lớp duyệt cuối.'],
                        ['title' => 'RAG cho tài liệu học tập', 'description' => 'Nền tảng đứng sau các tính năng AI khác ở trên: thay vì để AI trả lời theo những gì nó "nhớ" chung chung, XO xây hệ thống truy xuất đúng đoạn tài liệu liên quan (sách giáo khoa, bài giảng, ngân hàng câu hỏi của bạn) trước khi để AI trả lời, kèm trích dẫn nguồn để giáo viên/học viên kiểm chứng lại được. Đây là lý do XO không chỉ là "một chatbot đơn giản": chatbot đơn thuần trả lời theo huấn luyện chung, còn hệ thống của XO trả lời theo đúng giáo trình của bạn.'],
                    ],
                ],
                'en' => [
                    'title' => 'AI Solutions for Education',
                    'subheading' => 'Bringing AI into education — not just another simple chatbot.',
                    'problem' => 'Many organizations want to try AI but do not know where to start beyond a simple chatbot.',
                    'solution_overview' => "A plain chatbot only answers from what the model already learned — it doesn't know your specific curriculum, doesn't remember which learner is struggling where, and can confidently give a wrong answer. XO builds AI solutions on two principles: staying grounded in your own material (no content invented outside the curriculum) and tracking each learner individually (not answering the same way for everyone). We deliver the 5 capabilities below, used individually or combined as needed.",
                    'architecture_note' => 'The AI tutor is a conversational interface layer — to personalize correctly for each learner, it needs mastery and learning-path data from Adaptive Learning. It should be deployed alongside or after Adaptive Learning and Learning Analytics, not before.',
                    'architecture_approach' => "Three layers sit behind every AI solution XO builds:\n\n1. RAG — knowing the right content: documents are chunked and tagged by learning objective, retrieved via hybrid keyword + semantic search, always with source citations.\n2. Mastery Model — knowing where the learner stands: tracks proficiency per topic, choosing a model that matches the data volume available rather than forcing a complex model from day one.\n3. Pedagogy layer — knowing how to respond: picks a response strategy based on learner level, with a built-in refusal to just solve homework when it detects a learner copy-pasting a question for the AI to answer.",
                    'use_cases' => "Training centers: AI essay grading for weekly assignments, cutting manual grading workload.\nSchools: AI-generated review questions aligned to each class's exact curriculum; RAG answers learner questions strictly from the textbook the school uses.\nEdTech startups: RAG + AI chatbot as a learning-assistant layer bolted onto an existing product, no need to build from scratch.\nIndependent educators & experts: AI question generation cuts down time spent writing quizzes.",
                    'trust_safety' => [
                        ['question' => 'Can the AI be wrong?', 'answer' => 'Yes — every generative AI system can produce a wrong answer (hallucination). XO reduces this risk with RAG (forcing the AI to ground itself in real material instead of inventing content) and always surfaces source citations so teachers/learners can verify.'],
                        ['question' => "What is the human's role in the process?", 'answer' => 'For high-stakes decisions — grading an official exam, generating an official exam — XO recommends and designs the system so AI assists while a teacher gives final approval; AI never decides alone.'],
                    ],
                    'features' => [
                        ['title' => 'AI Tutor', 'description' => "A stateful learning assistant — not just answering isolated questions, but tracking each learner's mastery per topic and choosing the right response style: Socratic prompting for stronger learners, direct instruction for beginners, or gradually fading worked examples for a skill in progress. AI Tutor is built on the same personalization data layer as Adaptive Learning."],
                        ['title' => 'AI Chatbot', 'description' => 'A Q&A assistant for learners, parents or teachers — looking up course content, policies, schedules, and answering common questions. Lighter than AI Tutor: no progress tracking, a good first touchpoint before investing in a full learning assistant.'],
                        ['title' => 'AI Question Generation', 'description' => "Generates multiple-choice/essay questions from your own subject material (curriculum, exercises, past exams), with a self-verification pass before the final question set ships. This approach has been validated at scale in field studies on AI-generated exam reliability."],
                        ['title' => 'AI Essay Grading', 'description' => 'Grades essays by combining multiple signals — not just having the AI "read and score" alone. The approach closest to a human grader combines linguistic features (readability, grammar, vocabulary diversity) with the AI\'s semantic understanding. For high-stakes exams, XO recommends using AI for preliminary/routine grading while keeping a teacher as final reviewer.'],
                        ['title' => 'RAG for Learning Materials', 'description' => 'The foundation behind the other AI features above: instead of letting the AI answer from whatever it generically "remembers," XO builds a retrieval system that surfaces the exact relevant material (textbooks, lectures, your question bank) before the AI answers, with citations so teachers/learners can verify. This is why XO is not "just another simple chatbot": a plain chatbot answers from general training, XO\'s system answers from your actual curriculum.'],
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

                $feature = $solution->features()->create(['sort_order' => $i]);
                $feature->translations()->create(['locale' => 'vi', 'title' => $viTitle, 'description' => $viDescription]);
                $feature->translations()->create(['locale' => 'en', 'title' => $enTitle, 'description' => $enDescription]);
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
