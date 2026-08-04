# TopThi — Tài liệu Dự án

> **⚠️ Ghi chú lịch sử (2026-08-01):** Case study TopThi được viết vào `ProjectsSeeder.php` sớm nhất trong toàn bộ file (cùng đợt migration `2026_07_07`), **trước khi** quy ước "phải có `docs/casestudy/<slug>/` làm nguồn trước khi viết seeder, không bịa số liệu" được thiết lập cho các case sau (msd, hanquocnori, seiko, corporate-ld, school-k12, independent-tutor, free-content-branding). Vì vậy TopThi là case duy nhất không có docs nguồn — file này được tạo *sau*, để bù lại và ghi nhận rõ những gì đã xác nhận / chưa xác nhận.
>
> **Đã xác nhận (2026-08-01):** các con số cụ thể trước đây có trong seeder — *"40% giảm thời gian chấm bài"*, *"10,000+ lượt làm bài mỗi tháng"*, *"99.9% uptime"* — là **placeholder viết sớm, chưa xác minh**, không phải số liệu vận hành thật. Đã gỡ khỏi seeder (`hero_stats`, `results`, `results_heading`, và bảng `project_metrics`). Không bịa số — chỉ điền lại qua CMS khi có số liệu thật.

---

## Tổng quan

**TopThi** là *living lab* nội bộ của XO Edu Lab (không phải dự án cho khách hàng ngoài) — theo định vị trong `docs/plans/00-tong-quan-dinh-vi-sitemap.md`:

> TopThi nên được xem là living lab của công ty. Các năng lực như Exam Engine, Knowledge Graph, Learning Analytics và AI Learning nên được thử nghiệm trên TopThi trước, sau đó đóng gói thành dịch vụ hoặc sản phẩm để bán cho thị trường.

Đây là nền tảng thi trực tuyến dùng để chứng minh năng lực thực chiến cho nhóm sản phẩm Exam Engine / AI Learning, đồng thời phủ nhóm khách hàng "EdTech Startup" trong menu "Dành cho ai" (theo `docs/casestudy-gaps-proposal.md`).

## Nội dung định tính (giữ nguyên trong seeder — mô tả năng lực, không phải số liệu)

- **Thách thức:** chống gian lận thi trực tuyến ở quy mô lớn, tự động hóa chấm điểm, biến dữ liệu làm bài thành insight năng lực học viên.
- **Bản đồ chức năng:** Ngân hàng câu hỏi (gắn tag độ khó/kỹ năng, random đề & đáp án), Chấm điểm tự động, Phân tích (dashboard hành vi làm bài, Learning Analytics, Knowledge Graph thử nghiệm).
- **Hành trình sử dụng:** vào phòng thi → làm bài → nộp bài → xem kết quả.
- **Solution modules:** Ngân hàng câu hỏi thông minh; Chấm điểm & Dashboard phân tích.

## Cần xác nhận trước khi điền lại số liệu

- Số liệu vận hành thật: tỷ lệ giảm thời gian chấm bài, lượt làm bài/tháng, uptime nền tảng.
- Tech stack/kiến trúc thật (seeder hiện chỉ có `snapshot_items.Tech = 'Laravel'`, chưa có `architecture_layers`/`tech_stack_groups` chi tiết như HanQuocNori/Seiko).
- Ảnh chụp màn hình cho `featured_image`/`og_image`/gallery.

## Kết quả

*Chưa có số liệu vận hành thật. Không bịa số — điền qua CMS khi có số liệu thật.*
