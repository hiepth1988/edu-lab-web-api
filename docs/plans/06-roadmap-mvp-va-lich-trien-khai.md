# 06. Roadmap kỹ thuật (MVP 1–5) & Roadmap nội dung 90 ngày

> Roadmap dưới đây giữ cấu trúc MVP 1–5 của spec gốc nhưng **đưa hạ tầng i18n vào MVP 1** (thay vì để cuối ở MVP 5 như bản gốc) — lý do giải thích ở [`05-ke-hoach-da-ngon-ngu-i18n.md`](./05-ke-hoach-da-ngon-ngu-i18n.md). MVP 5 chỉ còn phần "hoàn thiện & mở rộng locale" (performance, thêm locale nếu cần, analytics).

## 1. Roadmap triển khai kỹ thuật

| Giai đoạn | Frontend NuxtJS 3 | Admin Vue 3 | Backend Laravel 12 |
|---|---|---|---|
| **MVP 1** | Layout, routing, Home, Solutions static, Contact. **Setup `@nuxtjs/i18n` (`prefix_except_default`), UI strings VI/EN.** | Login, dashboard đơn giản | Auth, API pages, API contact form. **Bảng `locales`, mọi bảng nội dung dựng kèm `_translations` ngay từ migration đầu tiên.** |
| **MVP 2** | Blog listing/detail, SEO meta, `sitemap.xml` (theo locale), `robots.txt`, `hreflang` | CRUD posts/categories/tags **kèm `LocaleTabs`** | Post API, media upload, slug theo locale, SEO fields |
| **MVP 3** | Solutions dynamic (template dùng chung), Products, Case Studies | CRUD solutions/products/case studies | API solutions/products/cases, form lead management |
| **MVP 4** | Research, search (Meilisearch, filter theo locale), related posts, newsletter | CRUD research, subscribers, settings | Search indexing, subscriber API, settings API |
| **MVP 5** | Performance tuning, **hoàn thiện song ngữ toàn site + rà soát fallback**, mở rộng locale thứ 3 nếu công ty cần, analytics events | Role/permission, audit log, widget "nội dung thiếu bản dịch" | Cache, queue, security hardening, backup |

## 2. Roadmap nội dung 90 ngày đầu

| Tuần | Nội dung nên làm | Mục tiêu | Ghi chú song ngữ |
|---|---|---|---|
| 1–2 | Hoàn thiện Home, Solutions tổng quan, Contact, About | Có website cơ bản để gửi khách hàng | **Bắt buộc có bản EN ngay** cho 4 trang này (đây là trang không được fallback — xem file i18n mục 3.2) |
| 3–4 | Landing page Online Exam Platform và LMS | Đẩy hai dịch vụ dễ bán nhất | Viết VI trước, EN có thể trễ 1 tuần nhưng nên xong trong tuần 4 |
| 5–6 | Viết 4 bài blog: Online Exam Architecture, Question Bank, LMS riêng vs Moodle, Scaling LMS | Tạo trust kỹ thuật | Blog kỹ thuật ưu tiên dịch EN sớm — đối tượng đọc kỹ thuật quốc tế nhiều hơn |
| 7–8 | Landing page AI Education và Learning Analytics | Bắt đầu định vị AI/data | Song ngữ song song với VI |
| 9–10 | Case study TopThi + 2 bài blog về adaptive testing/learning analytics | Chứng minh năng lực thực chiến | Case study nên có bản EN để dùng khi tiếp cận khách hàng/đối tác nước ngoài |
| 11–12 | Research section: Knowledge Graph, Student Behavior, Human Potential intro | Đặt nền cho định vị dài hạn | Research content có thể ưu tiên EN trước (thương hiệu học thuật quốc tế) |

## 3. Đo lường sau 90 ngày

Theo checklist ở [`00-tong-quan-dinh-vi-sitemap.md`](./00-tong-quan-dinh-vi-sitemap.md#5-checklist-công-việc-ưu-tiên), đo **tách riêng theo locale** để biết thị trường nào đang hoạt động tốt:

- Organic traffic (VI vs EN).
- Số form lead (VI vs EN, và theo nhu cầu: LMS/Exam/AI/Analytics/Consulting).
- Số cuộc hẹn tư vấn đặt được.
- Số bài đã index trên Google Search Console (theo locale).
- Top keyword (theo locale — từ khóa tiếng Việt và tiếng Anh thường khác nhau hoàn toàn, cần theo dõi riêng).
- Tỷ lệ nội dung đã có bản dịch EN / tổng nội dung VI (chỉ số nội bộ theo dõi "nợ dịch thuật").
