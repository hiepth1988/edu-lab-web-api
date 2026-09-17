# Checklist chỉnh sửa Menu & IA — XO Edu Lab (bản 2, đã đối chiếu thực tế 17/09/2026)

Website: https://edulab.xotech.space/
Bản tương tác (tick trạng thái, đồng bộ cho cả team): https://claude.ai/artifact/1xpE8aEcUTEvSyvPr2mFDe

> File cũ (`xo-edu-lab-ia-checklist.md`) đã lỗi thời — mọi checkbox trong đó vẫn để trống dù nhiều việc đã được làm trên site thật. File này thay thế nó, sau khi tôi mở lại từng trang trên site để xác minh trực tiếp (không suy đoán) việc nào đã xong, việc nào chưa.

> **Cập nhật 17/09/2026 (sau khi sửa code):** Nội dung site chạy qua CMS admin (dữ liệu DB), các file Seeder (`SolutionsSeeder.php`, `ProductsSeeder.php`...) chỉ là nguồn cho fresh install. Các việc #1, #2, #3 (một phần), #6 đã được sửa trong Seeder + code liên quan (model, controller, migration, frontend) — **cần chạy migrate + re-seed (hoặc cập nhật thủ công qua admin CMS) để áp dụng lên site production**. Chi tiết migrate ở cuối file.

---

## Đã xong — xác nhận trực tiếp trên site (10 việc)

- [x] **Đổi thứ tự menu** — Dự án đã đứng ngay sau Giải pháp (Giải pháp → Dự án → Sản phẩm & Công nghệ → Đối tượng → Insights).
- [x] **Cross-link TopThi** — mục "TopThi" trong mega menu Sản phẩm & Công nghệ nay trỏ thẳng `/our-work/topthi`.
- [x] **Nối Online Exam Platform ↔ Exam Engine/Question Bank Engine** — landing đã có đoạn giải thích + link chéo sang cả hai Core Engine.
- [x] **Gộp khung năng lực trùng nhau trên trang chủ** — khối mở đầu đã viết lại theo pain point (chưa rõ ai trả tiền / bỏ học giữa chừng / công cụ rời rạc), không còn liệt kê lại framework 3 mục trùng với khối Capabilities.
- [x] **Bỏ "AI recommendation" khỏi AI Education** — đã bỏ khỏi Tính năng chính, thay bằng link "Adaptive Learning →".
- [x] **Làm rõ ranh giới AI tutor ↔ Adaptive Learning** — cả hai trang đều có đoạn giải thích quan hệ + FAQ riêng nói rõ "không phải hai hệ thống recommendation tách biệt".
- [x] **Chốt vị trí Research** — chọn phương án giữ Research là section riêng trên trang chủ, và `/research` đã có nội dung thật (3 bài), không còn là trang rỗng.
- [x] **QA link "Khám phá Research →"** — trỏ đúng `/research`, có nội dung.
- [x] **Nối Learning Analytics như bước đi trước** — cả 3 trang (AI Education, Adaptive Learning, Learning Analytics) đã cross-link hai chiều + giải thích thứ tự triển khai.
- [x] **FAQ riêng cho từng trang Nâng cấp** — AI Education, Adaptive Learning, Learning Analytics đều đã có FAQ riêng, không còn dùng chung 2 câu template cũ.

---

## Đã sửa trong code, chờ deploy/seed lên site (5 việc)

### 1. Trùng tên "Learning Analytics" (Solutions) ↔ "Learning Analytics Platform" (Core Engines)

- [x] **Vị trí:** `/solutions/learning-analytics` + `/products/learning-analytics-platform`
- **Đã làm:** Thêm `architecture_note` giải thích quan hệ hai bên + cross-link trỏ sang `/products/learning-analytics-platform` trong `SolutionsSeeder.php`, tương tự cách đã làm với Online Exam Platform ↔ Exam Engine.
- **Ưu tiên:** Cao

### 2. Thêm tính năng "Cảnh báo học viên có nguy cơ" trên Learning Analytics

- [x] **Vị trí:** `/solutions/learning-analytics` — khối Tính năng chính
- **Đã làm:** Thêm feature "Cảnh báo học viên có nguy cơ" (EN: "At-risk learner alerts") vào danh sách features trong `SolutionsSeeder.php`.
- **Ưu tiên:** Thấp

### 3. Bổ sung Kiến trúc, Niềm tin, Use Cases, Related Products cho Learning Analytics

- [x] **Vị trí:** `/solutions/learning-analytics`
- **Đã làm:** Thêm đủ `architecture_approach` (3 bước), `trust_safety` (2 câu hỏi), `use_cases` (4 đối tượng) theo đúng khung đã dùng cho AI Education. Related Products (→ Learning Analytics Platform) cũng đã nối qua tính năng mới ở mục #6 bên dưới.
- **Ưu tiên:** Thấp

### 6. Related Products & Related Insights cho AI Education (và Learning Analytics)

