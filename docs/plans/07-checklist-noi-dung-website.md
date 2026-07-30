# Checklist nội dung cho XO Edu Lab

Ngày tạo: 2026-07-22. Đi kèm với đợt redesign "Space to Become" (menu mới: Why XO / Solutions / Products / Who We Help / Research / Our Work / Insights).

Chia theo nơi thao tác, vì có 2 loại khác nhau:
- **A. Sửa qua Admin** (`/login`) — có CRUD sẵn.
- **B. Sửa trong code** — chưa có màn Admin, đang nằm trong seeder `backend/database/seeders/HomePageSeeder.php`. Muốn tự sửa không cần đụng code thì báo lại để build thêm Admin CRUD cho khối này (như đã làm cho Audience/Who We Help).

---

## A. Sửa được ngay qua Admin

### Solutions (Giải pháp) — 7 mục
- [ ] LMS (Learning Management System)
- [ ] Online Exam Platform
- [ ] School & Training Center Management
- [ ] AI Solutions for Education
- [ ] Learning Analytics
- [ ] Adaptive Learning
- [ ] Education Technology Consulting

Mỗi mục cần rà: problem, solution overview, features, FAQ — hiện là nội dung viết nháp, cần đúng giọng văn và số liệu thật của XO.

### Products (Sản phẩm) — 6 mục
- [ ] TopThi
- [ ] Exam Engine
- [ ] Question Bank Engine
- [ ] Learning Analytics Platform
- [ ] AI Learning Engine
- [ ] Knowledge Graph Engine

Rà mô tả + xác nhận đúng `stage` (live / đang phát triển / roadmap) cho từng sản phẩm.

### Who We Help (Dành cho ai) — 5 mục
- [ ] Independent Educators (Giáo viên & chuyên gia độc lập)
- [ ] Training Centers (Trung tâm đào tạo)
- [ ] Schools (Trường học)
- [ ] EdTech Startups
- [ ] Enterprise Learning (Đào tạo nội bộ doanh nghiệp)

Rà pain points / cách XO giúp. **Thiếu ảnh đại diện** (hero_image đang để trống, trang đang fallback gradient placeholder).

### Our Work (Dự án) — 2 mục
- [ ] TopThi (published) — xác nhận số liệu (40% giảm thời gian chấm bài, 10,000+ lượt làm bài/tháng, 99.9% uptime) là thật hay minh họa
- [ ] MSD Learning Platform (**draft**) — **cần xin xác nhận MSD Vietnam trước khi public** (đã ghi chú sẵn trong code vì có dữ liệu học viên nhạy cảm: dân tộc, khuyết tật) + cần ảnh chụp màn hình thật

### Research — 8 bài, 3 chủ đề (Knowledge Graph, Student Behavior, Human Potential)
- [ ] Viết đầy đủ nội dung — hiện mỗi bài chỉ ~1 đoạn ngắn (placeholder), cần bài đầy đủ nếu muốn dùng làm nội dung SEO/thought leadership thật

### Insights (Blog) — 9 bài, 3 chuyên mục (Online Exam Platform, LMS, Learning Analytics)
- [ ] Viết đầy đủ nội dung — tương tự Research, hiện là bài ngắn placeholder

---

## B. Sửa trong code (seeder) — chưa có Admin UI

### Trang chủ (`/`)
- [ ] Hero (heading, subheading, quote, CTA)
- [ ] Problem (3 card: Nền tảng chiến lược / Kiến trúc học tập / Hệ sinh thái công nghệ)
- [ ] Who We Help teaser (4 card)
- [ ] Solutions teaser
- [ ] Journey (7 bước: Khám phá → Mở rộng)
- [ ] Ecosystem (4 năng lực: Strategy, Learning Design, Experience Design, Tech & Data)
- [ ] Process (5 phase: Understand → Improve)
- [ ] Scale (4 tile bento)
- [ ] Partnership (3 gói hợp tác)
- [ ] Philosophy quote
- [ ] Research/Our Work teaser
- [ ] Insights teaser
- [ ] Final CTA

Toàn bộ là copy viết dựa theo mockup thiết kế, cần duyệt lại thông điệp thương hiệu.

### Vì sao chọn XO (`/about`)
- [ ] Viết lại câu chuyện "Space to Become", triết lý, mô hình hợp tác theo đúng câu chuyện thật của XO (bản hiện tại là suy diễn từ brief thiết kế)

### Liên hệ (`/contact`)
- [ ] Rà đoạn giới thiệu ngắn (ít quan trọng, tạm ổn)

### Chính sách bảo mật (`/privacy`) — ưu tiên cao, chặn go-live
- [ ] Viết nội dung pháp lý thật — hiện đang để placeholder "nội dung đang hoàn thiện, sẽ cập nhật sau"

### Điều khoản dịch vụ (`/terms`) — ưu tiên cao, chặn go-live
- [ ] Viết nội dung pháp lý thật — hiện đang để placeholder tương tự

---

## C. Ảnh & media — đều đang là placeholder

- [ ] **Logo chính thức** (SVG/AI) — đang dùng ảnh JPG do AI tạo tạm, không phải file vector chính thức. Ảnh hưởng logo header, footer, và favicon.
- [ ] 15 ảnh trang chủ (`frontend/public/images/home/*`): hero, 3 avatar, 4 who-we-help, ecosystem, 2 scale — ảnh minh họa tải từ mockup thiết kế, cần ảnh thật/thiết kế thật của XO
- [ ] Ảnh đại diện (hero_image) cho 5 trang Who We Help — đang trống
- [ ] Ảnh sản phẩm/case study thật cho Our Work (featured_image + ảnh minh họa từng mục problem/solution/result)

---

## Thứ tự ưu tiên đề xuất

1. Chính sách bảo mật + Điều khoản dịch vụ (chặn go-live)
2. Logo chính thức (ảnh hưởng toàn site, favicon)
3. Nội dung Vì sao chọn XO + rà lại Trang chủ (trang khách xem đầu tiên)
4. Rà nội dung Solutions/Products (đã có cấu trúc, chỉ cần polish qua Admin)
5. Who We Help — bổ sung ảnh
6. Xác nhận MSD case study với đối tác trước khi publish
7. Viết đầy đủ Research/Insights (có thể làm dần, không chặn go-live)
