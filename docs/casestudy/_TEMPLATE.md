# Case Study — Mẫu thu thập thông tin

> Dùng file này làm khung để tổng hợp thông tin cho **từng** case study (copy ra
> `backend/docs/casestudy/<slug>/overview.md` rồi điền). Mục tiêu: gom đủ dữ liệu
> thô trước, sau đó mới soạn nội dung theo đúng cấu trúc CMS (`project_translations`
> + các bảng con) để nạp vào `ProjectsSeeder.php` hoặc nhập tay qua admin.
>
> **Nguyên tắc bắt buộc** (đã áp dụng cho tất cả case study hiện có):
> 1. **Không bịa số liệu.** Chỉ điền các trường số liệu (hero_stats, scale_stats,
>    results, metrics) khi có nguồn xác nhận (tài liệu gốc hoặc chủ dự án xác nhận
>    trực tiếp). Không có số → để trống, không suy đoán/làm tròn cho "có vẻ hợp lý".
> 2. **Xin phép trước khi public** nếu case study nêu tên tổ chức/khách hàng thật,
>    đặc biệt khi có dữ liệu nhạy cảm (thông tin người dùng cuối, dữ liệu nội bộ
>    khách hàng...). Nếu chưa xin được phép → `status = draft`.
> 3. Nếu đây là case **minh họa/giả định** (không phải dự án đã làm thật), phải ghi
>    rõ cảnh báo ngay đầu file overview.md — xem ví dụ `corporate-ld/overview.md`,
>    `independent-tutor/overview.md`.
> 4. `featured_image`/`og_image`/gallery cần ảnh chụp màn hình **thật**, không dùng
>    ảnh placeholder khi chuyển sang `published`.

---

## 0. Thông tin quản lý

- **Tên dự án**:
- **Slug** (không dấu, gạch nối):
- **Category** (chọn category có sẵn hoặc đề xuất mới — xem `seedCategories()` trong `ProjectsSeeder.php`):
- **Đây có phải case thật đã triển khai không?** (Thật / Minh họa-giả định):
- **Đã xin phép khách hàng public tên/logo/số liệu chưa?** (Có / Không / Không áp dụng vì là case minh họa):
- **Trạng thái đề xuất**: `draft` / `published` — lý do:

---

## 1. Tài liệu nguồn (thu thập trước)

### 1.1 Business overview
- Khách hàng/tổ chức là ai, hoạt động trong ngành gì?
- Bài toán/nhu cầu ban đầu là gì?
- Đối tượng người dùng cuối (học viên/nhân sự/giáo viên...)?

### 1.2 Project overview
- Vai trò của XO Edu Lab: tự xây từ đầu / tham gia một phần / chỉ tư vấn kỹ thuật?
- Phạm vi dự án (module nào có, module nào không)?
- Thời gian triển khai (bắt đầu — hiện tại/kết thúc)?
- Trạng thái vận hành hiện tại (đang chạy thật / đã ngừng / demo)?

### 1.3 Technical overview (đọc trực tiếp source code nếu có quyền truy cập)
- Kiến trúc tổng thể (số repo, framework, DB)?
- Các module/luồng nghiệp vụ chính?
- Điểm kỹ thuật đáng chú ý (đáng để đưa vào case study)?
- **Khoảng trống/hạn chế đã biết** (tính năng chưa hoàn thiện, tech debt...) — để tránh over-claim khi viết case study công khai.

### 1.4 Nguồn số liệu thật (nếu có)
- Số liệu vận hành lấy từ đâu (dashboard nào, ai xác nhận, ngày xác nhận)?
- Liệt kê từng số liệu kèm nguồn — số nào **không có nguồn thì bỏ qua**, không điền vào phần 2.

---

## 2. Nội dung biên tập — theo cấu trúc CMS (`project_translations`)

> Điền cho **cả 2 locale** (`vi`, `en`) nếu cần đa ngôn ngữ ngay từ đầu. Các trường
> đánh dấu **(JSON)** là mảng object — xem ví dụ định dạng ngay dưới mỗi trường.

### Cơ bản
- `title`:
- `excerpt` (1-2 câu tóm tắt):
- `featured_image` (đường dẫn ảnh thật):

