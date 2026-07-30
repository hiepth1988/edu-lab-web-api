# Tutor Suite — Tài liệu Dự án (MINH HỌA)

> **⚠️ LƯU Ý QUAN TRỌNG:** Đây là case study MINH HỌA/GIẢ ĐỊNH, mô tả năng lực giải pháp mà đội ngũ có thể triển khai cho nhóm khách hàng "Chuyên gia & Giáo viên độc lập". KHÔNG phải một dự án đã thực hiện thật. Không gán tên cá nhân/thương hiệu cụ thể. Không có số liệu vận hành thật (metrics để trống). Khi có dự án thật khớp bối cảnh này, thay thế toàn bộ nội dung này bằng dữ liệu thật.

---

## Tổng quan

**Tutor Suite** là mô hình công cụ self-serve nhẹ dành cho gia sư, huấn luyện viên, hoặc chuyên gia tự do — khác biệt cốt lõi với các case còn lại ở quy mô: không có bộ máy admin nhiều vai trò (admin/giảng viên/học sinh) như hệ thống lớn, mà một cá nhân vừa là người dạy vừa là người quản trị công cụ của chính mình.

## Bài toán

- Giáo viên/chuyên gia độc lập cần tự tạo lớp, quản lý danh sách học viên riêng mà không cần qua trung tâm hay tổ chức trung gian.
- Thu học phí nhỏ lẻ theo buổi/gói buổi học, cần theo dõi công nợ từng học viên (đã đóng bao nhiêu, còn nợ bao nhiêu buổi) mà không cần hệ thống kế toán phức tạp.
- Lịch dạy cá nhân: đặt lịch với từng học viên theo slot rảnh của giáo viên, tránh trùng lịch, nhắc lịch tự động.
- Cần công cụ đơn giản, không đòi hỏi giáo viên vận hành hạ tầng — khác hoàn toàn với các hệ thống lớn (Seiko, HanQuocNori) vốn cần đội IT/vận hành riêng.

## Hướng giải pháp minh họa

### Mô hình self-serve, một vai trò duy nhất
Không có phân cấp Admin/Giảng viên/Học sinh như hệ thống lớn — giáo viên đăng ký tài khoản, tự tạo "lớp" của mình (có thể là một học viên hoặc một nhóm nhỏ), tự quản lý toàn bộ mà không cần ai duyệt hay cấp quyền.

### Quản lý học viên & công nợ nhẹ
Mỗi học viên có hồ sơ đơn giản (tên, liên hệ, gói học đã mua), giáo viên ghi nhận buổi học đã dạy để trừ dần vào gói, hệ thống tự cảnh báo khi học viên sắp hết buổi hoặc quá hạn thanh toán — không cần module kế toán phức tạp như ERP.

### Lịch dạy cá nhân & đặt lịch 1-1
Giáo viên mở các khung giờ rảnh, học viên (hoặc chính giáo viên thay mặt) đặt lịch vào khung giờ đó, hệ thống chặn trùng lịch tự động — về bản chất tương tự phần lớp 1-1 trong HanQuocNori nhưng thu nhỏ lại: không qua trung tâm trung gian, giáo viên tự vận hành công cụ của mình như một SaaS cá nhân.

### Khác biệt cốt lõi với các case còn lại
Đây là mô hình self-serve SaaS — giáo viên/chuyên gia là khách hàng trực tiếp sử dụng công cụ cho chính mình, không phải hệ thống được một tổ chức lớn mua về và vận hành nội bộ cho hàng trăm/nghìn người dùng.

## Công nghệ minh họa (dựa trên stack đã dùng cho các case tương tự trong hệ sinh thái)

- **Backend:** Laravel, Sanctum (single-tenant nhẹ, mỗi giáo viên là một "tenant" độc lập trong dữ liệu).
- **Frontend:** Nuxt hoặc Vue SPA, giao diện lịch (calendar view) đơn giản, tối ưu cho dùng trên điện thoại vì giáo viên tự thao tác không qua đội vận hành riêng.
- **Thanh toán:** ghi nhận thủ công hoặc tích hợp cổng thanh toán nhỏ lẻ (tùy nhu cầu thực tế khi triển khai).

## Kết quả

*Chưa có số liệu vận hành thật — đây là case minh họa. Khi có dự án thật khớp bối cảnh giáo viên/chuyên gia độc lập, cập nhật phần này bằng số liệu thực tế qua CMS.*
