# Đề xuất Case Study còn thiếu

> Ngày viết: 2026-07-30
> Mục tiêu website: triển khai giải pháp giáo dục cho 5 nhóm khách hàng theo menu "Dành cho ai" — Chuyên gia & Giáo viên độc lập, Trung tâm đào tạo, Trường học, EdTech Startup, Đào tạo nội bộ doanh nghiệp.
> Tài liệu này ghi lại đánh giá khoảng trống case study hiện có và các hướng cần bổ sung. Chưa có dự án/dữ liệu thật đi kèm cho phần còn thiếu — đây là đề xuất hướng, cần dữ liệu thật (dự án đã làm, hoặc tài liệu nghiệp vụ chi tiết) trước khi viết seeder thật cho `ProjectsSeeder.php`, tránh bịa số liệu/tính năng.

---

## 1. Hiện trạng case study đối chiếu với 5 nhóm khách hàng mục tiêu

| Nhóm khách hàng (menu "Dành cho ai") | Case study phủ được | Category hiện gán |
|---|---|---|
| Chuyên gia & Giáo viên độc lập | ❌ chưa có | — |
| Trung tâm đào tạo | ✅ Seiko LMS, HanQuocNori | `lms-center`, `edtech-platform` |
| Trường học | ❌ chưa có | — |
| EdTech Startup | ✅ TopThi (living lab thử nghiệm Exam Engine/Learning Analytics trước khi đóng gói sản phẩm — đúng mô hình startup) | `exam-ai-learning` |
| Đào tạo nội bộ doanh nghiệp | ⚠️ MSD gần giống nhưng là NGO/tổ chức phi lợi nhuận, không phải doanh nghiệp — chưa đúng bối cảnh | `lms-community` |

**Nhận xét:** 2/5 nhóm được phủ tốt (Trung tâm đào tạo, EdTech Startup). Còn thiếu **3 nhóm**: Giáo viên độc lập, Trường học, và Đào tạo nội bộ doanh nghiệp (MSD chỉ gần giống, không thay thế được vì khác bối cảnh NGO vs. doanh nghiệp). Rủi ro: khách ghé đúng 3 mục menu này sẽ không thấy case study nào "giống mình".

---

## 2. Ba case study cần bổ sung

### 2.1 Corporate L&D / Đào tạo nội bộ doanh nghiệp

**Vì sao ưu tiên:** đây là mảng có ngân sách cao nhất trong các nhóm khách hàng mục tiêu, và case gần nhất hiện có (MSD) là NGO chứ không phải doanh nghiệp — chưa đủ thuyết phục cho khách hàng thuộc mục menu này.

**Điểm nhấn nghiệp vụ/kỹ thuật nên có:**
- Onboarding nhân viên mới (lộ trình học tự động theo ngày gia nhập)
- Compliance/mandatory training với cơ chế tracking bắt buộc hoàn thành, hạn chót, nhắc nhở
- Tích hợp org chart/phòng ban để assign khóa học theo vai trò, phòng ban, cấp bậc
- Báo cáo tuân thủ (compliance report) cho HR/quản lý — ai đã hoàn thành, ai còn thiếu
- (Nếu có) tích hợp SCORM/xAPI để nhập nội dung e-learning chuẩn công nghiệp
- Khác biệt với MSD (NGO): đây là bối cảnh doanh nghiệp, KPI đào tạo gắn với hiệu suất/tuân thủ chứ không phải tác động xã hội

**Loại dự án cần tìm:** hệ thống LMS nội bộ đã triển khai cho một doanh nghiệp/tổ chức thương mại (không phải trung tâm dạy học, không phải NGO), hoặc module đào tạo nhân sự trong một ERP/HRM.

---

### 2.2 Trường học K-12 hoặc trung tâm giáo dục chính quy

**Điểm nhấn nghiệp vụ/kỹ thuật nên có:**
- Quản lý theo năm học/học kỳ/lớp cố định (khác "lớp mở linh hoạt" theo khóa của trung tâm)
- Sổ điểm, học bạ, xếp loại học lực theo quy chế
- Liên lạc phụ huynh–giáo viên (thông báo, sổ liên lạc điện tử)
- Thời khóa biểu theo môn học chính quy, phân công giáo viên theo môn/lớp cố định cả năm học
- Khác biệt với Seiko (trung tâm ngoại ngữ): mô hình trường học có cấu trúc năm học/học kỳ cứng, nhiều môn học song song, và vai trò phụ huynh mà các case hiện tại không có

**Loại dự án cần tìm:** hệ thống quản lý trường học (School Management System) đã triển khai cho một trường phổ thông, hoặc trung tâm giáo dục vận hành theo mô hình chính quy.

---

### 2.3 Công cụ cho Chuyên gia & Giáo viên độc lập

**Điểm nhấn nghiệp vụ/kỹ thuật nên có:**
- Nhẹ, không cần bộ máy admin nhiều vai trò như các case hiện tại (đều là hệ thống lớn: admin/giảng viên/học sinh + nhiều module)
- Một giáo viên/chuyên gia tự tạo lớp, quản lý học viên riêng của mình
- Thu học phí nhỏ lẻ, theo dõi công nợ học viên cá nhân
- Lịch dạy cá nhân, đặt lịch với từng học viên (tương tự phần lớp 1-1 trong HanQuocNori nhưng ở quy mô cá nhân, tự vận hành, không qua trung tâm)
- Khác biệt cốt lõi: đây là mô hình self-serve SaaS (giáo viên/chuyên gia là khách hàng trực tiếp), không phải hệ thống được một tổ chức lớn mua và vận hành nội bộ

**Loại dự án cần tìm:** một SaaS nhỏ/mini app cho gia sư, huấn luyện viên, hoặc chuyên gia tự do quản lý học viên, lịch dạy, học phí.

---

## 3. Việc cần làm tiếp theo

1. Với mỗi hướng ở mục 2, tìm dự án thật đã từng thực hiện (kể cả dự án cũ, dự án phụ, dự án đang làm dở) khớp nghiệp vụ.
2. Đặt tài liệu nghiệp vụ/kỹ thuật vào `docs/casestudy/<tên-dự-án>/` theo đúng cấu trúc đã dùng cho hanquocnori/msd/seiko (project overview, business overview, technical overview — hoặc case-study + functional-spec).
3. Đọc tài liệu, đối chiếu, rồi bổ sung `seed<TênDựÁn>()` vào `database/seeders/ProjectsSeeder.php`, gán đúng category (tạo category mới nếu cần, theo cơ chế `seedCategories()` hiện có) — cân nhắc đặt category theo đúng nhóm menu "Dành cho ai" (giáo viên độc lập / trung tâm / trường học / EdTech Startup / đào tạo nội bộ doanh nghiệp) để dễ lọc hiển thị theo đối tượng trên trang case study.
4. Không bịa số liệu vận hành (metrics) nếu tài liệu nguồn không có — để trống và điền qua CMS khi có số liệu thật, đúng nguyên tắc đã áp dụng cho các case trước.
