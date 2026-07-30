# Corporate L&D Platform — Tài liệu Dự án (MINH HỌA)

> **⚠️ LƯU Ý QUAN TRỌNG:** Đây là case study MINH HỌA/GIẢ ĐỊNH, mô tả năng lực giải pháp mà đội ngũ có thể triển khai cho nhóm khách hàng "Đào tạo nội bộ doanh nghiệp" — KHÔNG phải một dự án đã thực hiện thật. Không gán tên khách hàng/tổ chức cụ thể. Không có số liệu vận hành thật (metrics để trống). Khi có dự án thật khớp bối cảnh này, thay thế toàn bộ nội dung này bằng dữ liệu thật.

---

## Tổng quan

**Corporate L&D Platform** là mô hình nền tảng đào tạo nội bộ dành cho doanh nghiệp, minh họa năng lực xây dựng hệ thống L&D (Learning & Development) gắn với vận hành nhân sự — khác với LMS cho trung tâm đào tạo hay NGO ở chỗ trọng tâm là **tuân thủ (compliance)** và **gắn với cơ cấu tổ chức** thay vì tuyển sinh công khai.

## Bài toán

- Nhân viên mới cần lộ trình đào tạo (onboarding) tự động kích hoạt theo ngày gia nhập, không cần HR giao thủ công từng người.
- Các khóa đào tạo bắt buộc (an toàn lao động, quy định nội bộ, PDPA/bảo mật dữ liệu...) cần cơ chế theo dõi hạn chót, nhắc nhở tự động, và chặn không cho coi là "hoàn thành" nếu chưa đạt.
- Khóa học cần gán theo phòng ban/vai trò/cấp bậc thay vì học viên tự chọn tự do như mô hình trung tâm.
- HR/quản lý cần báo cáo tuân thủ theo thời gian thực: ai đã hoàn thành, ai còn thiếu, theo phòng ban nào.

## Hướng giải pháp minh họa

### Cấu trúc tổ chức làm trung tâm dữ liệu
Thay vì "khóa học công khai cho ai đăng ký", hệ thống lấy sơ đồ tổ chức (phòng ban → vai trò → nhân viên) làm gốc để gán khóa học tự động theo quy tắc (ví dụ: "mọi nhân viên phòng Kỹ thuật phải hoàn thành khóa An toàn lao động trong 30 ngày kể từ ngày vào làm").

### Compliance tracking
Mỗi khóa bắt buộc có hạn chót cá nhân hóa theo ngày gia nhập/ngày được gán, hệ thống tự nhắc qua email/thông báo nội bộ khi gần hạn, và có báo cáo tổng hợp trạng thái tuân thủ cho quản lý/HR theo phòng ban, có thể xuất Excel.

### Tích hợp nội dung chuẩn công nghiệp
Hỗ trợ nhập nội dung theo chuẩn SCORM/xAPI để tận dụng thư viện bài giảng e-learning có sẵn của doanh nghiệp, thay vì buộc phải tạo lại nội dung từ đầu trên nền tảng.

### Khác biệt với case NGO (MSD)
MSD đo lường tác động xã hội tới nhóm học viên yếu thế; Corporate L&D đo lường tỷ lệ tuân thủ và hiệu suất đào tạo gắn với KPI nhân sự — cùng là LMS nhưng mục tiêu nghiệp vụ và đối tượng dữ liệu khác nhau.

## Công nghệ minh họa (dựa trên stack đã dùng cho các case tương tự trong hệ sinh thái)

- **Backend:** Laravel, Sanctum, Spatie Permission (phân quyền theo vai trò tổ chức), Maatwebsite Excel (báo cáo tuân thủ), queue cho nhắc nhở tự động.
- **Admin:** Vue 3 + TypeScript, biểu đồ báo cáo tuân thủ (Chart.js).
- **Tích hợp:** SCORM/xAPI player cho nội dung e-learning chuẩn công nghiệp.

## Kết quả

*Chưa có số liệu vận hành thật — đây là case minh họa. Khi có dự án thật khớp bối cảnh Corporate L&D, cập nhật phần này bằng số liệu thực tế qua CMS.*