- [x] **Vị trí:** `/solutions/ai-education`, `/solutions/learning-analytics`
- **Đã làm:** Đây là tính năng mới hoàn toàn (Solution chưa từng có quan hệ tới Product/Post) — đã thêm:
  - Migration `solution_related_products`, `solution_related_posts` (many-to-many, giống pattern `project_related_projects` đã có).
  - Quan hệ `relatedProducts()` / `relatedInsights()` trong `app/Models/Solution.php`.
  - API công khai (`Api/SolutionController.php`) trả về `related_products` / `related_insights`.
  - API admin (`Api/Admin/SolutionController.php`) nhận `related_product_ids` / `related_post_ids` để CRUD qua request (**chưa có UI dropdown chọn trong admin Vue** — nếu cần chỉnh qua giao diện thay vì gọi API trực tiếp, cần làm thêm form field trong `SolutionFormView.vue`).
  - Seed cross-link mặc định trong `ProductsSeeder.php` (`syncRelatedProducts()`): AI Education → AI Learning Engine, Knowledge Graph Engine; Learning Analytics → Learning Analytics Platform.
  - Frontend: khối "Sản phẩm liên quan" + "Bài viết liên quan" trong `solutions/[slug].vue`, đặt trước FAQ.
- **Insights liên quan cho AI Education:** để trống — 4 bài blog AI-specific trong bản nháp (`ai-education-content-draft.md`) chưa được viết/xuất bản trong `BlogSeeder.php`, nên chưa có gì để link tới. UI đã sẵn sàng hiển thị khi bài viết ra mắt.
- **Ưu tiên:** Thấp

---

## Cần bạn quyết định thêm hoặc rà soát (đã xử lý, không cần sửa code)

### 4. Rà nội dung 5 landing page dưới menu "Đối tượng"

- [x] **Vị trí:** 5 trang: Giáo viên độc lập, Trung tâm, Trường học, EdTech Startup, Doanh nghiệp
- **Kết quả rà soát:** Đã đọc lại `AudiencesSeeder.php` — mỗi trang có `pain_points` và `how_we_help` viết riêng theo đúng nỗi đau của nhóm đó, không lặp lại danh sách tính năng kỹ thuật giữa các trang. Không cần sửa.
- **Ưu tiên:** Thấp

### 5. Giảm thuật ngữ kỹ thuật ở tầng điều hướng cấp cao

- [x] **Vị trí:** Mega menu Sản phẩm & Công nghệ, các trang landing cấp 1
- **Quyết định:** Giữ nguyên tên kỹ thuật (Exam Engine, Question Bank Engine, Learning Analytics Platform...) — bạn đã xác nhận đây là mục "Sản phẩm & Công nghệ" hướng tới technical buyer (CTO...), không phải landing bán hàng, nên thuật ngữ kỹ thuật là phù hợp.
- **Ưu tiên:** Thấp

---

## Còn chưa làm — cần thông tin từ XO (1 việc)

### 7. Dữ liệu học viên được xử lý ra sao? (câu hỏi còn bỏ trống)

- [ ] **Vị trí:** `/solutions/ai-education` — FAQ / Niềm tin & An toàn
- **Vấn đề:** Đúng như lưu ý trong bản nháp — câu hỏi này cần câu trả lời thật từ XO, hiện tại trang chưa có (team đã khôn ngoan không tự bịa câu trả lời).
- **Việc cần làm:** XO cung cấp thông tin thật (lưu ở đâu, có dùng để huấn luyện lại model không, thời gian lưu trữ) để viết câu trả lời.
- **Ưu tiên:** Trung bình — đây thường là câu hỏi đầu tiên của khách B2B trước khi demo.

---

## Cách áp dụng các thay đổi code lên site (edu-lab-web-api)

Các thay đổi trên nằm trong Seeder + Model + Controller + migration mới, **chưa tự động lên site production** vì:
- Seeder có guard `if (X::exists()) return;` — chỉ chạy nội dung mẫu khi bảng rỗng (fresh install).
- Site thật lấy nội dung từ database đã seed từ trước, quản lý qua admin CMS.

Để áp dụng:
1. Chạy migration mới: `php artisan migrate` (tạo 2 bảng `solution_related_products`, `solution_related_posts`).
2. Với nội dung text (việc #1, #2, #3): cập nhật thủ công qua admin CMS (Solutions → Learning Analytics) theo đúng nội dung mới trong `SolutionsSeeder.php`, HOẶC xóa dữ liệu solutions hiện tại trên môi trường test/staging rồi chạy lại `php artisan db:seed --class=SolutionsSeeder` (chỉ nên làm trên môi trường chưa có dữ liệu người dùng chỉnh tay).
3. Với cross-link Related Products (việc #6): chạy `php artisan db:seed --class=ProductsSeeder` — an toàn để chạy lại trên DB đã có Products, vì phần sync cross-link được tách riêng và dùng `sync()` (không tạo trùng, không xóa Products hiện có).

---

## Tóm tắt

**14/17 việc đã xong hoặc đã xử lý** (10 việc gốc đã xong trước đó + 4 việc sửa trong phiên này: #1, #2, #3, #4/#5 rà soát xong không cần sửa, #6 xây tính năng mới). Còn lại **việc #7** cần XO cung cấp thông tin thật về xử lý dữ liệu học viên trước khi viết FAQ — không có gì chặn việc public/tìm dự án.
