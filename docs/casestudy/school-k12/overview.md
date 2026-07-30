# School Management System — Tài liệu Dự án (MINH HỌA)

> **⚠️ LƯU Ý QUAN TRỌNG:** Đây là case study MINH HỌA/GIẢ ĐỊNH, mô tả năng lực giải pháp mà đội ngũ có thể triển khai cho nhóm khách hàng "Trường học". KHÔNG phải một dự án đã thực hiện thật. Không gán tên trường/tổ chức cụ thể. Không có số liệu vận hành thật (metrics để trống). Khi có dự án thật khớp bối cảnh này, thay thế toàn bộ nội dung này bằng dữ liệu thật.

---

## Tổng quan

**School Management System** là mô hình hệ thống quản lý trường học phổ thông (K-12) hoặc trung tâm giáo dục vận hành theo mô hình chính quy — khác biệt cốt lõi với các case trung tâm đào tạo hiện có (Seiko, HanQuocNori) ở cấu trúc **năm học/học kỳ cố định**, nhiều môn học song song, và sự tham gia của **phụ huynh** như một vai trò chính thức trong hệ thống.

## Bài toán

- Quản lý học vụ theo năm học → học kỳ → lớp cố định (khác mô hình "khóa học mở linh hoạt" của trung tâm, nơi lớp có thể bắt đầu bất kỳ lúc nào).
- Một học sinh học nhiều môn song song trong cùng một học kỳ, mỗi môn có giáo viên và thời khóa biểu riêng — không phải một khóa học đơn lẻ.
- Cần sổ điểm, học bạ điện tử, xếp loại học lực theo quy chế đánh giá của nhà trường (không phải chỉ chấm điểm bài tập/bài thi đơn thuần).
- Phụ huynh cần được thông báo tình hình học tập, điểm số, hạnh kiểm của con em — một kênh liên lạc phụ huynh–giáo viên chính thức trong hệ thống, không có trong các case LMS hiện tại.
- Thời khóa biểu cố định cho cả năm học, phân công giáo viên theo môn/lớp, khác với lịch dạy linh hoạt theo slot của mô hình 1-1 hoặc lớp mở.

## Hướng giải pháp minh họa

### Cấu trúc học vụ theo năm học
Mô hình phân cấp: Năm học → Học kỳ → Khối lớp → Lớp học cố định (sĩ số, giáo viên chủ nhiệm) → Môn học (mỗi môn có giáo viên bộ môn và thời khóa biểu riêng trong tuần). Khác với "khóa học" của trung tâm đào tạo, ở đây một học sinh gắn với một lớp cố định suốt học kỳ và học nhiều môn song song.

### Sổ điểm & học bạ điện tử
Giáo viên bộ môn nhập điểm theo cột điểm quy định (miệng, 15 phút, 1 tiết, học kỳ...), hệ thống tự tính điểm trung bình môn và xếp loại học lực/hạnh kiểm theo quy chế, tổng hợp thành học bạ điện tử theo từng học kỳ/năm học.

### Liên lạc phụ huynh–giáo viên
Phụ huynh có tài khoản riêng (không phải tài khoản học sinh), xem được điểm số, thời khóa biểu, thông báo nghỉ học/vi phạm, và có kênh trao đổi trực tiếp với giáo viên chủ nhiệm — vai trò này không tồn tại trong các case LMS trung tâm đào tạo hiện có.

### Thời khóa biểu chính quy
Thời khóa biểu được lập cho cả học kỳ/năm học, phân công giáo viên cố định theo môn và lớp, khác với lịch học linh hoạt theo slot đăng ký của mô hình trung tâm hoặc gia sư 1-1.

### Khác biệt với Seiko LMS (trung tâm ngoại ngữ)
Seiko quản lý theo kỳ học/khóa học linh hoạt, không có khái niệm học bạ hay phụ huynh; School Management System quản lý theo năm học cố định, nhiều môn song song, và có vai trò phụ huynh chính thức.

## Công nghệ minh họa (dựa trên stack đã dùng cho các case tương tự trong hệ sinh thái)

- **Backend:** Laravel, Sanctum, phân quyền đa vai trò (Admin/Giáo viên/Học sinh/Phụ huynh).
- **Admin/Giáo viên:** Vue 3 hoặc Nuxt, giao diện nhập điểm, quản lý thời khóa biểu dạng lịch.
- **Phụ huynh:** cổng thông tin riêng (web hoặc responsive), thông báo qua email/push.
- **Báo cáo:** xuất học bạ, bảng điểm dạng PDF/Excel theo học kỳ.

## Kết quả

*Chưa có số liệu vận hành thật — đây là case minh họa. Khi có dự án thật khớp bối cảnh trường học K-12, cập nhật phần này bằng số liệu thực tế qua CMS.*
