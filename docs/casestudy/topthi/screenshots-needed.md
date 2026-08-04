# TopThi — Danh sách ảnh màn hình cần chụp

> Cập nhật 2026-08-04, sau khi seeder đã được bổ sung đầy đủ theo `overview_updated.md`
> (xem `overview.md` để biết bối cảnh: TopThi là living lab nội bộ XO Edu, không phải case
> khách hàng ngoài).

## Ưu tiên 1 — đang gắn trực tiếp vào field CMS đã seed

| Field trong CMS | Màn hình cần chụp | Ghi chú |
|---|---|---|
| `featured_image` / `og_image` | Trang chủ Front (Nuxt) | Ảnh đại diện đẹp nhất, có thương hiệu — dùng cho hero card & chia sẻ mạng xã hội (OG image), nên chụp full-width, không có popup/banner che |
| Solution module 1 — "Làm bài thi chống gian lận" | Giao diện làm bài thi | Cần thấy đồng hồ đếm giờ + câu hỏi trắc nghiệm đang làm dở, minh họa server-side timing |
| Solution module 2 — "AI Insight Report" | Trang `/ho-so-nang-luc` | Báo cáo top kỹ năng yếu, so sánh xu hướng giữa các lần thi, gợi ý đề luyện tiếp theo |
| Solution module 3 — "Lịch ôn tập thông minh" | Trang `/lich-on-tap` | Cần thấy 2 tab "Đến hạn" / "Sắp đến hạn" (spaced repetition) |

## Ưu tiên 2 — Product Gallery (chưa seed, làm sau nếu muốn mở rộng)

- Trang chọn đề thi (bộ lọc theo môn/chuyên đề)
- Trang kết quả ngay sau khi nộp bài (chấm điểm tự động)
- Admin — dashboard thống kê (theo đề/câu hỏi/người dùng)
- Admin — bảng gán nhãn kỹ năng cho câu hỏi (AI hỗ trợ, OpenAI API)
- Trang thanh toán / gói cước (PayOS)

## Yêu cầu chung khi chụp

- Ẩn dữ liệu cá nhân thật của học sinh (tên, email, số điện thoại) nếu ảnh có dữ liệu vận hành thật — che hoặc dùng tài khoản demo.
- Ưu tiên độ phân giải cao (≥1600px chiều rộng), định dạng PNG/WebP.
- Chụp ở trạng thái có dữ liệu đầy đủ (không chụp màn hình trống/rỗng) để thể hiện đúng tính năng.
- Sau khi có ảnh, cập nhật qua CMS (không sửa trực tiếp seeder) — seeder chỉ seed lần đầu, các lần sau chỉnh qua admin panel.
