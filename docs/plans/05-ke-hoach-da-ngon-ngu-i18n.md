# 05. Kế hoạch đa ngôn ngữ (i18n) — mảng bổ sung so với spec gốc

> **Ghi chú:** File `education_tech_company_website_plan.docx` gốc chỉ nhắc đến đa ngôn ngữ đúng 1 lần, ở dòng MVP 5: *"Performance, i18n EN/VN nếu cần, analytics events"* — tức xử lý cuối cùng, kiểu tùy chọn. Vì website định vị là *Education Technology Lab* hướng tới cả khách hàng trong nước và khả năng mở rộng quốc tế, đa ngôn ngữ nên được coi là **yêu cầu hạ tầng từ đầu**, không phải tính năng thêm sau — vì thêm i18n sau khi đã có dữ liệu/API/route sẽ phải migrate lại toàn bộ nội dung và URL (rủi ro mất SEO đã tích lũy). File này thiết kế chi tiết để đưa i18n vào **MVP 1** ở mức hạ tầng (schema, API contract, routing), và triển khai nội dung tiếng Anh dần theo roadmap.

## 1. Mục tiêu & phạm vi ngôn ngữ

- **Ngôn ngữ giai đoạn 1:** Tiếng Việt (`vi` — mặc định) + Tiếng Anh (`en`).
- **Thiết kế mở:** hạ tầng phải cho phép thêm locale thứ 3+ (ví dụ `ja`, `ko`) chỉ bằng cách thêm record vào bảng `locales`, không cần đổi schema hay code route.
- **Không dịch máy tự động cho nội dung marketing/blog** — chỉ dùng máy dịch làm bản nháp, biên tập lại bằng người (giữ tone chuyên gia như mục Design Direction). Riêng UI string (nút, label) có thể dịch tay 1 lần vì số lượng ít.
- **Không bắt buộc song ngữ 100% ngay từ ngày 1** — chấp nhận EN có ít nội dung hơn VI trong giai đoạn đầu, miễn là cơ chế fallback rõ ràng (mục 5).

## 2. Thiết kế database

### Lựa chọn: bảng translation riêng, không dùng JSON column

| Phương án | Ưu điểm | Nhược điểm | Kết luận |
|---|---|---|---|
| A. Cột JSON trên bảng gốc (kiểu Spatie `laravel-translatable`) | Ít bảng, code đơn giản | Khó tạo `UNIQUE` constraint cho slug theo locale ở tầng DB, khó filter/search hiệu quả bằng SQL thô, khó mở rộng field | Không chọn |
| B. Bảng `*_translations` riêng (1 dòng/locale) | `UNIQUE(entity_id, locale)` và `UNIQUE(locale, slug)` ở tầng DB, dễ query/join, dễ đánh index Meilisearch theo locale, dễ thêm locale mới | Nhiều bảng hơn | **Chọn phương án B** |

Bảng `locales`:

```
locales
  id, code (vi, en), name (Tiếng Việt, English), is_default (bool),
  is_active (bool), sort_order
```

