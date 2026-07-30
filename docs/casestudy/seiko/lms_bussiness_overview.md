# Seiko LMS — Tài Liệu Nghiệp Vụ Chi Tiết

> **Phiên bản:** 1.0.0 | **Ngày:** 2026-03-24 | **Loại:** Business Specification

---

## Mục lục

1. [Tổng quan hệ thống](#1-tổng-quan-hệ-thống)
2. [Đối tượng người dùng](#2-đối-tượng-người-dùng)
3. [Cấu trúc học vụ](#3-cấu-trúc-học-vụ)
4. [Nghiệp vụ Quản trị viên (Admin)](#4-nghiệp-vụ-quản-trị-viên-admin)
5. [Nghiệp vụ Giảng viên (Lecturer)](#5-nghiệp-vụ-giảng-viên-lecturer)
6. [Nghiệp vụ Học sinh (Student)](#6-nghiệp-vụ-học-sinh-student)
7. [Quy trình Điểm danh & Nghỉ phép](#7-quy-trình-điểm-danh--nghỉ-phép)
8. [Quy trình Bài tập](#8-quy-trình-bài-tập)
9. [Quy trình Thi cử](#9-quy-trình-thi-cử)
10. [Quy trình Đánh giá](#10-quy-trình-đánh-giá)
11. [Hệ thống Thông báo](#11-hệ-thống-thông-báo)
12. [Import/Export dữ liệu](#12-importexport-dữ-liệu)
13. [Trang Landing Page](#13-trang-landing-page)
14. [Quy tắc nghiệp vụ tổng hợp](#14-quy-tắc-nghiệp-vụ-tổng-hợp)

---

## 1. Tổng quan hệ thống

### 1.1 Mô tả tổng quát

**Seiko LMS** là hệ thống quản lý học tập được thiết kế cho các trung tâm/tổ chức giáo dục (đặc biệt phù hợp đào tạo ngoại ngữ). Hệ thống số hóa toàn bộ quy trình quản lý từ tuyển sinh, phân lớp, giảng dạy, điểm danh, bài tập, thi cử đến báo cáo.

### 1.2 Phạm vi hệ thống

```
Tuyển sinh → Phân lớp → Lịch học → Giảng dạy → Bài tập/Thi → Điểm danh → Báo cáo
```

**Ngoài phạm vi:**
- Thanh toán học phí
- Quản lý phòng học vật lý
- Video conferencing tích hợp
- Hệ thống điểm tích lũy

### 1.3 Các actor chính

| Actor | Mô tả | Quyền truy cập |
|-------|-------|---------------|
| Admin | Quản trị toàn hệ thống | Toàn quyền |
| Sub-Admin | Quản lý vận hành | Quản lý (không cấu hình hệ thống) |
| Giảng viên | Người dạy học | Quản lý lớp mình dạy |
| Học sinh | Người học | Xem và nộp bài |

---

## 2. Đối tượng người dùng

### 2.1 Admin

**Vai trò:** Quản trị toàn bộ hệ thống, thiết lập cơ cấu học vụ.

**Trách nhiệm chính:**
- Tạo và quản lý kỳ học, khóa học, lớp học
- Quản lý tài khoản tất cả các loại người dùng
- Phân công giảng viên vào lớp
- Đăng ký học sinh vào lớp
- Xem toàn bộ báo cáo và xuất Excel
- Duyệt đơn nghỉ phép của học sinh và giảng viên

**Màn hình chính:** Dashboard, Danh sách kỳ học, Quản lý người dùng

---

### 2.2 Sub-Admin

**Vai trò:** Hỗ trợ Admin trong công tác quản lý vận hành hàng ngày.

**Trách nhiệm chính:**
- Tạo và chỉnh sửa lớp học, lịch học
- Quản lý học sinh và giảng viên
- Xuất báo cáo Excel
- Duyệt đơn xin nghỉ

**Giới hạn:** Không thể quản lý tài khoản Admin khác.

---

### 2.3 Giảng viên (Lecturer)

**Vai trò:** Giảng dạy và quản lý nội dung trong lớp được phân công.

**Trách nhiệm chính:**
- Xem lịch dạy cá nhân
- Upload tài liệu học tập lên từng buổi học
- Tạo bài tập và bài kiểm tra
- Chấm điểm và nhận xét bài của học sinh
- Điểm danh học sinh trong buổi học
- Nộp đơn xin nghỉ dạy (lecturer absence)

**Giới hạn:** Chỉ thao tác được trong các lớp được phân công.

---

### 2.4 Học sinh (Student)

**Vai trò:** Tham gia học tập và thực hiện bài tập/bài thi.

**Trách nhiệm chính:**
- Xem lịch học cá nhân
- Tải xuống và xem tài liệu học tập
- Nộp bài tập và bài thi
- Xem điểm và nhận xét
- Nộp đơn xin nghỉ học (student absence)

---

## 3. Cấu trúc học vụ

### 3.1 Mô hình phân cấp

```
Project (Kỳ học/Khóa)
    ├── Course (Chương trình học)
    │       └── ClassRoom (Lớp học)
    │                   ├── ClassroomTeacher (Giảng viên dạy lớp)
    │                   ├── ClassRoomUser (Học sinh trong lớp)
    │                   └── PeriodClass (Buổi học)
    │                               ├── PeriodClassDocument (Tài liệu)
    │                               ├── Exercise (Bài tập)
    │                               │       └── ExerciseSubmit (Bài nộp)
    │                               ├── Exam (Bài thi)
    │                               │       └── ExamSubmit (Bài thi đã nộp)
    │                               └── Timesheet (Điểm danh)
```

---

### 3.2 Project (Kỳ học)

**Định nghĩa:** Đơn vị tổ chức lớn nhất. Tương ứng với một kỳ học, một khóa đào tạo, hoặc một chương trình cụ thể.

**Thuộc tính:**
- Tên kỳ học
- Địa chỉ tổ chức
- Trạng thái (Đang hoạt động / Không hoạt động)
- Ảnh đại diện
- Ngày bắt đầu
- Mức độ ưu tiên (is_priority) — xác định kỳ học mặc định hiển thị

**Quy tắc nghiệp vụ:**
- Mỗi kỳ học có thể có nhiều khóa học bên trong
- Kỳ học có `is_priority = 1` sẽ được chọn làm mặc định khi người dùng đăng nhập
- Giảng viên chỉ nhìn thấy các kỳ học có lớp mình được phân công

---

### 3.3 Course (Khóa học / Chương trình)

**Định nghĩa:** Nhóm các lớp học cùng chương trình hoặc môn học trong một kỳ.

**Thuộc tính:**
- Tên khóa học
- Mô tả
- Ngày bắt đầu
- Trạng thái

**Quy tắc nghiệp vụ:**
- Một khóa học thuộc duy nhất một kỳ học
- Học sinh được gắn với khóa học (thông qua `StudentDetail.course_id`)
- Khi xem học sinh chưa vào lớp, hệ thống lọc theo khóa học

---

### 3.4 ClassRoom (Lớp học)

**Định nghĩa:** Đơn vị học tập cơ bản, nơi học sinh được tổ chức và học tập thực tế.

**Thuộc tính:**
- Tên lớp
- Thuộc khóa học nào
- Cấp độ (N1–N5)
- Màu sắc (dùng để hiển thị trên lịch)
- Tổng số buổi học dự kiến
- Ngày bắt đầu / Ngày kết thúc
- Trạng thái (Đang học / Đã hoàn thành)
- Ghi chú

**Quy tắc nghiệp vụ:**
- Một lớp có thể có nhiều giảng viên (classroom_teachers)
- Một lớp có nhiều học sinh (class_room_users)
- Học sinh có trạng thái trong lớp: `Active` (đang học) hoặc `Inactive` (đã nghỉ/chuyển lớp)
- `time_update_status` ghi lại ngày học sinh ngừng học (dùng để lọc điểm danh)
- Khi xóa học sinh khỏi lớp → soft delete (không xóa vĩnh viễn)

---

### 3.5 PeriodClass (Buổi học)

**Định nghĩa:** Một buổi học cụ thể trong lịch của lớp.

**Thuộc tính:**
- Thuộc lớp nào
- Tên buổi học
- Ca học: Sáng (AM) hoặc Chiều (PM)
- Ngày học
- Giảng viên dạy buổi này
- Chỉ số thứ tự (index)
- Ghi chú
- Trạng thái

**Quy tắc nghiệp vụ:**
- Mỗi buổi học gắn với một giảng viên cụ thể (có thể thay đổi)
- Buổi học là nơi gắn kết tài liệu, bài tập, bài thi và điểm danh
- Ca sáng (AM) và ca chiều (PM) có thể diễn ra cùng ngày

---

## 4. Nghiệp vụ Quản trị viên (Admin)

### 4.1 Quản lý tài khoản

#### Tạo tài khoản Admin/Sub-Admin

1. Admin truy cập **Danh sách Admin** hoặc **Danh sách Sub-Admin**
2. Nhập thông tin: Tên, Email, Mật khẩu
3. Hệ thống tạo tài khoản với role tương ứng
4. Tài khoản có thể đăng nhập ngay

#### Tạo tài khoản Giảng viên

1. Admin vào **Danh sách Giảng viên** → Thêm mới
2. Nhập thông tin: Tên, Email, Số điện thoại, Địa chỉ
3. Mật khẩu mặc định: `123456`
4. Giảng viên nhận email thông báo (nếu cấu hình)
5. Giảng viên cần đổi mật khẩu sau lần đăng nhập đầu tiên

#### Tạo tài khoản Học sinh (đơn lẻ)

1. Admin vào **Danh sách học sinh** → Thêm mới
2. Nhập thông tin bắt buộc:
   - Tên học sinh
   - Email (unique trong hệ thống)
   - Mã học sinh - IMS Code (unique trong hệ thống)
3. Nhập thông tin tùy chọn:
   - Số điện thoại (10–15 chữ số)
   - Giới tính, Ngày sinh, Địa chỉ
   - Ghi chú
4. Chọn khóa học và lớp học

**Validation:**
- Email phải unique và đúng format
- IMS Code phải unique
- Số điện thoại chỉ chứa chữ số, 10–15 ký tự

---

### 4.2 Quản lý lớp học

#### Tạo lớp học mới

```
Điều kiện tiên quyết: Đã có Kỳ học và Khóa học

Bước 1: Chọn Khóa học
Bước 2: Nhập thông tin lớp
  - Tên lớp, Cấp độ, Màu sắc
  - Ngày bắt đầu, Ngày kết thúc
  - Tổng số buổi dự kiến
Bước 3: Chọn giảng viên (1 hoặc nhiều)
Bước 4: Chọn học sinh (từ danh sách chưa vào lớp của khóa học này)
Bước 5: Xác nhận → Hệ thống tạo lớp + ghi nhận đăng ký
```

#### Chỉnh sửa lớp học

- Có thể thêm/xóa học sinh và giảng viên
- Khi xóa học sinh: soft delete, không mất dữ liệu điểm danh cũ
- **Ràng buộc:** Không thể thay đổi ngày bắt đầu/kết thúc nếu đã có buổi học nằm ngoài khoảng ngày mới

#### Xem danh sách học sinh trong lớp

- Có thể lọc học sinh theo **khoảng thời gian** (chỉ hiển thị học sinh đang học trong giai đoạn đó)
- Hệ thống tính toán dựa trên `time_update_status` (ngày vào/ra lớp)

---

### 4.3 Quản lý lịch học

#### Xem lịch

- Admin xem lịch học theo tháng, theo lớp, theo khóa học
- Lịch hiển thị dạng Calendar với màu sắc theo lớp
- Có thể chuyển đổi giữa dạng Danh sách và dạng Lịch

#### Tạo/Chỉnh sửa buổi học

- Mỗi ô lịch = một buổi học
- Gán giảng viên cho từng buổi
- Ca học: AM (Sáng) hoặc PM (Chiều)
- Hệ thống cho phép một ngày có cả ca sáng lẫn ca chiều

#### Copy lịch học

- Admin có thể sao chép lịch của một tháng sang tháng khác
- Tiết kiệm thời gian nhập lịch cho các lớp có lịch cố định

#### Import lịch từ Excel

- Tải file Excel mẫu
- Điền thông tin lịch học theo template
- Upload → Hệ thống xử lý và tạo buổi học hàng loạt

---

### 4.4 Quản lý nghỉ phép Học sinh (Admin)

#### Xem danh sách đơn nghỉ phép

- Admin xem tất cả đơn nghỉ của tất cả học sinh
- Lọc theo lớp, ngày, trạng thái
- Xem ảnh minh chứng đính kèm

#### Duyệt/Từ chối đơn nghỉ

```
Học sinh nộp đơn
    ↓
Admin/Giảng viên xem đơn
    ↓
[Duyệt] → Học sinh nhận thông báo "Đã được duyệt"
[Từ chối] → Admin nhập lý do từ chối → Học sinh nhận thông báo + lý do
```

---

### 4.5 Quản lý nghỉ phép Giảng viên

#### Xem lịch nghỉ giảng viên

- Admin xem lịch nghỉ theo giảng viên và theo tháng
- Xem trạng thái: Chờ duyệt / Đã duyệt / Từ chối

#### Duyệt đơn nghỉ giảng viên

1. Vào **Danh sách nghỉ phép giảng viên**
2. Chọn đơn cần xem xét
3. Duyệt hoặc Từ chối (kèm lý do)

---

### 4.6 Dashboard

Admin thấy tổng quan:
- Tổng số học sinh, giảng viên, lớp học
- Biểu đồ hoặc số liệu tổng hợp theo kỳ học
- Lịch học trong ngày/tuần

---

## 5. Nghiệp vụ Giảng viên (Lecturer)

### 5.1 Xem lịch dạy

- Giảng viên thấy lịch của tất cả lớp được phân công
- Xem dạng tháng hoặc danh sách
- Từ lịch, click vào buổi học để xem chi tiết

---

### 5.2 Quản lý buổi học

Khi vào chi tiết buổi học, giảng viên có 4 tab chính:

| Tab | Tính năng |
|-----|-----------|
| **Thông tin** | Xem thông tin buổi học |
| **Tài liệu** | Upload, xem, xóa tài liệu |
| **Bài tập** | Tạo, chỉnh sửa, xóa, xem kết quả bài tập |
| **Bài thi** | Tạo, chỉnh sửa, xóa, chấm điểm bài thi |

---

### 5.3 Quản lý tài liệu

#### Upload tài liệu

1. Vào tab **Tài liệu** của buổi học
2. Chọn file (Word, PDF, MP3, hình ảnh, v.v.)
3. Nhập mô tả (tùy chọn)
4. Chọn ngày hiển thị (tùy chọn)
5. Upload → Tài liệu sẵn sàng cho học sinh xem

**Loại file hỗ trợ:**
- Audio (MP3, WAV)
- Microsoft Office (Word, Excel, PowerPoint)
- PDF
- Hình ảnh
- Link URL

---

### 5.4 Tạo Bài tập

#### Quy trình tạo bài tập

```
Bước 1: Vào buổi học → Tab Bài tập → Tạo mới
Bước 2: Nhập thông tin
  - Tên bài tập (bắt buộc, unique trong buổi học)
  - Loại: Vocabulary / Grammar / Reading
  - Thời gian bắt đầu, kết thúc
  - Thời gian trễ cho phép (phút)
Bước 3: Upload đề bài
  - Loại: File (upload) hoặc Link (URL)
Bước 4: Upload đáp án (tùy chọn, ẩn với học sinh)
  - Loại: File hoặc Link
Bước 5: Mô tả hướng dẫn (tùy chọn)
Bước 6: Lưu → Học sinh nhận thông báo
```

**Ràng buộc:**
- Tên bài tập không được trùng trong cùng một buổi học
- File đề bài phải có tên unique (không bị trùng với file đã upload)
- Đáp án chỉ hiển thị khi giảng viên mở khóa

---

### 5.5 Xem kết quả bài tập

1. Vào bài tập → **Xem danh sách bài nộp**
2. Hệ thống hiển thị danh sách học sinh + trạng thái:

| Trạng thái | Ý nghĩa |
|-----------|---------|
| **Đúng hạn** | Nộp trước end_date |
| **Trễ** | Nộp sau end_date (trong thời gian gia hạn) |
| **Chưa nộp** | Chưa nộp bài |

3. Click vào từng học sinh để tải file bài nộp
4. Cập nhật nhận xét cho học sinh

---

### 5.6 Tạo Bài thi

#### Quy trình tạo bài thi

```
Bước 1: Vào buổi học → Tab Bài thi → Tạo mới
Bước 2: Nhập thông tin
  - Loại bài thi: Mini Test (1) hoặc Comprehensive (2)
  - Phần thi: Vocab / Grammar / Reading / Listening / Pronunciation
  - Thang điểm (VD: "10", "100")
  - Thời gian bắt đầu, kết thúc
Bước 3: Upload đề thi (File hoặc Link)
Bước 4: Upload đáp án
Bước 5: Mô tả hướng dẫn
Bước 6: Lưu → Học sinh nhận thông báo
```

**Ràng buộc đặc biệt:**
- Mỗi buổi học chỉ được có **1 bài thi Comprehensive** (thi tổng hợp)
- Mini Test không giới hạn số lượng

---

### 5.7 Chấm điểm bài thi

```
Bước 1: Vào bài thi → Xem danh sách bài nộp
Bước 2: Chọn học sinh cần chấm
Bước 3: Xem file bài làm của học sinh
Bước 4: Nhập điểm (trong thang điểm đã cấu hình)
Bước 5: Nhập nhận xét (tùy chọn)
Bước 6: Lưu → Học sinh nhận thông báo điểm
Bước 7: Sau khi chấm xong tất cả → Đánh dấu "Đã chấm điểm xong"
```

**Kiểm soát hiển thị đáp án:**
- Giảng viên có thể mở/đóng việc hiển thị đáp án cho học sinh
- Được kiểm soát qua `active_show_answers`

---

### 5.8 Điểm danh học sinh

```
Bước 1: Vào buổi học → Tab Điểm danh
Bước 2: Hệ thống hiển thị danh sách học sinh của lớp
Bước 3: Giảng viên đánh dấu từng học sinh:
  - Có mặt
  - Vắng có phép
  - Vắng không phép
  - Đến muộn (nhập số phút)
  - Về sớm (nhập số phút)
Bước 4: Lưu điểm danh
```

---

### 5.9 Nộp đơn nghỉ dạy

1. Giảng viên vào **Đơn nghỉ của tôi**
2. Chọn ngày và buổi nghỉ
3. Nhập lý do
4. Gửi đơn → Admin nhận thông báo
5. Chờ Admin duyệt

---

## 6. Nghiệp vụ Học sinh (Student)

### 6.1 Xem lịch học

- Học sinh thấy lịch học của lớp mình
- Xem theo tháng: các buổi học được hiển thị trên lịch
- Click vào buổi học để xem nội dung

---

### 6.2 Xem nội dung buổi học

Học sinh xem trong buổi học:
- **Thông tin buổi học**: Ngày, ca, giảng viên
- **Tài liệu**: Tải xuống tài liệu học tập
- **Bài tập**: Xem đề bài, nộp bài
- **Bài thi**: Xem đề thi, nộp bài thi

---

### 6.3 Nộp bài tập

```
Bước 1: Vào bài tập đang mở
Bước 2: Tải đề bài về xem
Bước 3: Làm bài
Bước 4: Upload file bài làm
  - Có thể upload nhiều file
  - Xóa file đã upload và upload lại
Bước 5: Nộp trước deadline → Trạng thái "Đúng hạn"
       Nộp sau deadline (trong gia hạn) → "Trễ"
       Không nộp → "Chưa nộp"
```

---

### 6.4 Nộp bài thi

```
Bước 1: Vào bài thi trong thời gian quy định
Bước 2: Tải đề thi về xem
Bước 3: Làm bài
Bước 4: Upload file bài làm (hỗ trợ nhiều file)
Bước 5: Nộp bài
```

Sau khi giảng viên chấm điểm:
- Học sinh nhận thông báo
- Vào xem điểm số và nhận xét
- Xem đáp án (nếu giảng viên đã mở khóa)

---

### 6.5 Nộp đơn xin nghỉ học

```
Bước 1: Học sinh vào "Đơn xin nghỉ"
Bước 2: Điền thông tin đơn:
  - Chọn ngày nghỉ
  - Chọn ca: Sáng / Chiều
  - Chọn lý do: Ốm đau / Việc gia đình / Thi lại / Khác
  - Mô tả chi tiết
Bước 3: Upload ảnh minh chứng (giấy bệnh viện, v.v.)
Bước 4: Gửi đơn → Giảng viên/Admin nhận thông báo
Bước 5: Chờ duyệt
  [Duyệt]    → Nhận thông báo chấp thuận
  [Từ chối]  → Nhận thông báo + lý do từ chối
```

**Quy tắc:**
- Một đơn nghỉ áp dụng cho tất cả lớp của học sinh có buổi học vào ngày/ca đó
- Học sinh có thể xóa đơn nghỉ chưa được duyệt
- Học sinh có thể upload lại ảnh minh chứng sau khi gửi

---

### 6.6 Xem tài liệu học tập

- Học sinh xem tất cả tài liệu từ tất cả lớp đang học
- Lọc theo lớp, theo buổi học
- Tải xuống để học offline

---

## 7. Quy trình Điểm danh & Nghỉ phép

### 7.1 Quy trình điểm danh tổng thể

```
┌─────────────────────────────────────────────────────────────┐
│                    QUY TRÌNH ĐIỂM DANH                      │
│                                                             │
│  Học sinh                 Giảng viên              Admin     │
│     │                         │                     │      │
│     │ Xin nghỉ trước          │                     │      │
│     ├──── Nộp đơn ───────────►│                     │      │
│     │     (kèm minh chứng)    │                     │      │
│     │                         │ Xem đơn             │      │
│     │                         ├───────── Duyệt ─────►      │
│     │                         │     hoặc            │      │
│     │◄──── Thông báo ─────────┤──── Từ chối ────────►      │
│     │      kết quả            │     (+ lý do)       │      │
│     │                         │                     │      │
│     │              Ngày học diễn ra                  │      │
│     │                         │                     │      │
│     │                         ├── Điểm danh ────────────►  │
│     │                         │   từng học sinh            │
│     │                         │   (Có/Vắng/Trễ/Sớm)       │
│     │                         │                     │      │
└─────────────────────────────────────────────────────────────┘
```

### 7.2 Trạng thái điểm danh

| Trạng thái | Mã | Mô tả |
|-----------|-----|-------|
| Có mặt | - | Học sinh đến đúng giờ |
| Vắng có phép | ABSENT_PERMISSION (1) | Đã được duyệt đơn |
| Vắng không phép | ABSENT_NOT_PERMISSION (2) | Không có đơn hoặc bị từ chối |
| Đến muộn | LATE (3) | Đến sau giờ bắt đầu |
| Về sớm | EARLY (4) | Về trước giờ kết thúc |

### 7.3 Trạng thái đơn nghỉ phép

| Trạng thái | Mô tả |
|-----------|-------|
| Chờ duyệt | Học sinh đã gửi, Admin/GV chưa xử lý |
| Đã duyệt | Admin/GV chấp thuận |
| Từ chối | Admin/GV từ chối (kèm lý do) |

### 7.4 Lý do nghỉ phép

| Mã | Lý do |
|----|-------|
| 1 | Ốm đau / Bệnh tật |
| 2 | Việc gia đình |
| 3 | Thi lại / Thi bù |
| 4 | Khác |

---

## 8. Quy trình Bài tập

### 8.1 Vòng đời bài tập

```
Giảng viên tạo bài tập
        ↓
Học sinh nhận thông báo
        ↓
Thời gian mở: [start_date → end_date]
        ↓
Học sinh nộp bài
  ├── Nộp trước end_date → Trạng thái: Đúng hạn
  └── Nộp sau end_date (trong time_late) → Trạng thái: Trễ
        ↓
Giảng viên xem bài nộp
        ↓
Giảng viên nhận xét
        ↓
(Tùy chọn) Mở khóa đáp án cho học sinh xem
```

### 8.2 Phân loại bài tập

| Loại | Mã | Mô tả |
|------|-----|-------|
| Vocabulary | 1 | Bài tập từ vựng |
| Grammar | 2 | Bài tập ngữ pháp |
| Reading | 3 | Bài tập đọc hiểu |

### 8.3 Trạng thái nộp bài

| Trạng thái | Mã | Điều kiện |
|-----------|-----|-----------|
| Đúng hạn (On-time) | 1 | Nộp trước `end_date` |
| Trễ (Late) | 2 | Nộp sau `end_date` nhưng trong `time_late` |
| Chưa nộp (Not submitted) | 3 | Quá hạn, không có file nộp |

### 8.4 Quy tắc bài tập

- Mỗi học sinh chỉ có một bản nộp bài cho mỗi bài tập
- Học sinh có thể upload lại (thay thế file cũ) cho đến khi hết hạn
- Nhiều file có thể được nộp cùng lúc
- Giảng viên chỉ xem file của học sinh trong lớp mình phụ trách

---

## 9. Quy trình Thi cử

### 9.1 Vòng đời bài thi

```
Giảng viên tạo bài thi
        ↓
Học sinh nhận thông báo
        ↓
Thời gian thi: [start_date → end_date]
        ↓
Học sinh nộp bài thi (file hoặc nhiều file)
        ↓
Giảng viên xem và chấm điểm
        ↓
Nhập điểm + nhận xét → Học sinh nhận thông báo điểm
        ↓
Đánh dấu "Đã chấm xong" (is_scored = true)
        ↓
(Tùy chọn) Mở khóa đáp án cho học sinh
```

### 9.2 Phân loại bài thi

| Loại | Mã | Quy tắc |
|------|-----|---------|
| Mini Test | 1 | Không giới hạn số lượng mỗi buổi |
| Comprehensive (Thi tổng hợp) | 2 | **Tối đa 1 bài/buổi học** |

### 9.3 Phần thi

| Phần | Mã |
|------|-----|
| Vocabulary (Từ vựng) | 1 |
| Grammar (Ngữ pháp) | 2 |
| Reading (Đọc hiểu) | 3 |
| Listening (Nghe hiểu) | 4 |
| Pronunciation (Phát âm) | 5 |

### 9.4 Thang điểm

- Giảng viên tự cấu hình khi tạo bài thi (VD: "10", "100", "A-B-C")
- Điểm nhập vào khi chấm phải phù hợp với thang điểm đã đặt

### 9.5 Quy tắc thi cử

- **Kiểm soát đáp án:** Giảng viên quyết định khi nào học sinh được xem đáp án
- **is_scored:** Cờ đánh dấu giảng viên đã hoàn tất chấm điểm toàn bộ học sinh
- Học sinh chỉ thấy điểm sau khi giảng viên chấm xong cho mình

---

## 10. Quy trình Đánh giá

### 10.1 Đánh giá Học sinh

Giảng viên/Admin có thể đánh giá từng học sinh theo các tiêu chí:
- **Thách thức tháng này** (current_month_challenge)
- **Giải pháp** (solution)
- **Kết quả** (result)
- **Thách thức tháng tới** (next_month_challenge)
- **Nhận xét tổng** (comment)
- Trạng thái tham gia (Active/Inactive trong lớp)

### 10.2 Đánh giá Lớp học

- Đánh giá tổng thể về tình hình lớp
- Ghi nhận vào `review_classroom`
- Có thể dùng để theo dõi tiến độ theo tháng/kỳ

---

## 11. Hệ thống Thông báo

### 11.1 Các loại thông báo

| Mã | Sự kiện | Người gửi | Người nhận |
|----|---------|-----------|-----------|
| MES_01 | Học sinh nộp đơn nghỉ | Học sinh | Giảng viên/Admin |
| MES_02 | Học sinh cập nhật đơn nghỉ | Học sinh | Giảng viên/Admin |
| MES_03 | Đơn nghỉ được duyệt/từ chối | Admin/GV | Học sinh |
| MES_04 | Buổi học bị hủy (không dạy) | Admin | Học sinh |
| MES_05 | Buổi học không bị hủy | Admin | Học sinh |
| MES_06 | Thay đổi giảng viên | Admin | Học sinh |
| MES_07 | Tạo bài tập mới | Giảng viên | Học sinh |
| MES_08 | Học sinh nộp bài tập | Học sinh | Giảng viên |
| MES_09 | Tạo bài thi mới | Giảng viên | Học sinh |
| MES_10 | Học sinh nộp bài thi | Học sinh | Giảng viên |
| MES_11 | Hủy duyệt đơn nghỉ | Admin/GV | Học sinh |
| MES_12 | Cập nhật thời gian bài thi | Giảng viên | Học sinh |

### 11.2 Cách thức gửi thông báo

**Cơ chế:** Pusher WebSocket (Real-time)

```
Sự kiện xảy ra → Ghi vào DB (notifications table)
                → Fire NotificationEvent
                → Pusher broadcast → WebSocket
                → Client nhận ngay lập tức
```

**Badge đếm thông báo:**
- Góc trên cùng hiển thị số thông báo chưa đọc
- Tự động cập nhật real-time qua Pusher
- Khi đọc tất cả → badge biến mất

### 11.3 Trang thông báo

- Xem toàn bộ thông báo theo thứ tự mới nhất
- Đánh dấu đã đọc tất cả
- Click vào thông báo → Điều hướng đến đối tượng liên quan

---

## 12. Import/Export dữ liệu

### 12.1 Import học sinh hàng loạt

**Mục đích:** Đăng ký nhiều học sinh cùng lúc từ file Excel, thay vì nhập từng người.

**Quy trình:**

```
Bước 1: Admin tải file Excel mẫu
Bước 2: Điền thông tin học sinh vào file
  - Hàng 1+2: Header (không thay đổi)
  - Từ hàng 3: Dữ liệu học sinh
  - Các cột: Tên, Email, Mã HS, SĐT, Giới tính, Ngày sinh, Địa chỉ,
             Ghi chú, Khóa học, Lớp học
Bước 3: Upload file Excel
Bước 4: Hệ thống xử lý nền (Queue Job)
  → Validate từng dòng (7 bước kiểm tra)
  → Nếu có lỗi: Ghi nhận lỗi vào completed_jobs
  → Nếu hợp lệ: Tạo tài khoản + đăng ký lớp
Bước 5: Admin kiểm tra trạng thái ("check-job-working")
  → Xem kết quả: Thành công bao nhiêu / Lỗi ở dòng nào
```

**7 bước validation:**
1. Kiểm tra khóa học tồn tại trong kỳ học
2. Kiểm tra lớp học tồn tại trong khóa học
3. Kiểm tra email không trùng (trong file + trong DB)
4. Kiểm tra mã học sinh không trùng (trong file + trong DB)
5. Kiểm tra các trường bắt buộc (tên, email, mã HS)
6. Kiểm tra độ dài trường (tên ≤ 100 ký tự, SĐT 10–15 số)
7. Kiểm tra định dạng email (format + DNS MX)

**Mật khẩu mặc định:** `123456` — Học sinh cần đổi sau lần đầu đăng nhập.

---

### 12.2 Import lịch học

**Mục đích:** Tạo lịch học cho nhiều lớp cùng lúc từ file Excel.

**Quy trình:**
1. Tải file mẫu
2. Điền lịch học (ngày, ca, lớp, giảng viên)
3. Upload → Hệ thống tạo buổi học hàng loạt

---

### 12.3 Export Excel

| Báo cáo | Mô tả | Người dùng |
|---------|-------|------------|
| **Lịch học** | Lịch học theo lớp/giảng viên/tháng | Admin |
| **Dữ liệu học tập** | Tiến độ học tập toàn diện của từng học sinh | Admin |
| **Bài tập** | Kết quả nộp bài tập theo lớp | Admin |
| **Chấm công giảng viên** | Timesheet giảng viên theo tháng/kỳ | Admin |
| **Chấm công học sinh** | Điểm danh học sinh theo lớp/tháng | Admin |
| **Danh sách học sinh** | Roster đầy đủ thông tin học sinh | Admin |
| **Bài học** | Thông tin chi tiết từng buổi học | Admin |

---

## 13. Trang Landing Page

### 13.1 Mục đích

Trang công khai giới thiệu dịch vụ và cho phép học sinh tiềm năng đăng ký quan tâm.

### 13.2 Các trang

| Trang | URL | Mô tả |
|-------|-----|-------|
| Trang chủ | `/` | Giới thiệu dịch vụ, các khóa học |
| Đăng ký | `/register` | Form đăng ký học sinh tiềm năng |

### 13.3 Form đăng ký

**Thông tin thu thập:**
- Họ tên
- Email
- Số điện thoại
- Nội dung quan tâm

**Sau khi gửi:** Dữ liệu lưu vào bảng `advises` để Admin xem xét và liên hệ.

### 13.4 Đặc điểm

- Hỗ trợ 2 ngôn ngữ: Tiếng Việt, Tiếng Anh
- Responsive cho mobile và desktop
- Không yêu cầu đăng nhập

---

## 14. Quy tắc nghiệp vụ tổng hợp

### 14.1 Phân quyền theo tính năng

| Tính năng | Admin | Sub-Admin | GV | HS |
|-----------|:-----:|:---------:|:--:|:--:|
| Tạo/xóa kỳ học | ✅ | ✅ | ❌ | ❌ |
| Tạo/xóa khóa học | ✅ | ✅ | ❌ | ❌ |
| Tạo/xóa lớp học | ✅ | ✅ | ❌ | ❌ |
| Phân công giảng viên | ✅ | ✅ | ❌ | ❌ |
| Thêm học sinh vào lớp | ✅ | ✅ | ❌ | ❌ |
| Tạo tài liệu bài học | ✅ | ✅ | ✅ | ❌ |
| Tạo bài tập | ✅ | ✅ | ✅ | ❌ |
| Tạo bài thi | ✅ | ✅ | ✅ | ❌ |
| Chấm điểm | ✅ | ✅ | ✅ | ❌ |
| Điểm danh | ✅ | ✅ | ✅ | ❌ |
| Duyệt đơn nghỉ | ✅ | ✅ | ✅ | ❌ |
| Nộp bài tập/thi | ❌ | ❌ | ❌ | ✅ |
| Xin nghỉ học | ❌ | ❌ | ❌ | ✅ |
| Xem tài liệu | ✅ | ✅ | ✅ | ✅ |
| Import học sinh | ✅ | ✅ | ❌ | ❌ |
| Export báo cáo | ✅ | ✅ | ❌ | ❌ |

### 14.2 Quy tắc dữ liệu

| Quy tắc | Mô tả |
|---------|-------|
| Xóa mềm (Soft Delete) | Tất cả dữ liệu quan trọng không bị xóa vĩnh viễn |
| UUID | Tất cả bản ghi dùng UUID, không dùng auto-increment |
| Mật khẩu mặc định | Tài khoản mới tạo dùng mật khẩu `123456` |
| Trùng lặp | Email và IMS Code phải unique toàn hệ thống |
| Bài thi Comprehensive | Mỗi buổi học chỉ có 1 bài thi tổng hợp |
| File upload | Tên file không được trùng trong cùng buổi học |
| Điểm danh | Lưu thời gian đến muộn/về sớm (đơn vị: phút) |
| Thông báo | Mọi sự kiện quan trọng đều tạo thông báo real-time |

### 14.3 Ràng buộc toàn vẹn dữ liệu

- Không thể xóa lớp học đang có học sinh (phải xóa học sinh trước)
- Không thể thay đổi ngày lớp học nếu có buổi học nằm ngoài khoảng ngày mới
- Học sinh nghỉ (inactive) trong lớp vẫn giữ lịch sử điểm danh, bài tập
- Giảng viên chỉ xem nội dung của lớp mình được phân công

### 14.4 Luồng dữ liệu xuyên suốt

```
Tuyển sinh (Landing Page)
    ↓ Admin tạo tài khoản
Đăng ký vào hệ thống
    ↓ Gắn vào khóa học
Phân lớp
    ↓ Thêm vào ClassRoom
Học tập
    ↓ PeriodClass theo lịch
Nội dung bài học
    ↓ Documents, Exercises, Exams
Nộp bài & Điểm danh
    ↓ ExerciseSubmit, ExamSubmit, Timesheet
Đánh giá & Báo cáo
    ↓ ReviewStudent, Export Excel
Kết thúc kỳ học
```

---

*Tài liệu nghiệp vụ này được viết dựa trên phân tích toàn bộ source code và cấu trúc dữ liệu của hệ thống Seiko LMS — SourceEGLIFE*
