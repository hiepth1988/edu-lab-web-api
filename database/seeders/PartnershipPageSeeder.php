<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * The partnership page (/hop-tac, /en/partnership) plus the home page's
 * starter-package teaser, which shares its feature list. Kept apart from
 * HomePageSeeder so it can be re-run on production on its own:
 * `php artisan db:seed --class=PartnershipPageSeeder --force`.
 */
class PartnershipPageSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedHomeTeaser();
        $this->seedPartnershipPage();
    }

    private function seedHomeTeaser(): void
    {
        $home = Page::updateOrCreate(['key' => 'home'], ['status' => 'published']);

        $starter = $this->starterPackage();
        foreach (['vi', 'en'] as $locale) {
            $starter[$locale]['extra']['secondary_cta_url'] = '/hop-tac';
        }
        $starter['vi']['extra']['secondary_cta_label'] = 'Xem các mô hình hợp tác';
        $starter['vi']['extra']['hint'] = 'Ngân sách hạn chế? Bạn có thể chọn mô hình cùng phát triển, chia sẻ doanh thu.';
        $starter['en']['extra']['secondary_cta_label'] = 'See collaboration models';
        $starter['en']['extra']['hint'] = 'Tight budget? You can choose a co-development, revenue-share model.';

        $this->seedSections($home, [
            'starter_package' => ['sort_order' => 10, ...$starter],
        ]);

        // The collaboration models moved to the partnership page.
        $home->sections()->where('section_key', 'partnership')->update(['is_active' => false]);
    }

    private function seedPartnershipPage(): void
    {
        $page = Page::updateOrCreate(['key' => 'partnership'], ['status' => 'published']);

        $page->translations()->updateOrCreate(['locale' => 'vi'], [
            'slug' => 'hop-tac',
            'title' => 'Hợp tác cùng XO Edu Lab',
            'excerpt' => 'Từ một website bán khóa học chỉ từ 20 triệu đồng đến sản phẩm EdTech dài hạn — chọn cách hợp tác phù hợp với nguồn lực và mục tiêu của bạn.',
            'meta_title' => 'Hợp tác phát triển sản phẩm EdTech — website bán khóa học từ 20 triệu',
            'meta_description' => 'Website bán khóa học hoàn chỉnh chỉ từ 20 triệu đồng: thanh toán, video, luyện tập, kiểm tra, chứng chỉ. Ba mô hình hợp tác linh hoạt: chia sẻ doanh thu, chi phí thấp + % doanh thu, thuê trọn gói.',
        ]);
        $page->translations()->updateOrCreate(['locale' => 'en'], [
            'slug' => 'partnership',
            'title' => 'Partner with XO Edu Lab',
            'excerpt' => 'From a course-selling website starting at US$1,000 to a long-term EdTech product — choose the collaboration model that fits your resources and goals.',
            'meta_title' => 'EdTech product partnership — course-selling websites from US$1,000',
            'meta_description' => 'A complete course-selling website from US$1,000: payments, video, practice, tests and certificates. Three flexible collaboration models: revenue share, low cost + revenue share, or full-service contract.',
        ]);

        $starter = $this->starterPackage();
        $starter['vi']['extra']['facts'] = [
            ['icon' => 'schedule', 'label' => 'Thời gian triển khai', 'value' => '4–6 tuần'],
            ['icon' => 'cloud', 'label' => 'Hosting (chưa gồm)', 'value' => 'Từ khoảng 150.000đ/tháng'],
            ['icon' => 'language', 'label' => 'Tên miền (chưa gồm)', 'value' => 'Tùy tên miền bạn chọn'],
            ['icon' => 'build', 'label' => 'Bảo trì', 'value' => 'Miễn phí 16 giờ/tháng, vượt mức 250.000đ/giờ'],
        ];
        $starter['en']['extra']['facts'] = [
            ['icon' => 'schedule', 'label' => 'Delivery time', 'value' => '4–6 weeks'],
            ['icon' => 'cloud', 'label' => 'Hosting (not included)', 'value' => 'From about US$6/month'],
            ['icon' => 'language', 'label' => 'Domain (not included)', 'value' => 'Depends on the name you choose'],
            ['icon' => 'build', 'label' => 'Maintenance', 'value' => '16 free hours/month, then US$10/hour'],
        ];

        $this->seedSections($page, [
            'starter_package' => ['sort_order' => 1, ...$starter],
            'models' => [
                'sort_order' => 2,
                'vi' => [
                    'heading' => 'Mô hình hợp tác linh hoạt',
                    'body' => 'Mỗi đối tác có nguồn lực và mục tiêu khác nhau, vì vậy chúng tôi không áp một khung cố định. Bạn có thể chọn một trong các mô hình dưới đây hoặc kết hợp chúng.',
                    'extra' => [
                        'plans' => [
                            [
                                'icon' => 'groups',
                                'title' => 'Cùng phát triển, chia sẻ doanh thu',
                                'body' => 'Bạn góp ý tưởng, chuyên môn, nội dung hoặc tệp học viên; XO Edu Lab góp công nghệ và đội ngũ phát triển. Chi phí ban đầu tối thiểu, hai bên chia sẻ doanh thu khi sản phẩm vận hành.',
                                'fit' => 'Phù hợp: giáo viên, trung tâm có nội dung tốt nhưng ngân sách hạn chế',
                            ],
                            [
                                'icon' => 'balance',
                                'title' => 'Chi phí thấp + % doanh thu',
                                'body' => 'Bạn chi trả một phần chi phí phát triển, phần còn lại được bù bằng tỷ lệ % doanh thu trong thời gian thỏa thuận. Giảm áp lực vốn mà hai bên vẫn cùng gắn bó với kết quả.',
                                'fit' => 'Phù hợp: trung tâm, startup muốn ra sản phẩm nhanh, chia sẻ rủi ro',
                            ],
                            [
                                'icon' => 'inventory_2',
                                'title' => 'Thuê trọn gói',
                                'body' => 'Báo giá rõ ràng theo phạm vi công việc, bàn giao sản phẩm hoàn chỉnh theo thỏa thuận, kèm gói bảo trì và vận hành nếu cần. Không chia sẻ doanh thu.',
                                'fit' => 'Phù hợp: trường học, doanh nghiệp cần chủ động hoàn toàn',
                            ],
                        ],
                    ],
                ],
                'en' => [
                    'heading' => 'Flexible collaboration models',
                    'body' => "Every partner has different resources and goals, so we don't force a fixed framework. Pick one of the models below or combine them.",
                    'extra' => [
                        'plans' => [
                            [
                                'icon' => 'groups',
                                'title' => 'Co-develop, share revenue',
                                'body' => 'You bring the idea, expertise, content or learner base; XO Edu Lab brings the technology and the development team. Minimal upfront cost — both sides share revenue once the product is live.',
                                'fit' => 'Best for: teachers and centers with great content but a limited budget',
                            ],
                            [
                                'icon' => 'balance',
                                'title' => 'Low cost + revenue share',
                                'body' => 'You pay part of the development cost; the rest is offset by a percentage of revenue over an agreed period. Less capital pressure, while both sides stay committed to the outcome.',
                                'fit' => 'Best for: centers and startups that want to launch fast and share the risk',
                            ],
                            [
                                'icon' => 'inventory_2',
                                'title' => 'Full-service contract',
                                'body' => 'A clear quote based on scope, a complete product delivered as agreed, plus optional maintenance and operations. No revenue share.',
                                'fit' => 'Best for: schools and businesses that want full ownership and control',
                            ],
                        ],
                    ],
                ],
            ],
            'comparison' => [
                'sort_order' => 3,
                'vi' => [
                    'heading' => 'So sánh nhanh ba mô hình',
                    'extra' => [
                        'columns' => ['Cùng phát triển, chia sẻ doanh thu', 'Chi phí thấp + % doanh thu', 'Thuê trọn gói'],
                        'rows' => [
                            ['label' => 'Chi phí ban đầu', 'values' => ['Tối thiểu', 'Một phần chi phí phát triển', 'Toàn bộ theo báo giá']],
                            ['label' => 'Chia sẻ doanh thu', 'values' => ['Có — tỷ lệ thỏa thuận theo từng dự án', 'Có — trong thời gian thỏa thuận', 'Không']],
                            ['label' => 'Quyền sở hữu', 'values' => [
                                'XO giữ mã nguồn nền tảng; bạn sở hữu thương hiệu, nội dung và dữ liệu học viên',
                                'Đồng sở hữu phần phát triển riêng, hoặc mua lại mã nguồn theo thỏa thuận',
                                'Bạn sở hữu mã nguồn phần phát triển riêng; phần lõi nền tảng được cấp quyền sử dụng',
                            ]],
                            ['label' => 'Phù hợp với', 'values' => ['Giáo viên, trung tâm có nội dung tốt, ngân sách hạn chế', 'Trung tâm, startup muốn ra sản phẩm nhanh', 'Trường học, doanh nghiệp cần chủ động hoàn toàn']],
                        ],
                    ],
                ],
                'en' => [
                    'heading' => 'The three models at a glance',
                    'extra' => [
                        'columns' => ['Co-develop, share revenue', 'Low cost + revenue share', 'Full-service contract'],
                        'rows' => [
                            ['label' => 'Upfront cost', 'values' => ['Minimal', 'Part of the development cost', 'Full cost as quoted']],
                            ['label' => 'Revenue share', 'values' => ['Yes — rate agreed per project', 'Yes — for an agreed period', 'No']],
                            ['label' => 'Ownership', 'values' => [
                                'XO keeps the platform source code; you own your brand, content and learner data',
                                'Co-ownership of custom work, or a source-code buyout as agreed',
                                'You own the source code of custom work; the core platform is licensed to you',
                            ]],
                            ['label' => 'Best for', 'values' => ['Teachers and centers with great content, limited budget', 'Centers and startups that want to launch fast', 'Schools and businesses that want full control']],
                        ],
                    ],
                ],
            ],
            'process' => [
                'sort_order' => 4,
                'vi' => [
                    'heading' => 'Quy trình hợp tác',
                    'extra' => [
                        'steps' => [
                            ['title' => 'Trao đổi nhu cầu', 'body' => 'Làm rõ người học, nội dung và mục tiêu kinh doanh của bạn.'],
                            ['title' => 'Đề xuất mô hình & báo giá', 'body' => 'Chọn mô hình hợp tác, chốt phạm vi và chi phí.'],
                            ['title' => 'Ký thỏa thuận', 'body' => 'Thống nhất phạm vi, tiến độ, quyền sở hữu và tỷ lệ doanh thu (nếu có).'],
                            ['title' => 'Thiết kế & phát triển', 'body' => 'Gói khởi đầu thường hoàn thành trong 4–6 tuần.'],
                            ['title' => 'Bàn giao & vận hành', 'body' => 'Hướng dẫn sử dụng, đưa vào vận hành và bảo trì định kỳ.'],
                        ],
                    ],
                ],
                'en' => [
                    'heading' => 'How we work together',
                    'extra' => [
                        'steps' => [
                            ['title' => 'Discuss your needs', 'body' => 'Clarify your learners, content and business goals.'],
                            ['title' => 'Model & quote', 'body' => 'Choose a collaboration model, agree on scope and cost.'],
                            ['title' => 'Sign the agreement', 'body' => 'Agree on scope, timeline, ownership and revenue share (if any).'],
                            ['title' => 'Design & build', 'body' => 'The starter package usually takes 4–6 weeks.'],
                            ['title' => 'Handover & operations', 'body' => 'User guide, go-live and ongoing maintenance.'],
                        ],
                    ],
                ],
            ],
            'faq' => [
                'sort_order' => 5,
                'vi' => [
                    'heading' => 'Câu hỏi thường gặp',
                    'extra' => [
                        'items' => [
                            ['q' => 'Gói website bán khóa học triển khai trong bao lâu?', 'a' => 'Thông thường 4–6 tuần, tùy mức độ tùy biến và thời điểm bạn cung cấp đủ nội dung.'],
                            ['q' => 'Chi phí từ 20 triệu đã gồm hosting, tên miền và bảo trì chưa?', 'a' => 'Chưa. Hosting bắt đầu từ khoảng 150.000đ/tháng, tên miền tùy tên bạn chọn. Bảo trì miễn phí 16 giờ/tháng, vượt mức tính 250.000đ/giờ.'],
                            ['q' => 'Tỷ lệ chia sẻ doanh thu là bao nhiêu?', 'a' => 'Tỷ lệ và thời gian chia sẻ doanh thu được thỏa thuận theo từng dự án, dựa trên phần đóng góp của mỗi bên.'],
                            ['q' => 'Ai sở hữu mã nguồn?', 'a' => 'Tùy mô hình. Với thuê trọn gói, bạn sở hữu mã nguồn phần phát triển riêng. Với các mô hình chia sẻ doanh thu, XO Edu Lab giữ mã nguồn nền tảng; bạn luôn sở hữu thương hiệu, nội dung và dữ liệu học viên, và có thể đồng sở hữu hoặc mua lại mã nguồn theo thỏa thuận.'],
                            ['q' => 'Tôi có thể kết hợp các mô hình không?', 'a' => 'Có. Ví dụ, bạn có thể bắt đầu với gói website bán khóa học rồi chuyển sang mô hình chia sẻ doanh thu cho các tính năng mở rộng.'],
                        ],
                    ],
                ],
                'en' => [
                    'heading' => 'Frequently asked questions',
                    'extra' => [
                        'items' => [
                            ['q' => 'How long does the course-selling website take?', 'a' => 'Usually 4–6 weeks, depending on how much customization you need and when your content is ready.'],
                            ['q' => 'Does the US$1,000 starting price include hosting, domain and maintenance?', 'a' => 'No. Hosting starts at about US$6/month and the domain depends on the name you choose. Maintenance is free for 16 hours/month, then US$10/hour.'],
                            ['q' => 'What is the revenue-share rate?', 'a' => "The rate and duration are agreed per project, based on each side's contribution."],
                            ['q' => 'Who owns the source code?', 'a' => 'It depends on the model. With a full-service contract, you own the source code of your custom work. With the revenue-share models, XO Edu Lab keeps the platform source code; you always own your brand, content and learner data, and can co-own or buy out the source code as agreed.'],
                            ['q' => 'Can I combine models?', 'a' => 'Yes. For example, you can start with the course-selling website and move to a revenue-share model for later features.'],
                        ],
                    ],
                ],
            ],
            'case_study' => [
                'sort_order' => 6,
                'vi' => [
                    'heading' => 'Đội ngũ đứng sau TopThi',
                    'body' => 'XO Edu Lab xây dựng và vận hành TopThi — nền tảng luyện thi đang phục vụ học viên thực tế. Xem cách chúng tôi biến ý tưởng thành sản phẩm.',
                    'cta_label' => 'Xem dự án TopThi',
                    'cta_url' => '/our-work/topthi',
                ],
                'en' => [
                    'heading' => 'The team behind TopThi',
                    'body' => 'XO Edu Lab builds and runs TopThi — an exam-prep platform serving real learners. See how we turn ideas into products.',
                    'cta_label' => 'See the TopThi project',
                    'cta_url' => '/our-work/topthi',
                ],
            ],
            'final_cta' => [
                'sort_order' => 7,
                'vi' => [
                    'heading' => 'Chưa chắc mô hình nào phù hợp?',
                    'body' => 'Kể cho chúng tôi về ý tưởng và nguồn lực của bạn — XO Edu Lab sẽ đề xuất cách hợp tác phù hợp nhất.',
                    'cta_label' => 'Đặt lịch tư vấn',
                    'cta_url' => '/contact',
                ],
                'en' => [
                    'heading' => 'Not sure which model fits?',
                    'body' => 'Tell us about your idea and resources — XO Edu Lab will suggest the best way to work together.',
                    'cta_label' => 'Book a consultation',
                    'cta_url' => '/contact',
                ],
            ],
        ]);
    }

    /** Shared by the home teaser and the partnership page. */
    private function starterPackage(): array
    {
        return [
            'vi' => [
                'heading' => 'Website bán khóa học hoàn chỉnh — chỉ từ 20 triệu đồng',
                'body' => 'Không cần đầu tư hàng trăm triệu để bắt đầu. Chỉ từ 20 triệu, bạn đã có một website bán khóa học mang thương hiệu riêng: thanh toán online, quản lý video bài giảng, luyện tập, kiểm tra và cấp chứng chỉ — sẵn sàng đón học viên đầu tiên.',
                'cta_label' => 'Nhận báo giá chi tiết',
                'cta_url' => '/contact',
                'extra' => [
                    'eyebrow' => 'Gói khởi đầu',
                    'price_prefix' => 'Chỉ từ',
                    'price' => '20 triệu',
                    'price_unit' => 'VNĐ',
                    'price_note' => 'Chưa gồm hosting, tên miền. Chi phí cuối cùng tùy mức độ tùy biến.',
                    'groups' => [
                        ['icon' => 'storefront', 'title' => 'Bán khóa học & thanh toán', 'items' => [
                            'Trang giới thiệu khóa học, giá và ưu đãi',
                            'Thanh toán online: chuyển khoản QR, cổng thanh toán',
                            'Mã giảm giá, combo khóa học',
                            'Tự động kích hoạt khóa học sau khi thanh toán',
                        ]],
                        ['icon' => 'play_circle', 'title' => 'Bài giảng & video', 'items' => [
                            'Quản lý video bài giảng, hạn chế tải xuống và chia sẻ link',
                            'Tổ chức khóa học theo chương, bài, tài liệu đính kèm',
                            'Theo dõi tiến độ, học tiếp từ chỗ đã dừng',
                        ]],
                        ['icon' => 'quiz', 'title' => 'Luyện tập & kiểm tra', 'items' => [
                            'Ngân hàng câu hỏi trắc nghiệm và tự luận',
                            'Bài luyện tập sau mỗi bài học, chấm điểm tự động',
                            'Bài kiểm tra có giới hạn thời gian, xem kết quả và đáp án',
                        ]],
                        ['icon' => 'workspace_premium', 'title' => 'Chứng chỉ', 'items' => [
                            'Tự động cấp chứng chỉ khi hoàn thành khóa học',
                            'Mẫu chứng chỉ theo thương hiệu, có mã xác thực',
                            'Học viên tải về hoặc chia sẻ chứng chỉ',
                        ]],
                        ['icon' => 'manage_accounts', 'title' => 'Học viên & quản trị', 'items' => [
                            'Tài khoản học viên, đăng ký / đăng nhập',
                            'Quản lý học viên, đơn hàng và doanh thu',
                            'Báo cáo tiến độ và kết quả học tập',
                        ]],
                        ['icon' => 'devices', 'title' => 'Thương hiệu & vận hành', 'items' => [
                            'Giao diện theo thương hiệu, tối ưu cho di động',
                            'Tối ưu SEO cơ bản',
                            'Hướng dẫn sử dụng và bàn giao vận hành',
                        ]],
                    ],
                ],
            ],
            'en' => [
                'heading' => 'A complete course-selling website — from US$1,000',
                'body' => "You don't need a huge budget to get started. From US\$1,000, you get a course-selling website under your own brand: online payments, video lesson management, practice, tests and certificates — ready for your first learners.",
                'cta_label' => 'Get a detailed quote',
                'cta_url' => '/contact',
                'extra' => [
                    'eyebrow' => 'Starter package',
                    'price_prefix' => 'From',
                    'price' => '$1,000',
                    'price_unit' => 'USD',
                    'price_note' => 'Hosting and domain not included. Final cost depends on how much customization you need.',
                    'groups' => [
                        ['icon' => 'storefront', 'title' => 'Course sales & payments', 'items' => [
                            'Course landing pages with pricing and promotions',
                            'Online payments: QR bank transfer, payment gateways',
                            'Coupons and course bundles',
                            'Automatic course access after payment',
                        ]],
                        ['icon' => 'play_circle', 'title' => 'Lessons & video', 'items' => [
                            'Video lesson management with download and link-sharing protection',
                            'Courses organized into chapters, lessons and attachments',
                            'Progress tracking, resume where you left off',
                        ]],
                        ['icon' => 'quiz', 'title' => 'Practice & tests', 'items' => [
                            'Question bank with multiple-choice and essay questions',
                            'Practice after each lesson with automatic grading',
                            'Timed tests with results and answer review',
                        ]],
                        ['icon' => 'workspace_premium', 'title' => 'Certificates', 'items' => [
                            'Certificates issued automatically on course completion',
                            'Branded certificate templates with a verification code',
                            'Learners can download or share their certificates',
                        ]],
                        ['icon' => 'manage_accounts', 'title' => 'Learners & administration', 'items' => [
                            'Learner accounts with sign-up / sign-in',
                            'Manage learners, orders and revenue',
                            'Progress and learning-outcome reports',
                        ]],
                        ['icon' => 'devices', 'title' => 'Branding & operations', 'items' => [
                            'Branded, mobile-friendly design',
                            'Basic SEO optimization',
                            'User guide and operational handover',
                        ]],
                    ],
                ],
            ],
        ];
    }

    private function seedSections(Page $page, array $sections): void
    {
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
