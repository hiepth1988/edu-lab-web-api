# Case Study — Topthi (Nền tảng thi thử / luyện thi online + AI Insight)

> Điền theo `_TEMPLATE.md`. Số liệu (hero_stats/scale_stats/results/metrics) để trống
> chờ điền sau khi có nguồn xác nhận — KHÔNG bịa. Phần "Quy mô kỹ thuật" trong mục 1.3
> là số đếm được trực tiếp từ code nên đã điền sẵn, có thể dùng cho `scale_stats`.

---

## 0. Thông tin quản lý

- **Tên dự án**: Topthi — Nền tảng thi thử & luyện thi online có AI Insight
- **Slug**: `topthi`
- **Category**: EdTech / Online Exam & Learning Platform (đề xuất mới nếu chưa có category tương ứng trong `seedCategories()`)
- **Đây có phải case thật đã triển khai không?**: Thật — source code đang phát triển tại 3 repo (`Api`, `Admin`, `Front`), có dữ liệu vận hành thực tế (chưa xác nhận số liệu cụ thể)
- **Đã xin phép khách hàng public tên/logo/số liệu chưa?**: Chưa xác nhận — cần hỏi lại chủ dự án trước khi chuyển `published`
- **Trạng thái đề xuất**: `draft` — lý do: chưa có ảnh chụp màn hình thật, chưa có số liệu vận hành xác nhận, chưa xin phép public tên dự án

---

## 1. Tài liệu nguồn (thu thập trước)

### 1.1 Business overview
- **Khách hàng/tổ chức**: (điền sau — chưa xác nhận có phải public được không)
- **Ngành**: EdTech — luyện thi/ôn tập trắc nghiệm (hiện tập trung môn Toán, có kiến trúc mở rộng đa môn qua `subject_code`)
- **Bài toán/nhu cầu ban đầu**: Xây nền tảng cho học sinh làm đề thi thử online, tự động chấm điểm, và tiến tới cá nhân hóa lộ trình ôn tập dựa trên năng lực thực tế của từng học sinh thay vì chỉ chấm điểm đơn thuần
- **Đối tượng người dùng cuối**: Học sinh phổ thông (luyện thi), giáo viên/admin nội dung (biên soạn đề, gán nhãn kỹ năng câu hỏi), đội vận hành (quản trị đơn hàng, gói cước insight)

### 1.2 Project overview
- **Vai trò của đội kỹ thuật**: Tự xây từ đầu — thiết kế toàn bộ data model, backend, admin panel và frontend học viên
- **Phạm vi dự án**:
  - Có: quản lý đề thi/câu hỏi, làm bài thi online có chống gian lận server-side, thanh toán (PayOS), quản lý người dùng/phân quyền, dashboard thống kê, module AI Insight (skill tracking, mastery score, gợi ý ôn tập, spaced repetition), admin panel quản trị toàn bộ, blog/nội dung phụ trợ, tìm kiếm (Elasticsearch)
  - Không có (chưa làm): Embedding/vector similarity, Knowledge Tracing nâng cao (BKT/DKT), test tự động (coverage gần như 0)
- **Thời gian triển khai**: (điền sau — dựa theo lịch sử git/commit đầu tiên)
- **Trạng thái vận hành hiện tại**: Đang phát triển tích cực — module AI Insight (skills, learning_events, insight report, spaced repetition, milestone) là phần mới nhất, có cả backend lẫn 2 trang frontend thật tiêu thụ API (`/lich-on-tap`, `/ho-so-nang-luc`)

### 1.3 Technical overview

**Kiến trúc tổng thể**
- 3 repo riêng biệt: `Api` (Laravel 9 / PHP 8.0+), `Admin` (Vue 3 Composition API + TypeScript), `Front` (Nuxt 3.9 SSR)
- Database: MySQL (utf8mb4)
- Queue: Laravel Queue (driver `database`, sẵn sàng chuyển `redis`)
- Cache: Redis (`predis/predis`)
- Search: Elasticsearch 8.x (custom analyzer tiếng Việt, có fallback MySQL LIKE khi ES down)
- Thanh toán: PayOS SDK chính thức
- AI bên thứ 3: OpenAI API (`gpt-4o-mini`) cho auto-labeling câu hỏi theo kỹ năng — gọi trực tiếp qua Guzzle

