# 00. Tổng quan, định vị & sitemap

> Nguồn: `education_tech_company_website_plan.docx`. File này tổng hợp phần định vị chiến lược, sitemap, bố cục trang chủ, design direction và checklist ưu tiên. Các mảng kỹ thuật chi tiết hơn (backend, frontend, admin, i18n, roadmap) nằm ở các file plan riêng trong cùng thư mục `plans/`.

## 1. Mục tiêu & định vị

- **Stack:** Frontend NuxtJS 3 / Vue 3 · Admin Vue 3 · Backend Laravel 12.
- **Mục tiêu:** Website công ty không chỉ giới thiệu dịch vụ, mà là **knowledge hub** về công nghệ giáo dục (EdTech), AI learning, exam platform, learning analytics.
- **Định vị:** *Education Technology Partner / Education Technology Lab* — không định vị là công ty outsource phần mềm thông thường.

**Thông điệp chính:** Chúng tôi xây dựng hạ tầng công nghệ cho giáo dục: LMS, nền tảng thi trực tuyến, AI Education, Learning Analytics và Adaptive Learning.

### Không nói / Nên nói

| Không nên nói | Nên nói |
|---|---|
| Chúng tôi nhận làm phần mềm theo yêu cầu | Chúng tôi thiết kế và phát triển nền tảng giáo dục có khả năng mở rộng |
| Outsource giá tốt | Đối tác công nghệ hiểu sâu nghiệp vụ giáo dục |
| Code theo yêu cầu | Tư vấn kiến trúc, dữ liệu, AI và sản phẩm giáo dục |
| Làm website / app | Xây LMS, Exam Platform, Learning Analytics, AI Learning Engine |

## 2. Sitemap tổng thể

| Menu | Mục tiêu | URL |
|---|---|---|
| Home | Truyền tải định vị, năng lực và CTA | `/` |
| Solutions | Các nhóm dịch vụ chính để bán | `/solutions` |
| Products | IP/nền tảng có thể tái sử dụng: TopThi, Exam Engine, AI Learning Engine | `/products` |
| Technology | Thể hiện năng lực kỹ thuật và kiến trúc | `/technology` |
| Research | Nơi xây thương hiệu nghiên cứu dài hạn | `/research` |
| Case Studies | Dự án, bài học triển khai, kết quả | `/case-studies` |
| Insights | Blog/knowledge hub chia theo chuyên mục | `/insights` |
| About | Mission, vision, team, văn hóa R&D | `/about` |
| Contact | Form liên hệ, book meeting, thông tin công ty | `/contact` |

> **Lưu ý đa ngôn ngữ:** các URL trên là dạng "không prefix" (locale mặc định). Khi có tiếng Anh, mỗi route sẽ tồn tại thêm ở dạng `/en/...`. Chi tiết chiến lược URL/slug đa ngôn ngữ xem [`05-ke-hoach-da-ngon-ngu-i18n.md`](./05-ke-hoach-da-ngon-ngu-i18n.md).

## 3. Bố cục trang chủ

| Section | Nội dung cần có | CTA / Mục tiêu |
|---|---|---|
| Hero | Headline: *Building the Future of Education Technology.* Subheadline: Chúng tôi thiết kế và phát triển nền tảng giáo dục hiện đại, có khả năng mở rộng, tích hợp AI và dữ liệu học tập. | Đặt lịch tư vấn / Xem demo |
| Who we help | Schools, Training Centers, EdTech Startups, Enterprise Learning | Chọn đúng phân khúc khách hàng |
| Solutions | LMS, Online Exam, School Management, AI Education, Learning Analytics, Adaptive Learning | Dẫn vào landing page từng dịch vụ |
| Technology Capability | Nuxt/Vue, Laravel, Spring Boot nếu cần, AI/LLM, Elasticsearch, Redis, Queue, Cloud, Security | Tạo niềm tin kỹ thuật |
| Research Lab | Knowledge Graph, Brain-based Learning, Student Behavior, Human Potential | Tạo khác biệt với outsource thường |
| Case Studies | TopThi và các case có thể công khai/ẩn danh | Chứng minh năng lực thực chiến |
| Latest Insights | 3–6 bài viết mới nhất | Xây SEO và trust |
| Final CTA | "Bạn đang xây LMS, Exam Platform hay AI Learning?" | Book a consultation |

