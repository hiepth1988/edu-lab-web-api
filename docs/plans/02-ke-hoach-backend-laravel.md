# 02. Kế hoạch Backend — Laravel 12

> Backend là API-only, phục vụ cả Public site (Nuxt) và Admin (Vue 3). Toàn bộ bảng nội dung được thiết kế **đa ngôn ngữ (i18n) ngay từ đầu** bằng mô hình "translation table" — xem giải thích lựa chọn ở [`05-ke-hoach-da-ngon-ngu-i18n.md`](./05-ke-hoach-da-ngon-ngu-i18n.md#2-thiết-kế-database).

## 1. Danh sách module

| Module | Chức năng chính | Bảng dữ liệu |
|---|---|---|
| Auth & Role | Đăng nhập admin, phân quyền editor/admin | `users`, `roles`, `permissions` |
| Locales | Danh sách ngôn ngữ hệ thống hỗ trợ, ngôn ngữ mặc định | `locales` |
| Pages | Trang tĩnh: Home, About, Contact, Technology | `pages`, `page_translations`, `page_sections`, `page_section_translations` |
| Solutions | Quản lý landing page dịch vụ | `solutions`, `solution_translations`, `solution_features`, `solution_feature_translations`, `solution_faqs`, `solution_faq_translations` |
| Products | Quản lý sản phẩm/module/IP | `products`, `product_translations`, `product_features`, `product_feature_translations` |
| Insights/Blog | Bài viết, category, tag, SEO meta | `posts`, `post_translations`, `categories`, `category_translations`, `tags`, `tag_translations`, `post_tag` |
| Research | Bài nghiên cứu, series, framework | `research_posts`, `research_post_translations`, `research_topics`, `research_topic_translations` |
| Case Studies | Dự án, vấn đề, giải pháp, kết quả | `case_studies`, `case_study_translations`, `case_metrics` |
| Lead Forms | Lưu form liên hệ, phân loại nhu cầu | `leads`, `lead_notes` |
| Media Library | Upload và quản lý ảnh/tài liệu | `media_files` |
| SEO | Meta title, description, canonical, schema | tích hợp trực tiếp vào từng bảng `*_translations` (xem file i18n) |
| Newsletter | Email đăng ký nhận bài | `subscribers` |
| Settings | Thông tin công ty, social links, CTA | `settings`, `setting_translations` |

## 2. Ghi chú thiết kế bảng (mẫu chung)

Mỗi entity nội dung tách thành 2 bảng:

- **Bảng gốc** (`solutions`, `posts`, ...): dữ liệu **không phụ thuộc ngôn ngữ** — `id`, `status`, `published_at`, `author_id`, `featured_image`, `sort_order`, các khóa ngoại (category, product liên quan), timestamps.
- **Bảng translation** (`solution_translations`, `post_translations`, ...): `id`, `{entity}_id`, `locale`, `slug`, `title`, `excerpt/summary`, `content` (rich text/markdown), `meta_title`, `meta_description`, `og_image`, timestamps.
  - `UNIQUE(entity_id, locale)` — mỗi bản dịch chỉ có 1 bản/ngôn ngữ.
  - `UNIQUE(locale, slug)` — slug là duy nhất **trong phạm vi 1 locale** (cho phép slug tiếng Anh khác slug tiếng Việt của cùng 1 bài, tối ưu SEO).

Ví dụ cụ thể cho `posts`:

```
posts
  id, category_id, status (draft/published), published_at,
  featured_image, is_featured, view_count, created_at, updated_at

post_translations
  id, post_id, locale (vi|en), slug, title, excerpt, content_html,
  meta_title, meta_description, og_image, created_at, updated_at
  UNIQUE(post_id, locale)
  UNIQUE(locale, slug)

tags / tag_translations (tương tự: name, slug theo locale)
post_tag (post_id, tag_id) — không cần locale, tag liên kết ở cấp bài viết gốc
```

Áp dụng tương tự cho `solutions`, `products`, `pages`, `page_sections`, `research_posts`, `case_studies`.

> `case_metrics` (số liệu case study, ví dụ "giảm 40% thời gian chấm bài") thường là số + đơn vị, có thể cần dịch label → thêm `case_metric_translations` nếu label hiển thị bằng chữ.

## 3. Auth & Role

- Laravel Sanctum cho admin (SPA token-based).
- Roles: `admin` (full quyền), `editor` (CRUD content, không đổi settings/users).
- Middleware `CheckPermission` theo action (create/update/publish/delete).

## 4. API endpoint gợi ý (mở rộng theo locale)

Tất cả endpoint public đọc dữ liệu đều nhận tham số `locale` (query param `?locale=vi|en`, mặc định lấy theo config `app.locale` nếu không truyền — middleware `SetLocale` xử lý, chi tiết ở file i18n).

| Nhóm | Endpoint mẫu |
|---|---|
| Locales | `GET /api/locales` (danh sách ngôn ngữ + locale mặc định, dùng cho language switcher) |
| Public | `GET /api/pages/{slug}?locale=vi`, `GET /api/home?locale=vi` |
| Solutions | `GET /api/solutions?locale=vi`, `GET /api/solutions/{slug}?locale=vi` |
| Products | `GET /api/products?locale=vi`, `GET /api/products/{slug}?locale=vi` |
| Posts | `GET /api/posts?locale=vi`, `GET /api/posts/{slug}?locale=vi`, `GET /api/categories?locale=vi`, `GET /api/tags?locale=vi` |
| Research | `GET /api/research?locale=vi`, `GET /api/research/{slug}?locale=vi` |
| Case Studies | `GET /api/case-studies?locale=vi`, `GET /api/case-studies/{slug}?locale=vi` |
| Lead | `POST /api/leads` (kèm field `locale` để biết khách hàng liên hệ ở ngôn ngữ nào → phản hồi đúng ngôn ngữ), `POST /api/newsletter/subscribe` |
| Admin Auth | `POST /api/admin/login`, `POST /api/admin/logout`, `GET /api/admin/me` |
| Admin CRUD | `CRUD /api/admin/posts`, `/solutions`, `/products`, `/case-studies`, `/media`, `/settings` — payload CRUD nhận **object translations theo locale**, ví dụ: `{ "status": "published", "translations": { "vi": {...}, "en": {...} } }` |
| Sitemap/SEO | `GET /api/sitemap.xml` (hoặc generate tĩnh, xem file i18n cho sitemap đa locale) |

## 5. Các module khác

- **Lead Forms:** `leads` lưu `name, email, phone, company, need (LMS/Exam/AI/Analytics/Consulting), message, locale, source_url, created_at`; `lead_notes` cho sales note lại follow-up.
- **Media Library:** `media_files` (path, mime, size, alt_text — `alt_text` nên là translatable vì ảnh hỗ trợ SEO theo ngôn ngữ → cân nhắc `media_file_translations` cho `alt_text`/`caption`).
- **Settings:** thông tin công ty, social links, CTA — tách phần translatable (tagline, CTA text) vào `setting_translations`, phần không đổi (email, phone, social URLs) giữ ở `settings`.
- **Search indexing:** khi publish/update translation → dispatch job đồng bộ record đó (kèm `locale`) lên Meilisearch/Elasticsearch.
- **Sitemap/cache invalidation:** khi publish nội dung → queue job regenerate sitemap.xml theo locale bị ảnh hưởng + clear cache Redis liên quan.

## 6. Thứ tự xây dựng backend (tham chiếu roadmap MVP đầy đủ ở file 06)

1. `locales`, `users/roles`, Auth API.
2. `pages` + `page_translations`, API contact form (`leads`).
3. `posts` + `post_translations` + `categories`/`tags` (CRUD + public API + SEO fields + sitemap).
4. `solutions`, `products`, `case_studies` (+ translations) — CRUD + public API.
5. `research_posts`, `subscribers`, `settings`.
6. Search indexing, cache, queue, security hardening (rate limit, validation, backup), audit log.