**Module nghiệp vụ chính**
1. Đề thi & câu hỏi — trộn đề, xuất Word, đề thương mại
2. Làm bài thi — chống gian lận thời gian server-side, snapshot đáp án tại thời điểm thi
3. Thanh toán & gói cước — PayOS, đơn hàng, credit hệ thống insight
4. Người dùng & phân quyền — Sanctum 2 guard (admin/user), RBAC theo `company_id`
5. Dashboard & thống kê — theo đề, theo câu hỏi, theo người dùng
6. **AI Insight Stack** (xem chi tiết mục dưới — tính năng đang phát triển, trọng tâm case study)
7. Crawler & import — nhập câu hỏi hàng loạt, xử lý lỗi Excel
8. Nội dung phụ trợ — blog, quảng cáo, lộ trình học, diễn đàn hỏi đáp

**AI Insight Stack — đang phát triển (điểm nhấn case study)**
- **Skill taxonomy**: bảng `skills` (subject_code/skill_code) + `skill_relations` (quan hệ tiên quyết giữa kỹ năng, dạng đồ thị — đã có schema, chưa được logic nghiệp vụ sử dụng)
- **Event log append-only**: `learning_events` ghi lại từng lượt trả lời câu hỏi, thiết kế chủ đích không update/không xóa, làm dữ liệu thô cho Knowledge Tracing sau này
- **Mastery scoring**: `user_skill_states` — điểm thành thạo (0-1) cập nhật kiểu EMA có trọng số theo độ khó câu hỏi, phân loại 4 mức (critical/weak/moderate/good); code ghi rõ đây là baseline rule-based, có lộ trình nâng cấp lên BKT/DKT
- **Xử lý async sau khi nộp bài**: nộp bài → job lưu kết quả → dispatch job riêng ghi learning_events + cập nhật mastery + build insight report + kiểm tra milestone — có idempotency guard và retry, không chặn trải nghiệm nộp bài của học sinh
- **Insight Report**: báo cáo phân tích năng lực sau mỗi lần thi — top kỹ năng yếu, so sánh với báo cáo trước (xu hướng tăng/giảm), gợi ý đề luyện tiếp theo dựa trên độ phủ kỹ năng yếu
- **Spaced repetition**: tự sinh lịch ôn tập (offset 1/3/7 ngày) khi phát hiện kỹ năng yếu, có UI riêng ở frontend
- **Gamification nhẹ**: streak học tập tính từ learning_events, milestone (streak/skill mastered/số lượt thi) chống trùng bằng unique constraint
- **Gợi ý câu hỏi tương tự**: theo kỹ năng chung (tag-based), chưa dùng embedding
- **Mô hình credit trả phí**: hạ tầng mở khóa insight report bằng credit đã sẵn sàng, hiện đang bật chế độ miễn phí toàn bộ để tăng trưởng người dùng trước

**Quy mô kỹ thuật (đếm trực tiếp từ code, dùng cho `scale_stats`)**
| Hạng mục | Số lượng |
|---|---|
| Models | 76 |
| Migrations | 171 |
| API routes (user) | 78 |
| API routes (admin) | 128 |
| Controllers | 87 (36 user-facing + 41 admin + còn lại) |
| Services | 65 |
| Jobs (hàng đợi) | 26 |
| Form Requests | 45 |
| Migration riêng cho AI/Insight | 13 |

**Điểm kỹ thuật đáng chú ý**
- Kiến trúc event-sourcing kiểu append-only cho dữ liệu học tập, chuẩn bị cho mô hình AI phức tạp hơn mà không cần re-migrate
- Chống gian lận thi server-side bằng atomic update có điều kiện thay vì check-then-update (tránh race condition)
- Snapshot đáp án đúng tại thời điểm thi, phục vụ audit/khiếu nại điểm khi đề gốc bị sửa sau
- Tìm kiếm tiếng Việt không dấu tự triển khai, dùng chung giữa AI labeling và Elasticsearch, có fallback khi ES down
- Có tài liệu đánh giá kiến trúc/hiệu năng/bảo mật nội bộ (self-audit), theo dõi vấn đề có ưu tiên rõ ràng — quy trình kỹ thuật có kỷ luật