Mọi entity nội dung (`pages`, `solutions`, `products`, `posts`, `categories`, `tags`, `research_posts`, `case_studies`) đều có bảng `_translations` tương ứng theo mẫu ở [`02-ke-hoach-backend-laravel.md`](./02-ke-hoach-backend-laravel.md#2-ghi-chú-thiết-kế-bảng-mẫu-chung).

### Slug theo locale

- Mỗi locale có slug riêng, ví dụ:
  - VI: `/solutions/nen-tang-thi-truc-tuyen`
  - EN: `/en/solutions/online-exam-platform`
- Cần bảng map slug ↔ entity ↔ locale (chính là `*_translations`) để Nuxt resolve đúng route khi người dùng chuyển ngôn ngữ (không redirect về homepage khi switch locale).

## 3. Backend (Laravel)

### 3.1. Xác định locale của request

- Middleware `SetLocaleFromRequest`: đọc query param `?locale=vi|en` (Nuxt luôn gửi tham số này khi gọi API) → set `App::setLocale()` → nếu không có/không hợp lệ, fallback về locale mặc định trong bảng `locales` (`is_default = true`).
- Response luôn trả về đúng 1 locale được yêu cầu, **không** trả toàn bộ object translations cho public API (để nhẹ payload); admin API thì trả full `translations` object để hiển thị tab.

### 3.2. Fallback khi thiếu bản dịch

Quy tắc rõ ràng để tránh trang trắng:

- **Danh sách (listing API)**: chỉ trả về item có bản dịch ở locale được yêu cầu **và** `status = published` cho locale đó → EN listing sẽ tự động ít hơn VI listing, đúng thực tế.
- **Trang chi tiết (detail API) theo slug không tồn tại ở locale đó**: trả `404` → Nuxt hiển thị trang "Nội dung này chưa có phiên bản tiếng Anh, xem bản tiếng Việt" kèm link, **không** tự âm thầm hiển thị nội dung tiếng Việt trong trang gắn `lang="en"` (tránh sai schema.org/SEO).
- **Page tĩnh cốt lõi (Home, About, Contact, Solutions overview)**: bắt buộc phải có cả 2 locale trước khi launch — đây là các trang không được fallback.

### 3.3. Sitemap & cache

- Sitemap tách theo locale: `sitemap-vi.xml`, `sitemap-en.xml`, gộp trong `sitemap.xml` (sitemap index). Chỉ đưa URL đã `published` cho locale đó vào sitemap — tự động loại bỏ nội dung chưa dịch.
- Cache key Redis luôn có locale: `solutions:{locale}:{slug}`, tránh cache lẫn giữa 2 ngôn ngữ.
- Search index (Meilisearch): field `locale` là filterable attribute; Nuxt luôn filter theo locale hiện tại khi search.

## 4. Frontend (Nuxt 3)

### 4.1. Module & chiến lược URL

- Dùng `@nuxtjs/i18n` (module chính thức cho Nuxt 3), strategy **`prefix_except_default`**:
  - VI (mặc định): không prefix — `/solutions/...`
  - EN: prefix `/en/...` — `/en/solutions/...`
- Cấu hình `locales` trong `nuxt.config.ts` khớp với bảng `locales` ở backend (đồng bộ danh sách, không hard-code trùng lặp — lý tưởng là build-time fetch từ `GET /api/locales`, tối thiểu là giữ đồng bộ tay khi thêm locale mới).

### 4.2. Hai lớp nội dung cần dịch, xử lý khác nhau

1. **UI strings** (nút, label menu, footer, thông báo lỗi form...) → file JSON tĩnh trong Nuxt (`i18n/locales/vi.json`, `i18n/locales/en.json`), dịch 1 lần, ít thay đổi.
2. **Content động** (bài viết, solutions, products...) → lấy từ API theo `locale` hiện tại của route, **không** đưa vào file JSON tĩnh của Nuxt.

### 4.3. Slug khác nhau theo locale

- Vì slug VI và EN khác nhau (mục 2), **không dùng `useSwitchLocalePath()` mặc định** (nó chỉ đổi prefix, giữ nguyên slug) — mà gọi API lấy "bản dịch tương đương" của entity hiện tại (`GET /api/solutions/{slug}?locale=vi` trả kèm field `translations_meta: { en: "online-exam-platform" }`) để `LocaleSwitcher` điều hướng đúng URL đã dịch. Nếu chưa có bản dịch → switcher disable hoặc dẫn về trang listing của locale đó (không 404 khó chịu).

### 4.4. SEO đa ngôn ngữ

- Mỗi trang render `<link rel="alternate" hreflang="vi" href=".../solutions/...">` và `hreflang="en"` + `x-default` (dùng `useHead`/`@nuxtjs/i18n` tích hợp sẵn `hreflang` tự động theo cấu hình).
- `<html lang="vi">` hoặc `lang="en"` set động theo route.
- `canonical` luôn chỉ về chính URL của locale hiện tại (không canonical chéo về locale khác).
- Schema.org `Organization`/`Article` có field `inLanguage` khớp locale.

## 5. Admin CMS — UX (tham chiếu chi tiết ở file 04)

- Form mọi entity translatable dùng `LocaleTabs.vue` — xem [`04-ke-hoach-admin-cms.md`](./04-ke-hoach-admin-cms.md#2-nguyên-tắc-uiux-quản-trị).
- Badge trạng thái dịch tính bằng: `translations.count() === active_locales.count()` → "Đầy đủ"; `> 0` → "Thiếu bản dịch"; `= 0` → "Chưa dịch".
- Dashboard có widget "Nội dung thiếu bản dịch EN" (danh sách posts/solutions còn thiếu) để team biết ưu tiên dịch gì tiếp.

## 6. Quy trình dịch nội dung (vận hành, không phải code)

1. Viết bản gốc tiếng Việt, publish trước.
2. Đưa qua dịch nháp (máy dịch/cộng tác viên) → editor tiếng Anh trong công ty biên tập lại giọng văn (tránh dịch cứng, đảm bảo đúng thuật ngữ EdTech: "adaptive learning", "knowledge graph"... giữ nguyên tiếng Anh cả ở bản VI nếu là thuật ngữ chuyên ngành phổ biến).
3. Ưu tiên dịch EN theo thứ tự: **Home → About → Contact → Solutions overview (bắt buộc trước launch)**, sau đó mới tới từng landing page dịch vụ, cuối cùng là blog/insights (có thể lệch sau bản VI vài tuần, xem roadmap 90 ngày ở file 06).

## 7. Checklist kỹ thuật riêng cho i18n

- [ ] Bảng `locales` + seed `vi` (default), `en`.
- [ ] Toàn bộ bảng nội dung có `_translations` với `UNIQUE(entity_id, locale)` + `UNIQUE(locale, slug)`.
- [ ] Middleware `SetLocaleFromRequest` ở Laravel.
- [ ] `@nuxtjs/i18n` cấu hình `prefix_except_default`, load `locales` UI JSON.
- [ ] `LocaleSwitcher` điều hướng đúng slug tương đương (không chỉ đổi prefix).
- [ ] `hreflang` + `lang` attribute + canonical đúng theo từng route.
- [ ] Sitemap tách theo locale, chỉ chứa nội dung đã publish ở locale đó.
- [ ] Cache key và search index có `locale`.
- [ ] Admin: `LocaleTabs` cho mọi form translatable + badge trạng thái dịch.
- [ ] Trang 404 riêng cho case "nội dung chưa có bản dịch" (khác 404 thường).
- [ ] Test: chuyển đổi ngôn ngữ ở mọi loại trang (home, solution, blog, 404-chưa-dịch) không bị lỗi URL hoặc lộ nội dung sai locale.
