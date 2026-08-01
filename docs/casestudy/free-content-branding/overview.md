# Học liệu Miễn phí — Xây dựng Thương hiệu cho Trung tâm & Chuyên gia (Case Study)

> **Nguồn thông tin:** khảo sát trực tiếp 2 website công khai — https://tailieutienghan.vn/ và https://toploigiai.vn/ (cấu trúc menu, tính năng, nội dung). KHÔNG có quyền truy cập mã nguồn/hạ tầng thật của 2 dự án này. Xác nhận từ chủ sở hữu (2026-08-01): cả hai nền tảng do XO Edu phát triển.
>
> **Vì sao gộp thành 1 case study:** đây không phải hai sản phẩm độc lập mà là hai lần triển khai cùng một chiến lược — dùng nội dung bài học/lời giải miễn phí, chất lượng cao, tối ưu SEO để xây dựng uy tín và thương hiệu cho trung tâm đào tạo hoặc chuyên gia cá nhân, thay vì phụ thuộc hoàn toàn vào quảng cáo trả phí. Case study trình bày mô hình chung này, với 2 nền tảng làm ví dụ minh họa (2 solution module trong CMS).
>
> **Cần xác nhận thêm trước khi công bố số liệu vận hành hoặc chi tiết kỹ thuật:** khách hàng/đối tác đứng tên (footer tailieutienghan.vn hiện ghi "Hàn Quốc Nori & EGLife Software"), vai trò cụ thể của XO Edu ở từng dự án, thời gian triển khai, team, tech stack thật, số liệu traffic/chuyển đổi. **Không bịa số liệu** — seeder chỉ dùng nội dung quan sát được từ 2 website thật, để trống các phần cần số liệu kinh doanh (hero_stats, scale_stats, results, tech_stack_groups, architecture_layers).

---

## Mô hình chung: Content-Led Branding

1. **Thư viện nội dung miễn phí** — tài liệu/lời giải chất lượng cao, tối ưu cho tìm kiếm tự nhiên (SEO), cập nhật liên tục.
2. **Xây dựng uy tín** — trang giới thiệu đội ngũ/giáo viên, chuyên mục báo chí/giải thưởng, cộng đồng hỏi đáp.
3. **Chuyển đổi có kiểm soát (freemium)** — nội dung miễn phí thu hút traffic; tài liệu/khóa học premium tạo doanh thu; tài khoản giữ chân người dùng quay lại.

## Hai ví dụ triển khai

- **[Tài Liệu Tiếng Hàn](tailieutienghan.md)** (tailieutienghan.vn) — học liệu & luyện thi TOPIK cho người học tiếng Hàn.
- **[Top Lời Giải](toploigiai.md)** (toploigiai.vn) — lời giải bài tập & khóa học K-12 bám 3 bộ sách giáo khoa mới.

Xem chi tiết tính năng/bài toán riêng của từng nền tảng trong 2 file trên. File này chỉ trình bày khung chiến lược chung dùng để viết case study gộp trong CMS (`ProjectsSeeder::seedFreeContentBranding()`, slug `free-content-branding`).

## Kết quả

*Chưa có số liệu vận hành thật (traffic, số tài liệu/lời giải, tỷ lệ chuyển đổi từ miễn phí sang premium...). Không bịa số — điền qua CMS khi có số liệu thật từ chủ dự án.*
