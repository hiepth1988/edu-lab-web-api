<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class HomePageSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedHome();
        $this->seedSimplePage('about', [
            'vi' => [
                'title' => 'Vì sao chọn XO',
                'excerpt' => '"Space to Become" — không gian để người kiến tạo giáo dục trở nên tốt hơn.',
                'content' => "XO Edu Lab tin rằng giáo dục không nên chuẩn hóa con người, mà nên giúp con người khám phá điều họ có thể trở thành. Đó là lý do triết lý \"Space to Become\" nằm ở trung tâm mọi sản phẩm XO xây dựng.\n\nXO không phải một đơn vị gia công phần mềm thông thường. Chúng tôi không chỉ nhận yêu cầu và code theo đúng bản thiết kế — chúng tôi cùng bạn đặt câu hỏi đúng trước khi xây dựng: Ai là người học? Họ cần trở thành người như thế nào? Mô hình nào giúp điều đó xảy ra bền vững?\n\nXO giúp nhà giáo dục, chuyên gia và đội ngũ EdTech trưởng thành theo ba cách: tư duy sản phẩm rõ ràng thay vì chỉ có nội dung hay, năng lực công nghệ vững chắc để không phụ thuộc mãi vào một đội ngũ thuê ngoài, và một quy trình lặp lại được để tiếp tục cải tiến sau khi ra mắt.\n\nMô hình hợp tác của XO linh hoạt theo giai đoạn của bạn: từ một sprint khám phá sản phẩm ngắn, đến xây dựng và ra mắt toàn diện, cho tới đồng hành dài hạn như một đội ngũ sản phẩm mở rộng.",
            ],
            'en' => [
                'title' => 'Why XO',
                'excerpt' => '"Space to Become" — a space for education creators to become more capable.',
                'content' => "XO Edu Lab believes education should not standardize people — it should help them discover what they can become. That philosophy, \"Space to Become,\" sits at the center of everything XO builds.\n\nXO is not a typical outsourcing shop. We don't just take a spec and code it — we work with you to ask the right questions before building anything: Who is the learner? Who do they need to become? What model makes that sustainable?\n\nXO helps educators, experts, and EdTech teams grow in three ways: clear product thinking instead of content alone, solid technical capability so you're not permanently dependent on an outside team, and a repeatable process to keep improving after launch.\n\nXO's partnership model flexes to your stage — from a short product discovery sprint, to full-service build and launch, to a long-term partnership acting as an extended product team.",
            ],
        ]);
        $this->seedSimplePage('contact', [
            'vi' => [
                'title' => 'Liên hệ',
                'excerpt' => 'Đặt lịch tư vấn hoặc gửi yêu cầu dự án cho XO Edu Lab.',
                'content' => 'Bạn đang xây LMS, Exam Platform hay AI Learning? Hãy để lại thông tin, chúng tôi sẽ phản hồi trong 24 giờ làm việc.',
            ],
            'en' => [
                'title' => 'Contact',
                'excerpt' => 'Book a consultation or send us your EdTech project brief.',
                'content' => 'Building an LMS, Exam Platform or AI Learning product? Leave your details and we will get back to you within one business day.',
            ],
        ]);
        $this->seedSimplePage('privacy', [
            'vi' => [
                'title' => 'Chính sách bảo mật',
                'excerpt' => 'Cách XO Edu Lab thu thập, sử dụng và bảo vệ dữ liệu của bạn.',
                'content' => "Nội dung chính sách bảo mật đầy đủ đang được hoàn thiện và sẽ được cập nhật tại đây trước khi website chính thức vận hành.\n\nMọi câu hỏi liên quan tới dữ liệu cá nhân, vui lòng liên hệ qua trang Liên hệ.",
            ],
            'en' => [
                'title' => 'Privacy Policy',
                'excerpt' => 'How XO Edu Lab collects, uses and protects your data.',
                'content' => "The full privacy policy is being finalized and will be published here before the site goes live.\n\nFor any questions about personal data, please reach out via the Contact page.",
            ],
        ]);
        $this->seedSimplePage('terms', [
            'vi' => [
                'title' => 'Điều khoản dịch vụ',
                'excerpt' => 'Các điều khoản áp dụng khi sử dụng dịch vụ của XO Edu Lab.',
                'content' => "Nội dung điều khoản dịch vụ đầy đủ đang được hoàn thiện và sẽ được cập nhật tại đây trước khi website chính thức vận hành.\n\nMọi câu hỏi, vui lòng liên hệ qua trang Liên hệ.",
            ],
            'en' => [
                'title' => 'Terms of Service',
                'excerpt' => 'The terms that apply when you use XO Edu Lab services.',
                'content' => "The full terms of service are being finalized and will be published here before the site goes live.\n\nFor any questions, please reach out via the Contact page.",
            ],
        ]);
    }

    private function seedSimplePage(string $key, array $translations): void
    {
        $page = Page::updateOrCreate(['key' => $key], ['status' => 'published']);

        foreach ($translations as $locale => $data) {
            $page->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'slug' => $key,
                    'meta_title' => $data['title'],
                    'meta_description' => $data['excerpt'],
                    ...$data,
                ]
            );
        }
    }

    private function seedHome(): void
    {
        $page = Page::updateOrCreate(['key' => 'home'], ['status' => 'published']);

        $page->translations()->updateOrCreate(['locale' => 'vi'], [
            'slug' => 'home',
            'title' => 'Biến ý tưởng giáo dục của bạn thành một trải nghiệm học tập đáng giá.',
            'excerpt' => 'Chúng tôi thiết kế và phát triển nền tảng giáo dục hiện đại, có khả năng mở rộng, tích hợp AI và dữ liệu học tập.',
            'meta_title' => 'Space to Become',
            'meta_description' => 'XO Edu Lab đồng hành cùng nhà giáo dục, chuyên gia và đội ngũ EdTech thiết kế, xây dựng và mở rộng sản phẩm học tập số: LMS, nền tảng thi trực tuyến, AI Education, Learning Analytics và Adaptive Learning.',
        ]);

        $page->translations()->updateOrCreate(['locale' => 'en'], [
            'slug' => 'home',
            'title' => 'Turn your education idea into an experience worth learning from.',
            'excerpt' => 'We design and build modern, scalable education platforms powered by AI and learning data.',
            'meta_title' => 'Space to Become',
            'meta_description' => 'XO Edu Lab helps educators, experts and EdTech teams design, build and scale digital learning products: LMS, online exam platforms, AI Education, Learning Analytics and Adaptive Learning.',
        ]);

        $sections = [
            'hero' => [
                'sort_order' => 1,
                'vi' => [
                    'heading' => 'Biến ý tưởng giáo dục của bạn thành một trải nghiệm học tập đáng giá.',
                    'subheading' => 'XO Edu Lab đồng hành cùng nhà giáo dục, chuyên gia và đội ngũ EdTech để thiết kế, xây dựng và mở rộng sản phẩm học tập số hoàn chỉnh — từ chiến lược, nội dung đến công nghệ và trải nghiệm người học.',
                    'cta_label' => 'Xây dựng cùng XO',
                    'cta_url' => '/contact',
                    'extra' => [
                        'eyebrow' => 'Space to Become',
                        'secondary_cta_label' => 'Tìm hiểu cách XO làm việc',
                        'secondary_cta_url' => '/about',
                        'quote' => 'Dành cho nhà giáo dục, đội ngũ đào tạo và nhà sáng lập EdTech muốn xây dựng giáo dục một cách bài bản.',
                    ],
                ],
                'en' => [
                    'heading' => 'Turn your education idea into an experience worth learning from.',
                    'subheading' => 'XO Edu Lab helps educators, experts, and EdTech teams design, build, and scale complete digital learning products — from strategy and content to technology and learner experience.',
                    'cta_label' => 'Build with XO',
                    'cta_url' => '/contact',
                    'extra' => [
                        'eyebrow' => 'Space to Become',
                        'secondary_cta_label' => 'Explore how we work',
                        'secondary_cta_url' => '/about',
                        'quote' => 'For educators, training teams, and EdTech founders who want to build education properly.',
                    ],
                ],
            ],
            'problem' => [
                'sort_order' => 2,
                'vi' => [
                    'heading' => 'Kiến thức thôi chưa đủ để tạo ra một sản phẩm học tập tốt.',
                    'body' => 'Phần lớn sản phẩm học tập số thất bại vì chỉ được xây trên nội dung, thiếu đi những trụ cột cấu trúc tạo ra chuyển đổi thực sự.',
                    'extra' => [
                        'cards' => [
                            ['icon' => 'architecture', 'title' => 'Nền tảng chiến lược', 'body' => 'Thiếu product-market fit, mục tiêu rõ ràng và mô hình bền vững, nội dung tốt nhất cũng chỉ dừng ở sở thích cá nhân, không phải sản phẩm.'],
                            ['icon' => 'neurology', 'title' => 'Kiến trúc học tập', 'body' => 'Học thụ động là kẻ thù của việc học. XO thu hẹp khoảng cách bằng thiết kế chương trình học và khoa học hành vi.'],
                            ['icon' => 'terminal', 'title' => 'Hệ sinh thái công nghệ', 'body' => 'Công cụ rời rạc tạo ra ma sát. XO xây nền tảng gắn kết, nơi công nghệ phục vụ người học chứ không phải ngược lại.'],
                        ],
                    ],
                ],
                'en' => [
                    'heading' => 'Knowledge alone does not create a great learning product.',
                    'body' => "Most digital learning fails because it's built on content alone, missing the structural pillars that drive real transformation.",
                    'extra' => [
                        'cards' => [
                            ['icon' => 'architecture', 'title' => 'Strategic Foundation', 'body' => 'Without market fit, clear outcomes, and a sustainable model, even the best content remains a hobby, not a product.'],
                            ['icon' => 'neurology', 'title' => 'Learning Architecture', 'body' => 'Passive consumption is the enemy of learning. We bridge the gap with curriculum design and behavioral science.'],
                            ['icon' => 'terminal', 'title' => 'Technical Ecosystem', 'body' => 'Fragmented tools create friction. We build cohesive platforms where technology serves the learner, not the other way around.'],
                        ],
                    ],
                ],
            ],
            'who_we_help' => [
                'sort_order' => 3,
                'vi' => [
                    'heading' => 'Đồng hành cùng những người dẫn dắt tương lai giáo dục.',
                    'body' => 'Giáo viên & chuyên gia độc lập, Trung tâm đào tạo, EdTech Startup, Trường học.',
                    'cta_label' => 'Xem câu chuyện của chúng tôi',
                    'cta_url' => '/who-we-help',
                    'extra' => [
                        'cards' => [
                            ['title' => 'Giáo viên & chuyên gia độc lập', 'body' => 'Sẵn sàng đóng gói kiến thức chuyên môn thành một tài sản có thể mở rộng quy mô.', 'image' => '/images/home/who-we-help-independent-educators.jpg', 'to' => '/who-we-help/independent-educators'],
                            ['title' => 'Trung tâm đào tạo', 'body' => 'Chuyển đổi mô hình vận hành truyền thống sang hybrid hoặc digital-first.', 'image' => '/images/home/who-we-help-training-centers.jpg', 'to' => '/who-we-help/training-centers'],
                            ['title' => 'EdTech Startup', 'body' => 'Founder tìm một đội ngũ sản phẩm bên ngoài để xây dựng và ra mắt nhanh.', 'image' => '/images/home/who-we-help-edtech-startups.jpg', 'to' => '/who-we-help/edtech-startups'],
                            ['title' => 'Trường học', 'body' => 'Hiện đại hóa hạ tầng công nghệ học tập cho toàn trường.', 'image' => '/images/home/who-we-help-education-orgs.jpg', 'to' => '/who-we-help/schools'],
                        ],
                    ],
                ],
                'en' => [
                    'heading' => 'Tailored for those leading the future of education.',
                    'body' => 'Independent Educators, Training Centers, EdTech Startups, Schools.',
                    'cta_label' => 'See our impact stories',
                    'cta_url' => '/who-we-help',
                    'extra' => [
                        'cards' => [
                            ['title' => 'Independent Educators', 'body' => 'Experts ready to productize their knowledge into a scalable asset.', 'image' => '/images/home/who-we-help-independent-educators.jpg', 'to' => '/who-we-help/independent-educators'],
                            ['title' => 'Training Centers', 'body' => 'Traditional centers transitioning to a hybrid or digital-first model.', 'image' => '/images/home/who-we-help-training-centers.jpg', 'to' => '/who-we-help/training-centers'],
                            ['title' => 'EdTech Startups', 'body' => 'Founders looking for an external product team to build and launch fast.', 'image' => '/images/home/who-we-help-edtech-startups.jpg', 'to' => '/who-we-help/edtech-startups'],
                            ['title' => 'Schools', 'body' => 'Modernizing learning technology infrastructure across the whole school.', 'image' => '/images/home/who-we-help-education-orgs.jpg', 'to' => '/who-we-help/schools'],
                        ],
                    ],
                ],
            ],
            'solutions' => [
                'sort_order' => 4,
                'vi' => [
                    'heading' => 'Giải pháp',
                    'body' => 'LMS, Online Exam, School Management, AI Education, Learning Analytics, Adaptive Learning',
                    'cta_label' => 'Xem tất cả giải pháp',
                    'cta_url' => '/solutions',
                ],
                'en' => [
                    'heading' => 'Solutions',
                    'body' => 'LMS, Online Exam, School Management, AI Education, Learning Analytics, Adaptive Learning',
                    'cta_label' => 'View all solutions',
                    'cta_url' => '/solutions',
                ],
            ],
            'journey' => [
                'sort_order' => 5,
                'vi' => [
                    'heading' => 'Không gian để người kiến tạo giáo dục trở nên tốt hơn.',
                    'extra' => [
                        'steps' => [
                            ['n' => '01', 'label' => 'Khám phá'],
                            ['n' => '02', 'label' => 'Thiết kế'],
                            ['n' => '03', 'label' => 'Thử nghiệm'],
                            ['n' => '04', 'label' => 'Xây dựng'],
                            ['n' => '05', 'label' => 'Đo lường'],
                            ['n' => '06', 'label' => 'Cải tiến'],
                            ['n' => '07', 'label' => 'Mở rộng'],
                        ],
                    ],
                ],
                'en' => [
                    'heading' => 'A space for education creators to become more capable.',
                    'extra' => [
                        'steps' => [
                            ['n' => '01', 'label' => 'Discover'],
                            ['n' => '02', 'label' => 'Design'],
                            ['n' => '03', 'label' => 'Experiment'],
                            ['n' => '04', 'label' => 'Build'],
                            ['n' => '05', 'label' => 'Measure'],
                            ['n' => '06', 'label' => 'Improve'],
                            ['n' => '07', 'label' => 'Scale'],
                        ],
                    ],
                ],
            ],
            'tech_capability' => [
                'sort_order' => 6,
                'vi' => [
                    'heading' => 'Mọi thứ cần thiết để xây một sản phẩm giáo dục hoàn chỉnh.',
                    'body' => 'Một hệ sinh thái năng lực theo module, đảm bảo tầm nhìn của bạn không chỉ ra mắt mà còn phát triển bền vững.',
                    'extra' => [
                        'items' => [
                            ['title' => 'Chiến lược', 'body' => 'Mô hình kinh doanh và product-market fit.'],
                            ['title' => 'Thiết kế học tập', 'body' => 'Kiến trúc chương trình và luồng sư phạm.'],
                            ['title' => 'Thiết kế trải nghiệm', 'body' => 'UI/UX tôn trọng sự tập trung của người học.'],
                            ['title' => 'Công nghệ & Dữ liệu', 'body' => 'Nền tảng tùy chỉnh và learning analytics.'],
                        ],
                    ],
                ],
                'en' => [
                    'heading' => 'Everything needed to build a complete education product.',
                    'body' => "A modular ecosystem of capabilities that ensures your vision doesn't just launch, but thrives and grows.",
                    'extra' => [
                        'items' => [
                            ['title' => 'Strategy', 'body' => 'Commercial model and product-market fit.'],
                            ['title' => 'Learning Design', 'body' => 'Curriculum architecture and pedagogical flow.'],
                            ['title' => 'Experience Design', 'body' => "UI/UX that respects the learner's attention."],
                            ['title' => 'Tech & Data', 'body' => 'Custom platforms and learning analytics.'],
                        ],
                    ],
                ],
            ],
            'process' => [
                'sort_order' => 7,
                'vi' => [
                    'heading' => 'Quy trình XO',
                    'body' => 'XO không chỉ xây dựng; chúng tôi đồng hành cùng bạn qua một phương pháp có cấu trúc, hướng tới sự xuất sắc trong giáo dục.',
                    'extra' => [
                        'phases' => [
                            ['n' => '01', 'title' => 'Thấu hiểu', 'body' => 'Đào sâu vào chuyên môn và tiềm năng thị trường của bạn.'],
                            ['n' => '02', 'title' => 'Định hình', 'body' => 'Phác thảo hành trình người học và tech stack.'],
                            ['n' => '03', 'title' => 'Thiết kế', 'body' => 'Tạo mẫu và lặp lại trải nghiệm.'],
                            ['n' => '04', 'title' => 'Xây dựng', 'body' => 'Sản xuất nội dung và phát triển ứng dụng.'],
                            ['n' => '05', 'title' => 'Cải tiến', 'body' => 'Tăng trưởng sau ra mắt và tối ưu kỹ thuật.'],
                        ],
                    ],
                ],
                'en' => [
                    'heading' => 'The XO Process',
                    'body' => "We don't just build; we partner with you through a structured methodology designed for education excellence.",
                    'extra' => [
                        'phases' => [
                            ['n' => '01', 'title' => 'Understand', 'body' => 'Deep dive into your expertise and market potential.'],
                            ['n' => '02', 'title' => 'Define', 'body' => 'Blueprint the learner journey and tech stack.'],
                            ['n' => '03', 'title' => 'Design', 'body' => 'Iterative prototyping of the experience.'],
                            ['n' => '04', 'title' => 'Build', 'body' => 'Production of content and development of the app.'],
                            ['n' => '05', 'title' => 'Improve', 'body' => 'Post-launch growth and technical optimization.'],
                        ],
                    ],
                ],
            ],
            'scale' => [
                'sort_order' => 8,
                'vi' => [
                    'heading' => 'Từ một khóa học đến cả một hệ sinh thái học tập.',
                    'extra' => [
                        'tiles' => [
                            ['type' => 'image', 'size' => 'lg', 'image' => '/images/home/scale-academic-platform.jpg', 'title' => 'Nền tảng học thuật tùy chỉnh'],
                            ['type' => 'icon', 'size' => 'sm', 'tone' => 'accent', 'icon' => 'school', 'title' => 'Ứng dụng học vi mô có thể mở rộng', 'body' => 'Trải nghiệm học trên di động ngắn gọn, tiếp thu kỹ năng nhanh.'],
                            ['type' => 'icon', 'size' => 'sm', 'icon' => 'database', 'title' => 'Content Engine', 'body' => 'Quy trình tự động hóa sản xuất nội dung giáo dục.'],
                            ['type' => 'image', 'size' => 'lg', 'image' => '/images/home/scale-ai-tutoring.jpg', 'title' => 'Gia sư cá nhân hóa bằng AI', 'body' => 'Lộ trình học tập cá nhân hóa nhờ tích hợp LLM hiện đại.'],
                        ],
                    ],
                ],
                'en' => [
                    'heading' => 'From one course to an entire learning ecosystem.',
                    'extra' => [
                        'tiles' => [
                            ['type' => 'image', 'size' => 'lg', 'image' => '/images/home/scale-academic-platform.jpg', 'title' => 'Custom Academic Platforms'],
                            ['type' => 'icon', 'size' => 'sm', 'tone' => 'accent', 'icon' => 'school', 'title' => 'Scalable Micro-learning Apps', 'body' => 'Bite-sized mobile experiences for rapid skill acquisition.'],
                            ['type' => 'icon', 'size' => 'sm', 'icon' => 'database', 'title' => 'Content Engines', 'body' => 'Automated workflows for education content production.'],
                            ['type' => 'image', 'size' => 'lg', 'image' => '/images/home/scale-ai-tutoring.jpg', 'title' => 'AI-Powered Tutoring', 'body' => 'Personalized learning paths driven by modern LLM integration.'],
                        ],
                    ],
                ],
            ],
            'partnership' => [
                'sort_order' => 9,
                'vi' => [
                    'heading' => 'Cách chúng tôi hợp tác',
                    'extra' => [
                        'plans' => [
                            ['title' => 'Khám phá sản phẩm', 'body' => 'Sprint 4 tuần để định hình tầm nhìn sản phẩm, mô hình kinh doanh và roadmap.', 'cta_label' => 'Tìm hiểu discovery', 'cta_url' => '/contact', 'featured' => false],
                            ['title' => 'Xây dựng & Ra mắt', 'body' => 'Thiết kế và phát triển toàn diện sản phẩm giáo dục của bạn từ zero-to-one.', 'cta_label' => 'Bắt đầu xây dựng', 'cta_url' => '/contact', 'featured' => true, 'badge' => 'Phổ biến nhất'],
                            ['title' => 'Đồng hành dài hạn', 'body' => 'Quản lý sản phẩm liên tục, bảo trì kỹ thuật và thử nghiệm tăng trưởng.', 'cta_label' => 'Đồng hành cùng XO', 'cta_url' => '/contact', 'featured' => false],
                        ],
                    ],
                ],
                'en' => [
                    'heading' => 'How we partner',
                    'extra' => [
                        'plans' => [
                            ['title' => 'Product Discovery', 'body' => '4-week sprint to define your product vision, commercial model, and roadmap.', 'cta_label' => 'Explore discovery', 'cta_url' => '/contact', 'featured' => false],
                            ['title' => 'Build & Launch', 'body' => 'Full-service design and development of your education product from zero to one.', 'cta_label' => 'Start building', 'cta_url' => '/contact', 'featured' => true, 'badge' => 'Most Common'],
                            ['title' => 'Long-term Partnership', 'body' => 'Ongoing product management, technical maintenance, and growth experimentation.', 'cta_label' => 'Partner with us', 'cta_url' => '/contact', 'featured' => false],
                        ],
                    ],
                ],
            ],
            'philosophy' => [
                'sort_order' => 10,
                'vi' => [
                    'body' => 'Giáo dục không nên chuẩn hóa con người. Nó nên giúp con người khám phá điều họ có thể trở thành.',
                    'extra' => ['cite' => 'Tuyên ngôn XO'],
                ],
                'en' => [
                    'body' => 'Education should not standardize people. It should help them discover what they can become.',
                    'extra' => ['cite' => 'The XO Manifesto'],
                ],
            ],
            'research_lab' => [
                'sort_order' => 11,
                'vi' => [
                    'heading' => 'Research Lab',
                    'body' => 'Knowledge Graph, Brain-based Learning, Student Behavior, Human Potential',
                    'cta_label' => 'Khám phá Research',
                    'cta_url' => '/research',
                ],
                'en' => [
                    'heading' => 'Research Lab',
                    'body' => 'Knowledge Graph, Brain-based Learning, Student Behavior, Human Potential',
                    'cta_label' => 'Explore Research',
                    'cta_url' => '/research',
                ],
            ],
            'our_work' => [
                'sort_order' => 12,
                'vi' => [
                    'heading' => 'Dự án của chúng tôi',
                    'body' => 'TopThi và các dự án có thể công khai/ẩn danh — chứng minh năng lực thực chiến.',
                    'cta_label' => 'Xem dự án',
                    'cta_url' => '/our-work',
                ],
                'en' => [
                    'heading' => 'Our Work',
                    'body' => 'TopThi and other projects — proof of real-world execution.',
                    'cta_label' => 'View our work',
                    'cta_url' => '/our-work',
                ],
            ],
            'latest_insights' => [
                'sort_order' => 13,
                'vi' => [
                    'heading' => 'Bài viết mới nhất',
                    'body' => '',
                    'cta_label' => 'Xem tất cả Insights',
                    'cta_url' => '/insights',
                ],
                'en' => [
                    'heading' => 'Latest Insights',
                    'body' => '',
                    'cta_label' => 'View all Insights',
                    'cta_url' => '/insights',
                ],
            ],
            'final_cta' => [
                'sort_order' => 14,
                'vi' => [
                    'heading' => 'Bạn mang tầm nhìn giáo dục đến. XO giúp bạn xây dựng điều nó có thể trở thành.',
                    'body' => 'Cùng trao đổi về trải nghiệm học tập tiếp theo của bạn.',
                    'cta_label' => 'Đặt lịch tư vấn',
                    'cta_url' => '/contact',
                ],
                'en' => [
                    'heading' => 'You bring the education vision. We help you build what it can become.',
                    'body' => "Let's talk about your next learning experience.",
                    'cta_label' => 'Book a consultation',
                    'cta_url' => '/contact',
                ],
            ],
        ];

        foreach ($sections as $key => $section) {
            $model = $page->sections()->updateOrCreate(
                ['section_key' => $key],
                ['sort_order' => $section['sort_order'], 'is_active' => true]
            );

            foreach (['vi', 'en'] as $locale) {
                $model->translations()->updateOrCreate(
                    ['locale' => $locale],
                    $section[$locale]
                );
            }
        }
    }
}