## 4. Design direction

| Yếu tố | Định hướng |
|---|---|
| Style | Sạch, hiện đại, công nghệ, không quá màu mè; giống một R&D company hơn là agency outsource |
| Màu sắc | Xanh navy / xanh dương công nghệ, trắng, xám nhạt; dùng màu nhấn cho CTA |
| Typography | Sans-serif rõ ràng, dễ đọc; heading mạnh, body thoáng |
| Visual | Dashboard, graph, learning path, knowledge graph, student analytics, architecture diagram |
| Tone | Chuyên gia, thực chiến, có tư duy nghiên cứu; không phô trương quá mức |
| CTA | Book a consultation, Request a demo, Discuss your EdTech project |

> Khi làm đa ngôn ngữ, tone/CTA tiếng Anh cần được viết lại tự nhiên (không dịch máy 1:1) — xem checklist dịch thuật trong file i18n.

## 5. Checklist công việc ưu tiên

- [ ] Chốt tên thương hiệu/domain cho công ty công nghệ giáo dục.
- [ ] Viết copy trang Home bản đầu tiên (song ngữ VI/EN — xem file i18n).
- [ ] Tạo 7 landing page Solutions ở dạng skeleton trước, sau đó bổ sung dần nội dung.
- [ ] Chọn 10 bài blog đầu tiên để viết theo cụm: Exam Platform, LMS, Learning Analytics.
- [ ] Tạo case study TopThi làm bằng chứng năng lực.
- [ ] Xây Laravel 12 API + Vue 3 admin đủ CRUD bài viết/solution/case study (đa ngôn ngữ từ đầu).
- [ ] Xây NuxtJS 3 public site có SEO, sitemap, schema, tracking CTA, hreflang.
- [ ] Thiết lập Search Console, GA4, form lead và quy trình phản hồi khách hàng.
- [ ] Sau 90 ngày, đo: organic traffic, số form lead, số cuộc hẹn, số bài index, top keyword (theo từng locale).

## 6. Nguyên tắc dài hạn

- Mỗi dự án outsource trong giáo dục nên để lại một tài sản: code module, hiểu biết nghiệp vụ, dữ liệu hành vi học tập, case study hoặc framework. Nếu dự án chỉ tạo doanh thu mà không tạo tài sản, cần cân nhắc kỹ trước khi nhận.
- **TopThi** nên được xem là *living lab* của công ty. Các năng lực như Exam Engine, Knowledge Graph, Learning Analytics và AI Learning nên được thử nghiệm trên TopThi trước, sau đó đóng gói thành dịch vụ hoặc sản phẩm để bán cho thị trường.

## Danh sách các file plan liên quan

1. `00-tong-quan-dinh-vi-sitemap.md` (file này)
2. [`01-kien-truc-ky-thuat.md`](./01-kien-truc-ky-thuat.md) — kiến trúc kỹ thuật tổng thể
3. [`02-ke-hoach-backend-laravel.md`](./02-ke-hoach-backend-laravel.md) — DB schema, module, API
4. [`03-ke-hoach-frontend-nuxt.md`](./03-ke-hoach-frontend-nuxt.md) — trang public, SEO, template landing page
5. [`04-ke-hoach-admin-cms.md`](./04-ke-hoach-admin-cms.md) — admin Vue 3
6. [`05-ke-hoach-da-ngon-ngu-i18n.md`](./05-ke-hoach-da-ngon-ngu-i18n.md) — **đa ngôn ngữ (mảng bổ sung, chưa có trong spec gốc)**
7. [`06-roadmap-mvp-va-lich-trien-khai.md`](./06-roadmap-mvp-va-lich-trien-khai.md) — roadmap kỹ thuật MVP1–5 + roadmap nội dung 90 ngày
