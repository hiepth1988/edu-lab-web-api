# 03. Kế hoạch Frontend — NuxtJS 3 (Public Website)

> Kiến trúc tổng thể ở [`01-kien-truc-ky-thuat.md`](./01-kien-truc-ky-thuat.md). Cấu hình `@nuxtjs/i18n`, routing đa ngôn ngữ chi tiết ở [`05-ke-hoach-da-ngon-ngu-i18n.md`](./05-ke-hoach-da-ngon-ngu-i18n.md#3-frontend-nuxt-3).

## 1. Landing page dịch vụ giai đoạn 1 (`/solutions/...`)

Giai đoạn đầu chỉ tập trung các dịch vụ liên quan trực tiếp đến giáo dục, có thể tái sử dụng thành module sản phẩm về sau.

### LMS — `/solutions/lms`
- **Khách hàng mục tiêu:** Trung tâm, trường học, doanh nghiệp đào tạo nội bộ.
- **Năng lực/tính năng:** Quản lý khóa học, video, live class, assignment, certificate, learning path, payment, mobile-friendly.
- **Blog hỗ trợ SEO:** LMS là gì?; Khi nào nên xây LMS riêng?; Kiến trúc LMS scale lớn.

### Online Exam Platform — `/solutions/online-exam-platform`
- **Khách hàng mục tiêu:** Trường học, trung tâm luyện thi, EdTech startup.
- **Năng lực/tính năng:** Question bank, random exam, random answer, auto grading, essay, analytics, proctoring integration.
- **Blog hỗ trợ SEO:** Thiết kế ngân hàng câu hỏi; Random đề chống gian lận; Online Exam Architecture.

### School & Training Center Management — `/solutions/school-management`
- **Khách hàng mục tiêu:** Trung tâm đào tạo, trường tư, học viện.
- **Năng lực/tính năng:** Quản lý học viên, giáo viên, lớp, lịch học, điểm danh, học phí, báo cáo.
- **Blog hỗ trợ SEO:** Số hóa trung tâm đào tạo; Quản lý trung tâm bằng Excel có rủi ro gì?

### AI Solutions for Education — `/solutions/ai-education`
- **Khách hàng mục tiêu:** EdTech startup, trung tâm, trường học muốn thử AI.
- **Năng lực/tính năng:** AI tutor, AI chatbot, AI tạo đề, AI chấm luận, AI recommendation, RAG cho tài liệu học tập.
- **Blog hỗ trợ SEO:** AI Tutor khác chatbot thế nào?; LLM trong giáo dục; AI chấm bài luận.

### Learning Analytics — `/solutions/learning-analytics`
- **Khách hàng mục tiêu:** Đơn vị đã có LMS/exam nhưng thiếu dữ liệu ra quyết định.
- **Năng lực/tính năng:** Dashboard, student progress, learning behavior, retention, completion rate, teacher dashboard.
- **Blog hỗ trợ SEO:** Learning Analytics là gì?; Vì sao dữ liệu học tập quan trọng hơn điểm số?

### Adaptive Learning — `/solutions/adaptive-learning`
- **Khách hàng mục tiêu:** Nền tảng muốn cá nhân hóa học tập.
- **Năng lực/tính năng:** Knowledge graph, skill mapping, weakness detection, personalized learning path, recommendation.
- **Blog hỗ trợ SEO:** Adaptive Learning hoạt động thế nào?; Knowledge Graph trong giáo dục.

### Education Technology Consulting — `/solutions/edtech-consulting`
- **Khách hàng mục tiêu:** Founder EdTech, CTO, trường/trung tâm chuẩn bị làm sản phẩm.
- **Năng lực/tính năng:** Tư vấn kiến trúc, product roadmap, AI strategy, data architecture, scaling, security.
- **Blog hỗ trợ SEO:** Kiến trúc nền tảng giáo dục 100.000 học sinh; Monolith hay microservices cho EdTech.

## 2. Template chi tiết cho 1 landing page dịch vụ

1. **Hero** — nêu rõ vấn đề và đối tượng khách hàng.
2. **Problem** — khách hàng đang gặp khó khăn gì trong giáo dục/vận hành/dữ liệu.
3. **Solution Overview** — giải pháp của công ty ở mức ngắn gọn.
4. **Core Features** — 6–10 tính năng chính.
5. **Architecture / Technical Approach** — cách xây để scale, bảo mật, dễ tích hợp.
6. **Use Cases** — trung tâm luyện thi, trường học, startup EdTech, doanh nghiệp đào tạo.
7. **Related Products** — Exam Engine, AI Learning Engine, Analytics Platform nếu liên quan.
8. **Related Insights** — 3–6 bài blog hỗ trợ SEO.
9. **FAQ** — 5–8 câu hỏi thường gặp.
10. **CTA** — Đặt lịch tư vấn hoặc gửi yêu cầu dự án.

> Component Nuxt nên build 1 lần dạng `LandingSolutionTemplate.vue` nhận props/data từ API `GET /api/solutions/{slug}`, tái dùng cho cả 7 trang — tránh 7 page riêng lẻ hard-code.

## 3. Trang Products — `/products`

Tách biệt với Solutions: Solutions là dịch vụ bán cho khách hàng, Products là tài sản công nghệ/IP có thể tái sử dụng, demo, hoặc bán dưới dạng license/API về sau.

| Product / Module | Vai trò | Giai đoạn |
|---|---|---|
| TopThi | Living lab và case study chính cho năng lực Exam + AI Learning | Có thể đưa ngay |
| Exam Engine | Module tạo đề, làm bài, chấm điểm, phân tích kết quả | MVP 3–6 tháng |
| Question Bank Engine | Quản lý câu hỏi, tag, difficulty, skill, import/export | MVP 3–6 tháng |
| Learning Analytics Platform | Dashboard hành vi học tập, tiến độ, rủi ro bỏ học | 6–12 tháng |
| AI Learning Engine | Gợi ý học, phát hiện điểm yếu, cá nhân hóa lộ trình | 9–18 tháng |
| Knowledge Graph Engine | Mapping kiến thức, prerequisite, skill dependency | 12–18 tháng |

## 4. SEO và tracking cần có ngay từ đầu

- Mỗi page/post có `meta title`, `meta description`, `og:image`, `canonical URL`.
- Tự sinh `sitemap.xml` cho pages, solutions, products, posts, research, case studies — **theo từng locale** (xem file i18n cho cấu trúc sitemap index đa ngôn ngữ + `hreflang`).
- Schema.org: `Organization`, `Article`, `BreadcrumbList`, `FAQPage` cho landing page có FAQ.
- Tối ưu Core Web Vitals: ảnh webp/avif, lazy load, cache header, SSR/SSG.
- Tracking CTA: click Book Meeting, submit form, click email/phone, scroll depth bài viết.
- Phân loại lead theo nhu cầu: LMS, Exam, AI, Analytics, Consulting.

## 5. Cấu trúc thư mục Nuxt (đề xuất)

```
pages/
  index.vue                      → Home
  solutions/index.vue            → /solutions (listing)
  solutions/[slug].vue           → dùng LandingSolutionTemplate
  products/index.vue
  products/[slug].vue
  technology.vue
  research/index.vue
  research/[slug].vue
  case-studies/index.vue
  case-studies/[slug].vue
  insights/index.vue             → blog listing (+ category/tag filter)
  insights/[slug].vue            → blog detail
  about.vue
  contact.vue
components/
  landing/LandingSolutionTemplate.vue
  home/Hero.vue, WhoWeHelp.vue, SolutionsGrid.vue, TechCapability.vue, ResearchLab.vue, CaseStudiesPreview.vue, LatestInsights.vue, FinalCta.vue
  shared/LocaleSwitcher.vue, CtaButton.vue, SeoHead.vue
composables/
  useApi.ts, useSolutions.ts, usePosts.ts, useLeadForm.ts
```

## 6. Thứ tự xây dựng frontend (tham chiếu roadmap đầy đủ ở file 06)

1. Layout, routing, Home (tĩnh/skeleton), Solutions listing tĩnh, Contact form.
2. Blog listing/detail, SEO meta component, `sitemap.xml`, `robots.txt`.
3. Solutions dynamic (theo template ở mục 2), Products, Case Studies.
4. Research, search (kết nối Meilisearch), related posts, newsletter signup.
5. Performance tuning, **i18n đầy đủ (VI/EN)**, analytics events.
