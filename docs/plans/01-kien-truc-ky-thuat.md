# 01. Kiến trúc kỹ thuật tổng thể

> Xem tổng quan tại [`00-tong-quan-dinh-vi-sitemap.md`](./00-tong-quan-dinh-vi-sitemap.md). File này mô tả kiến trúc hệ thống; thiết kế đa ngôn ngữ chi tiết ở [`05-ke-hoach-da-ngon-ngu-i18n.md`](./05-ke-hoach-da-ngon-ngu-i18n.md).

## 1. Bảng kiến trúc theo layer

| Layer | Công nghệ | Ghi chú |
|---|---|---|
| Public Website | NuxtJS 3, Vue 3, TypeScript, Tailwind CSS, SSR/SSG | SEO mạnh, tốc độ cao, dễ mở rộng content hub. **+ `@nuxtjs/i18n`** cho đa ngôn ngữ (chi tiết ở file i18n). |
| Admin CMS | Vue 3, Vite, Pinia, Vue Router | Quản lý bài viết, dịch vụ, case study, lead, media. Có tab/switcher theo locale khi chỉnh nội dung đa ngôn ngữ. |
| Backend API | Laravel 12, PHP 8.3+, Sanctum/Passport, Queue, Scheduler | REST API hoặc kết hợp GraphQL nếu cần. Middleware xác định locale theo request. |
| Database | MySQL 8/PostgreSQL | Khởi đầu MySQL nếu đồng bộ với kinh nghiệm hiện tại. Các bảng nội dung dùng cột JSON translatable (xem file i18n). |
| Search | Meilisearch hoặc Elasticsearch | Dùng cho blog, case study, research; index riêng theo locale (`posts_vi`, `posts_en`, hoặc field `locale` filterable). |
| Cache/Queue | Redis, Laravel Queue | Cache bài viết, job gửi email, indexing search. Cache key luôn có locale trong thành phần key. |
| Storage | S3-compatible hoặc local storage giai đoạn đầu | Ảnh blog, tài liệu, file case study. |
| Deployment | Nginx, PHP-FPM, PM2/Nitro server, Docker tùy giai đoạn | Ưu tiên đơn giản lúc đầu, chuẩn hóa CI/CD sau. |
| Analytics | GA4, Search Console, server-side event log | Theo dõi CTA, form submit, bài viết tạo lead — tách theo locale để so sánh hiệu quả từng thị trường. |

## 2. Nguyên tắc kiến trúc

- **API-first:** Backend Laravel expose REST API thuần, Nuxt và Admin đều là client của API này (không render Blade).
- **SSR/SSG cho public site:** ưu tiên SSG (generate tĩnh) cho page ít đổi (Home, About, Solutions skeleton); SSR/ISR cho Blog/Insights vì nội dung thay đổi thường xuyên.
- **Content-driven:** hầu hết nội dung (pages, solutions, products, posts, research, case studies) đều quản lý qua Admin CMS, không hard-code trong Nuxt — để non-dev cũng có thể cập nhật.
- **Đa ngôn ngữ là thuộc tính hạ tầng, không phải tính năng phụ:** mọi bảng nội dung, mọi API, mọi route Nuxt cần tính đến `locale` ngay từ schema/API contract đầu tiên (MVP 1), tránh phải migrate dữ liệu sau. Chi tiết: [file i18n](./05-ke-hoach-da-ngon-ngu-i18n.md).
- **Tách Solutions (dịch vụ bán) khỏi Products (IP/tài sản công nghệ)** — hai domain model riêng, không dùng chung 1 bảng dù UI có thể giống nhau.

## 3. Sơ đồ luồng dữ liệu (tổng quát)

```
Admin (Vue 3) ──CRUD (kèm locale)──▶ Laravel API ──▶ MySQL/PostgreSQL
                                        │
                                        ├──▶ Redis (cache theo key có locale)
                                        ├──▶ Search index (Meilisearch, field locale)
                                        └──▶ Queue (email, reindex, sitemap regen)

Nuxt 3 (SSR/SSG) ──GET (?locale=vi|en hoặc /en/...)──▶ Laravel API (public, read-only, cached)
```

## 4. Yêu cầu phi chức năng (NFR)

- **SEO:** SSR/SSG, sitemap.xml đa locale, schema.org, Core Web Vitals tốt (ảnh webp/avif, lazy load).
- **Bảo mật:** Sanctum cho admin session/token, rate limit cho API public (leads, newsletter), validate input nghiêm ngặt ở form liên hệ.
- **Hiệu năng:** cache page/API response, CDN cho asset tĩnh, queue cho việc nặng (gửi mail, reindex search).
- **Khả năng mở rộng:** thiết kế API/DB để dễ thêm locale thứ 3 (ví dụ tiếng Nhật/Hàn nếu công ty mở rộng thị trường) mà không phải đổi schema.
