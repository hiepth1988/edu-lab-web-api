# 04. Kế hoạch Admin CMS — Vue 3

> Admin là SPA riêng (Vue 3 + Vite + Pinia + Vue Router), gọi cùng Laravel API ở [`02-ke-hoach-backend-laravel.md`](./02-ke-hoach-backend-laravel.md). UX quản trị đa ngôn ngữ chi tiết ở [`05-ke-hoach-da-ngon-ngu-i18n.md`](./05-ke-hoach-da-ngon-ngu-i18n.md#4-admin-cms-ux-đa-ngôn-ngữ).

## 1. Chức năng theo module

| Module | Màn hình chính |
|---|---|
| Auth | Login, quên mật khẩu, session/token via Sanctum |
| Dashboard | Tổng quan: số lead mới, bài viết chờ publish, traffic nhanh (nếu tích hợp GA4 API) |
| Pages | Editor cho Home/About/Contact/Technology theo section (page builder đơn giản dạng block) |
| Solutions | List + form CRUD (features, FAQs là repeater field) |
| Products | List + form CRUD (features là repeater field) |
| Insights/Blog | List, editor (rich text/markdown), category/tag manager, SEO panel |
| Research | List + editor tương tự Blog, gắn `research_topics` |
| Case Studies | List + editor (problem/solution/result + `case_metrics` repeater) |
| Leads | Inbox dạng list, filter theo `need`, đổi trạng thái, ghi `lead_notes` |
| Media Library | Grid upload, tìm kiếm, chọn ảnh cho các module khác |
| Settings | Thông tin công ty, social links, CTA mặc định, cấu hình locale mặc định |
| Users & Roles | Quản lý user admin/editor, phân quyền |

## 2. Nguyên tắc UI/UX quản trị

- **Form theo tab locale:** mỗi entity có bản dịch (Solutions, Products, Posts, Research, Case Studies, Pages, Settings) hiển thị dạng tab `🇻🇳 Tiếng Việt | 🇬🇧 English` trên cùng 1 form — không tạo 2 trang riêng.
- **Trạng thái dịch:** badge nhỏ cạnh mỗi tab locale: "Đã dịch" / "Thiếu bản dịch" / "Nháp" — để editor biết cần bổ sung ngôn ngữ nào (chi tiết logic ở file i18n).
- **Slug tự sinh theo locale:** khi nhập `title` ở tab nào, tự generate `slug` cho đúng locale đó (không dùng chung 1 slug cho 2 ngôn ngữ).
- **Publish theo entity, không theo locale:** field `status` (draft/published) áp dụng cho toàn bộ record; nếu 1 locale chưa có bản dịch thì frontend fallback (xem file i18n) — admin không cần publish riêng từng ngôn ngữ trong giai đoạn đầu (đơn giản hóa vận hành).
- **Repeater field** (features, FAQs, metrics) cũng translatable theo cùng cơ chế tab.
- **SEO panel** dùng chung component, hiển thị preview Google snippet theo từng locale.

## 3. Stack & cấu trúc thư mục (đề xuất)

```
src/
  api/            → axios instance + service theo module (solutions.ts, posts.ts, leads.ts...)
  stores/         → Pinia: auth, ui, locales
  views/          → LoginView, DashboardView, solutions/List.vue, solutions/Form.vue, ...
  components/
    form/LocaleTabs.vue        → tab switcher tái dùng cho mọi form translatable
    form/RepeaterField.vue     → cho features/FAQs/metrics
    media/MediaPicker.vue
  router/         → route guard theo permission
```

## 4. Thứ tự xây dựng admin (tham chiếu roadmap đầy đủ ở file 06)

1. Login, dashboard đơn giản.
2. CRUD posts/categories/tags (kèm `LocaleTabs` — build sớm để tái dùng cho các module sau).
3. CRUD solutions/products/case studies, form lead management.
4. CRUD research, subscribers, settings.
5. Role/permission, audit log.