### Hero
- `hero_eyebrow` (nhãn nhỏ phía trên tiêu đề, vd "EdTech / Online Exam Platform"):
- `hero_cta_label` / `hero_cta_url` (nếu có nút CTA):
- `hero_badges` **(JSON)** — danh sách badge icon+label:
  ```
  [{"icon": "...", "label": "..."}, ...]
  ```
- `hero_stats` **(JSON)** — chỉ điền nếu có số liệu thật xác nhận:
  ```
  [{"value": "...", "label": "..."}, ...]
  ```

### Project Snapshot
- `snapshot_items` **(JSON)** — thông tin nhanh (Industry, Type, Role, Tech, Languages...):
  ```
  [{"icon": "...", "label": "...", "value": "..."}, ...]
  ```

### Project Scale (tùy chọn — dùng cho số liệu quy mô *kỹ thuật*, không phải số liệu kinh doanh)
- `scale_heading`:
- `scale_description`:
- `scale_stats` **(JSON)** — vd số model/migration/màn hình admin (đếm được từ code, không phải ước lượng):
  ```
  [{"value": "...", "label": "..."}, ...]
  ```

### The Challenge
- `challenges_heading`:
- `challenges_description`:
- `challenges` **(JSON)**:
  ```
  [{"icon": "...", "color": "primary|secondary|gold", "title": "...", "description": "...", "wide": false}, ...]
  ```

### Feature Map
- `feature_map_heading`:
- `feature_groups` **(JSON)**:
  ```
  [{"title": "...", "badge_label": "...", "features": ["...", "..."]}, ...]
  ```

### Product Journey
- `journey_heading`:
- `journey_steps` **(JSON)**:
  ```
  [{"title": "...", "description": "..."}, ...]
  ```

### Solution Modules (bảng con `project_solution_modules` — mỗi mô-đun giải pháp là 1 dòng)
Lặp lại khối này cho từng module:
- `image`:
- `title`:
- `description`:
- `technical_note` (chi tiết kỹ thuật, tùy chọn):
- `features` **(JSON array string)**: `["...", "..."]`

### Product Gallery
- `gallery_heading`:
- Ảnh (theo category) — cần ảnh chụp màn hình thật, ghi rõ `category_key` cho mỗi ảnh:
  - category:
  - danh sách URL ảnh:

### Technical Architecture (tùy chọn)
- `architecture_heading`:
- `architecture_layers` **(JSON)**:
  ```
  [{"icon": "...", "title": "...", "subtitle": "..."}, ...]
  ```

### Technology Stack
- `tech_stack_groups` **(JSON)**:
  ```
  [{"title": "...", "items": ["...", "..."]}, ...]
  ```

### Results & Impact (chỉ điền số/kết quả có nguồn xác nhận)
- `results_heading`:
- `results` **(JSON)**:
  ```
  [{"icon": "...", "color": "primary|secondary|gold", "value": "...", "label": "..."}, ...]
  ```

### Lessons Learned
- `lessons_quote`:
- `lessons_citation` (vd "— Đội ngũ Kỹ thuật XO"):

### Related Projects
- Danh sách slug các case study liên quan:

### Metrics riêng (bảng `project_metrics` — 2-3 con số nổi bật kiểu banner, tách khỏi hero_stats)
- Chỉ điền nếu có số liệu thật:
  - value / label (vi) / label (en):

### SEO
- `meta_title`:
- `meta_description`:
- `og_image`:

---

## 3. Checklist trước khi chuyển `published`

- [ ] Đã có ảnh thật cho `featured_image`, `og_image`, gallery (không còn placeholder)
- [ ] Mọi số liệu (hero_stats/scale_stats/results/metrics) đều có nguồn xác nhận, ghi rõ nguồn ở mục 1.4
- [ ] Nếu nêu tên tổ chức/khách hàng thật kèm dữ liệu nhạy cảm → đã xin xác nhận công khai
- [ ] Nếu là case minh họa/giả định → đã ghi cảnh báo rõ ràng trong nội dung (hoặc giữ tên chung chung, không gán khách hàng cụ thể)
- [ ] Đã điền đủ cả 2 locale (vi/en) nếu site hiển thị song ngữ
- [ ] Slug không trùng với case study khác