**Khoảng trống/hạn chế đã biết** (để tránh over-claim khi viết case study công khai)
- Test tự động gần như không có ở cả 3 repo
- RBAC admin có đủ model Role/Permission nhưng router guard frontend admin chưa enforce theo quyền, chỉ check đăng nhập
- BKT/DKT (Knowledge Tracing nâng cao) chưa triển khai — hiện là rule-based EMA
- Chưa có Embedding/vector similarity cho gợi ý câu hỏi
- `skill_relations` (đồ thị tiên quyết) đã có schema nhưng chưa được dùng trong logic suy luận thực tế
- Một số phần dở dang/trùng lặp đã tự ghi nhận nội bộ (config thanh toán cũ không dùng, vài trang UI trùng lặp)
- Trang chủ frontend chưa cá nhân hóa theo skill/streak — phần AI hiện nằm ở các route phụ, chưa lên trang chủ
- Cần lưu ý khi viết: tính năng AI labeling dùng **OpenAI API**, không phải Claude/Anthropic dù tên nội bộ gây hiểu nhầm

### 1.4 Nguồn số liệu thật (nếu có)
- Chưa có — mọi số liệu vận hành (số học sinh, số đề thi, doanh thu, retention...) cần chủ dự án xác nhận và cung cấp nguồn trước khi điền vào mục 2

---

## 2. Nội dung biên tập — theo cấu trúc CMS (`project_translations`)

### Cơ bản
- `title` (vi): Topthi — Nền tảng luyện thi trắc nghiệm online tích hợp AI phân tích năng lực
- `title` (en): Topthi — Online Exam Practice Platform with AI-driven Skill Insight
- `excerpt` (vi): Nền tảng thi thử trực tuyến cho học sinh phổ thông, tự động chấm điểm và sinh báo cáo phân tích năng lực theo từng kỹ năng, gợi ý lộ trình ôn tập cá nhân hóa.
- `excerpt` (en): An online exam practice platform for high-school students with automated grading and AI-driven skill-level insight reports that generate personalized review plans.
- `featured_image`: (chờ ảnh thật)

### Hero
- `hero_eyebrow`: EdTech / Online Exam & Adaptive Learning Platform
- `hero_cta_label` / `hero_cta_url`: (điền sau nếu có demo public)
- `hero_badges` (JSON):
  ```json
  [
    {"icon": "laravel", "label": "Laravel 9"},
    {"icon": "vue", "label": "Vue 3"},
    {"icon": "nuxt", "label": "Nuxt 3 SSR"},
    {"icon": "openai", "label": "OpenAI API"}
  ]
  ```
- `hero_stats` (JSON): _(chờ số liệu thật — không điền)_

### Project Snapshot
- `snapshot_items` (JSON):
  ```json
  [
    {"icon": "industry", "label": "Industry", "value": "EdTech — Online Exam & Test Prep"},
    {"icon": "layers", "label": "Type", "value": "Multi-repo web platform (API + Admin + Student Front)"},
    {"icon": "role", "label": "Role", "value": "Full lifecycle: kiến trúc, backend, admin panel, frontend"},
    {"icon": "code", "label": "Tech", "value": "Laravel 9, Vue 3, Nuxt 3, MySQL, Redis, Elasticsearch"},
    {"icon": "languages", "label": "Languages", "value": "PHP, TypeScript"}
  ]
  ```

### Project Scale
- `scale_heading`: Quy mô kỹ thuật
- `scale_description`: Số liệu đếm trực tiếp từ source code tại thời điểm khảo sát.
- `scale_stats` (JSON):
  ```json
  [
    {"value": "76", "label": "Database models"},
    {"value": "171", "label": "Migrations"},
    {"value": "206", "label": "API endpoints (user + admin)"},
    {"value": "87", "label": "Controllers"},
    {"value": "65", "label": "Service classes"},
    {"value": "26", "label": "Queue jobs"}
  ]
  ```

### The Challenge
- `challenges_heading`: Bài toán đặt ra
- `challenges_description`: Từ một nền tảng thi thử chấm điểm đơn thuần, cần tiến hóa thành hệ thống hiểu được năng lực thực tế của từng học sinh mà không phá vỡ dữ liệu và trải nghiệm đang vận hành.
- `challenges` (JSON):
  ```json
  [
    {"icon": "brain", "color": "primary", "title": "Từ điểm số sang năng lực", "description": "Hệ thống cũ chỉ chấm đúng/sai theo đề; cần bóc tách được học sinh yếu ở kỹ năng cụ thể nào, không chỉ ở môn học chung chung.", "wide": false},
    {"icon": "zap", "color": "secondary", "title": "Không chặn trải nghiệm nộp bài", "description": "Xử lý phân tích AI phải chạy nền, không được làm chậm phản hồi khi học sinh nộp bài thi.", "wide": false},
    {"icon": "shield", "color": "gold", "title": "Chống gian lận thời gian làm bài", "description": "Thời gian bắt đầu/kết thúc phải được xác thực phía server, tránh học sinh chỉnh sửa thời gian client để gian lận.", "wide": false},
    {"icon": "trending-up", "color": "primary", "title": "Thiết kế có đường lùi nâng cấp", "description": "Mô hình chấm điểm năng lực ban đầu cần đơn giản (rule-based) nhưng schema dữ liệu phải sẵn sàng cho mô hình AI phức tạp hơn (Knowledge Tracing) mà không phải migrate lại từ đầu.", "wide": true}
  ]
  ```

### Feature Map
- `feature_map_heading`: Bản đồ tính năng
- `feature_groups` (JSON):
  ```json
  [
    {"title": "Đề thi & Câu hỏi", "badge_label": "Core", "features": ["Trộn đề tự động", "Xuất đề ra file Word", "Đề thi thương mại (bán riêng)", "Quản lý ngân hàng câu hỏi", "Gán nhãn kỹ năng cho câu hỏi (AI hỗ trợ)"]},
    {"title": "Làm bài thi", "badge_label": "Core", "features": ["Chống gian lận thời gian server-side", "Snapshot đáp án tại thời điểm thi", "Làm bài theo nhóm/lớp", "Chấm điểm tự động"]},
    {"title": "AI Insight Stack", "badge_label": "Đang phát triển", "features": ["Skill taxonomy & đồ thị tiên quyết", "Event log học tập (append-only)", "Chấm điểm mức độ thành thạo theo kỹ năng", "Báo cáo phân tích năng lực sau mỗi lần thi", "So sánh xu hướng giữa các lần thi", "Gợi ý đề luyện theo kỹ năng yếu", "Lịch ôn tập tự sinh (spaced repetition)", "Streak & milestone học tập", "Gợi ý câu hỏi tương tự theo kỹ năng"]},
    {"title": "Thanh toán & Gói cước", "badge_label": "Core", "features": ["Tích hợp PayOS", "Quản lý đơn hàng", "Hệ thống credit mở khóa báo cáo insight", "Gói cước insight"]},
    {"title": "Quản trị & Vận hành", "badge_label": "Admin", "features": ["Phân quyền theo vai trò (RBAC multi-tenant)", "Quản lý người dùng & lịch sử làm bài", "Thống kê theo đề/câu hỏi/người dùng", "Quản lý nội dung (blog, quảng cáo, lộ trình học)", "Bảng điều khiển gán nhãn AI cho câu hỏi"]}
  ]
  ```

### Product Journey
- `journey_heading`: Hành trình sử dụng của học sinh
- `journey_steps` (JSON):
  ```json
  [
    {"title": "Chọn đề & làm bài", "description": "Học sinh chọn đề thi theo môn/chuyên đề, hệ thống ghi nhận thời điểm bắt đầu phía server để chống gian lận."},
    {"title": "Nộp bài & chấm điểm", "description": "Bài thi được chấm tự động ngay lập tức, kết quả hiển thị tức thì."},
    {"title": "Phân tích năng lực nền", "description": "Hệ thống xử lý nền: ghi nhận từng câu trả lời, cập nhật mức độ thành thạo theo từng kỹ năng."},
    {"title": "Nhận báo cáo Insight", "description": "Học sinh nhận báo cáo kỹ năng yếu/mạnh, so sánh với lần thi trước, gợi ý đề luyện tiếp theo."},
    {"title": "Ôn tập theo lịch cá nhân hóa", "description": "Hệ thống tự sinh lịch ôn tập giãn cách cho các kỹ năng yếu, theo dõi streak và cột mốc học tập."}
  ]
  ```

### Solution Modules
_(điền sau khi có ảnh chụp màn hình thật cho từng module — cấu trúc gợi ý bên dưới)_

- **Module: Làm bài thi chống gian lận**
  - `image`: (chờ)
  - `description`: Ghi nhận thời gian bắt đầu/kết thúc phía server bằng cập nhật nguyên tử có điều kiện, tránh gian lận chỉnh sửa thời gian phía client.
  - `technical_note`: Atomic UPDATE có điều kiện `WHERE ended_at IS NULL` thay vì check-then-update.
  - `features`: `["Server-side timing", "Chống double-submit", "Snapshot đáp án"]`

- **Module: AI Insight Report**
  - `image`: (chờ)
  - `description`: Sau mỗi lần thi, hệ thống tự động phân tích và chỉ ra học sinh đang yếu ở kỹ năng nào, so sánh tiến bộ theo thời gian.
  - `technical_note`: Xử lý async qua queue job có retry & idempotency guard; mastery score theo mô hình EMA có trọng số độ khó.
  - `features`: `["Top kỹ năng yếu", "So sánh xu hướng theo thời gian", "Gợi ý đề luyện tiếp theo"]`

- **Module: Lịch ôn tập thông minh**
  - `image`: (chờ)
  - `description`: Tự sinh lịch ôn tập giãn cách cho các kỹ năng yếu ngay sau khi phát hiện qua insight report.
  - `technical_note`: Spaced repetition offset 1/3/7 ngày, chống chồng lịch.
  - `features`: `["Tabs Đến hạn/Sắp đến hạn", "Tự động sinh sau insight", "Đánh dấu hoàn thành"]`

### Product Gallery
- `gallery_heading`: (chờ ảnh thật)

### Technical Architecture
- `architecture_heading`: Kiến trúc hệ thống
- `architecture_layers` (JSON):
  ```json
  [
    {"icon": "server", "title": "Backend API", "subtitle": "Laravel 9 / PHP 8 — REST API cho Admin & Student Front"},
    {"icon": "layout", "title": "Admin Panel", "subtitle": "Vue 3 Composition API + TypeScript"},
    {"icon": "monitor", "title": "Student Frontend", "subtitle": "Nuxt 3 SSR + Pinia"},
    {"icon": "database", "title": "Data Layer", "subtitle": "MySQL + Redis cache + Elasticsearch search"},
    {"icon": "cpu", "title": "AI Processing", "subtitle": "Async queue jobs + OpenAI API cho gán nhãn kỹ năng"}
  ]
  ```

### Technology Stack
- `tech_stack_groups` (JSON):
  ```json
  [
    {"title": "Backend", "items": ["Laravel 9", "PHP 8.0+", "Laravel Sanctum", "Spatie Permission (RBAC)"]},
    {"title": "Frontend học viên", "items": ["Nuxt 3 (SSR)", "Vue 3", "Pinia", "TailwindCSS"]},
    {"title": "Admin Panel", "items": ["Vue 3 Composition API", "TypeScript", "Vuex 4", "TailwindCSS"]},
    {"title": "Dữ liệu & Hạ tầng", "items": ["MySQL", "Redis", "Elasticsearch", "Laravel Queue", "Docker"]},
    {"title": "Tích hợp bên thứ 3", "items": ["PayOS (thanh toán)", "OpenAI API (gán nhãn AI)", "Google Socialite (đăng nhập)"]}
  ]
  ```

### Results & Impact
- `results_heading`: (chờ số liệu thật — không điền cho tới khi có xác nhận)
- `results` (JSON): _(để trống)_

### Lessons Learned
- `lessons_quote`: (điền sau khi trao đổi với chủ dự án)
- `lessons_citation`: (điền sau)

### Related Projects
- (điền sau khi có các case study khác trong cùng hệ thống)

### Metrics riêng (`project_metrics`)
- _(chờ số liệu thật)_

### SEO
- `meta_title`: Topthi — Case Study nền tảng luyện thi online tích hợp AI phân tích năng lực
- `meta_description`: Cách xây dựng một nền tảng thi thử trắc nghiệm với module AI phân tích năng lực học sinh theo từng kỹ năng, gợi ý ôn tập cá nhân hóa và kiến trúc sẵn sàng mở rộng lên Knowledge Tracing.
- `og_image`: (chờ ảnh thật)

---

## 3. Checklist trước khi chuyển `published`

- [ ] Đã có ảnh thật cho `featured_image`, `og_image`, gallery (không còn placeholder)
- [ ] Mọi số liệu (hero_stats/scale_stats/results/metrics) đều có nguồn xác nhận, ghi rõ nguồn ở mục 1.4
- [ ] Nếu nêu tên tổ chức/khách hàng thật kèm dữ liệu nhạy cảm → đã xin xác nhận công khai
- [ ] Nếu là case minh họa/giả định → đã ghi cảnh báo rõ ràng trong nội dung (hoặc giữ tên chung chung, không gán khách hàng cụ thể)
- [ ] Đã điền đủ cả 2 locale (vi/en) nếu site hiển thị song ngữ
- [ ] Slug không trùng với case study khác
