# HanQuocNori - Tài liệu Nghiệp vụ

> Mô tả chi tiết các luồng nghiệp vụ của hệ thống học tiếng Hàn trực tuyến

---

## Mục lục

1. [Phân quyền người dùng](#1-phân-quyền-người-dùng)
2. [Luồng đăng ký & đăng nhập](#2-luồng-đăng-ký--đăng-nhập)
3. [Luồng mua khóa học](#3-luồng-mua-khóa-học)
4. [Luồng học tập](#4-luồng-học-tập)
5. [Luồng bài thi](#5-luồng-bài-thi)
6. [Luồng lớp học 1-1](#6-luồng-lớp-học-1-1)
7. [Luồng Affiliate (Giới thiệu)](#7-luồng-affiliate-giới-thiệu)
8. [Luồng quản trị Admin](#8-luồng-quản-trị-admin)

---

## 1. Phân quyền người dùng

### Các vai trò trong hệ thống

| Vai trò | Mã | Mô tả |
|---|---|---|
| Học viên (Student) | TYPE_STUDENT = 0 | Người dùng thông thường |
| Giáo viên (Teacher) | TYPE_TEACHER = 1 | Giáo viên dạy lớp 1-1 |
| Quản trị (Admin) | TYPE_ADMIN = 2 | Quản trị hệ thống |

### Cấp độ học tập của học viên

| Cấp độ | Mã |
|---|---|
| Sơ cấp (Elementary) | 0 |
| Trung cấp (Intermediate) | 1 |
| Nâng cao (Advanced) | 2 |
| Phiên dịch (Interpreter) | 3 |

### Quyền theo vai trò

**Học viên:**
- Đăng ký, đăng nhập, quản lý hồ sơ
- Xem và mua khóa học, sách, combo
- Truy cập khóa học đã mua (trong thời hạn)
- Theo dõi tiến độ học tập
- Làm bài tập, bài thi
- Đặt và hủy lớp học 1-1
- Kích hoạt và sử dụng mã affiliate
- Đánh giá, phản hồi sau buổi học

**Giáo viên:**
- Đăng ký/đăng nhập như học viên (type=1)
- Tạo và quản lý lịch dạy (BookLesson slots)
- Dạy lớp 1-1 qua Stringee (video call)
- Xem danh sách lớp học sắp tới
- Hủy lớp học có điều kiện

**Admin:**
- Toàn quyền quản lý nội dung (khóa học, bài học, bài tập, bài thi)
- Tạo mã giảm giá và mã affiliate
- Quản lý đơn hàng, thanh toán
- Chấm điểm bài thi tự luận
- Quản lý người dùng và giáo viên
- Xem báo cáo, thống kê
- Gửi email cho người dùng

---

## 2. Luồng đăng ký & đăng nhập

### 2.1 Đăng ký tài khoản

```
Người dùng điền form
        │
        ▼
Validate dữ liệu (RegisterRequest)
  - email, fullname, phone, sex,
    birthday, level, country, password
        │
        ▼
UserService::register()
  - Bcrypt password
  - Tạo user với status = 0 (chưa kích hoạt)
  - Tạo email_verification_token
        │
        ▼
Gửi email xác thực (SendMailEvent)
        │
        ▼
Người dùng click link xác thực
        │
        ▼
AuthController: email/verify/{id}/{token}
  - Cập nhật status = 1 (ACTIVE)
  - Gửi email chào mừng
  - Redirect → http://thankyou.hanquocnori.com/
```

**Trạng thái tài khoản:**
- `status = 0`: Chưa xác thực email
- `status = 1`: Đang hoạt động (ACTIVE)

### 2.2 Đăng nhập thông thường

```
POST /auth/login
  - Validate email + password (LoginRequest)
        │
        ▼
UserService::login(email, password, rememberMe)
        │
   ┌────┴────┐
Sai mật khẩu  Đúng mật khẩu
   │              │
Trả lỗi      Tạo JWT token
             Trả token về client
```

### 2.3 Đăng nhập qua mạng xã hội (OAuth)

```
GET /auth/login/{services}          ← Facebook hoặc Google
        │
        ▼
Redirect tới trang OAuth của provider
        │
        ▼
GET /auth/login/{services}/callback
        │
   ┌────┴─────────┐
User chưa tồn tại  User đã tồn tại
   │                     │
Tạo user mới         Cập nhật face_id
(face_id, sign_up_type,  (nếu cần)
 password = facebook_id) │
   │                     │
   └────────┬────────────┘
            ▼
        Đăng nhập tự động
        Trả JWT token
```

### 2.4 Quên mật khẩu

```
POST /auth/send-email-reset-password
  - Tạo random token → lưu vào password_reset_token
  - Gửi email kèm link reset (SendMailEvent)
        │
        ▼
Người dùng click link → POST /auth/reset/password
  - Validate token
  - Cập nhật password (bcrypt)
  - Xóa password_reset_token
```

---

## 3. Luồng mua khóa học

### 3.1 Tổng quan

Hệ thống hỗ trợ 3 phương thức thanh toán:

| ID | Phương thức |
|---|---|
| 1 | Thanh toán online |
| 2 | COD (Cash On Delivery - Thu tiền khi giao hàng) |
| 3 | MoMo |

### 3.2 Luồng chi tiết

```
Bước 1: Xem danh sách khóa học
GET /course/list
  - Trả về: tên, giá, is_free, number_month_expired
        │
        ▼
Bước 2: Kiểm tra mã giảm giá (tùy chọn)
GET /price-after-discount
  - Params: course_id, discount_code, affiliate_code, type
  - Kiểm tra mã giảm giá:
      + is_active = true
      + Trong thời hạn (start_time → end_time)
      + Số lần dùng < max_of_use
      + Loại: TYPE_PERCENT (1) hoặc TYPE_MONEY (2)
  - Kiểm tra mã affiliate:
      + Không được dùng mã của chính mình
        │
        ▼
Bước 3: Tạo đơn hàng
POST /order/save
  - Lưu Order: user_id, course_id, discount_code_id,
    affiliate_code_id, payment_category_id, status=1 (đang xử lý)
  - Tính giá cuối: OrderService::getPriceOrder()
    (giá gốc - mã giảm giá - mã affiliate)
  - Lưu giá vào order.current_price_course
        │
        ▼
Bước 4: Xử lý thanh toán
```

```
┌─────────────────────────────────────────────────────────┐
│                  Xử lý theo phương thức                 │
├──────────────┬───────────────────────┬──────────────────┤
│  Online (1)  │      COD (2)          │    MoMo (3)      │
│              │                       │                  │
│ Lưu giá đã  │ PaymentService::      │ MoMo xử lý       │
│ tính toán    │ saveShipcod()         │ thanh toán       │
│              │ Lưu thông tin giao    │                  │
│              │ hàng, địa chỉ        │ POST /notify-momo │
│              │                       │ (webhook callback)│
│              │                       │                  │
│              │                       │ errorCode == 0?  │
│              │                       │ ✓ Thành công     │
│              │                       │ ✗ Thất bại       │
└──────────────┴───────────────────────┴──────────────────┘
```

**Khi thanh toán thành công (MoMo webhook):**
```
errorCode == 0
        │
        ▼
Cập nhật Order.status = 2 (thành công)
        │
        ▼
Tạo CourseManager record
  - active_date = hôm nay
  - expiration_date = hôm nay + course.number_month_expired tháng
  - order_id = order vừa thanh toán
        │
        ▼
Gửi thông báo cho user
Tạo message: "Thanh toán hóa đơn thành công"
```

**Khi thanh toán thất bại:**
```
errorCode != 0
        │
        ▼
Cập nhật Order.status = 3 (thất bại)
Tạo message: "Thanh toán hóa đơn thất bại"
```

### 3.3 Trạng thái đơn hàng

| status | Ý nghĩa |
|---|---|
| 1 | Đang xử lý (process) |
| 2 | Thành công (success) |
| 3 | Thất bại (failure) |

### 3.4 Kiểm tra quyền truy cập khóa học

```
CourseManager::checkActive(user_id, course_slug)
  - Kiểm tra expiration_date >= ngày hiện tại
  ✓ Còn hạn → Cho truy cập
  ✗ Hết hạn → Chặn truy cập
```

---

## 4. Luồng học tập

### 4.1 Truy cập khóa học

```
GET /course/list
        │
   ┌────┴──────────────┐
is_free = 1           is_free = 0
(Miễn phí)            (Trả phí)
   │                       │
Cho phép truy cập     Kiểm tra CourseManager
toàn bộ               (checkActive)
                           │
                      ┌────┴────┐
                   Hết hạn   Còn hạn
                      │          │
                  Chặn truy cập  Cho phép
```

### 4.2 Xem danh sách bài học

```
GET /lesson/list?course_slug={slug}
        │
   ┌────┴──────────────────────────┐
Khóa học miễn phí            Khóa học trả phí
   │                               │
Trả tất cả bài học           Kiểm tra quyền truy cập
(status=2 active)                  │
                        ┌──────────┴──────────┐
                    Chưa mua             Đã mua (còn hạn)
                        │                      │
                  Chỉ trả bài học        Trả tất cả bài học
                  is_free=1              (status=2)
```

> Cache: Redis key `nori:lesson-menu-active-by-course-{slug}-by-{user_id}`

### 4.3 Xem nội dung bài học

```
GET /lesson/detail?course_slug={slug}&lesson_slug={slug}
        │
   ┌────┴─────────────┐
Có quyền truy cập    Không có quyền
   │                       │
Trả nội dung đầy đủ  Trả nội dung giới hạn
(status=2 unlocked)  (status=1 locked)
```

**Nội dung bài học bao gồm:**
- Văn bản, tài liệu
- Video (DASH streaming)
- Audio
- Bài tập liên quan

### 4.4 Theo dõi tiến độ học

```
POST /lesson/process/save
  - Lưu LessonProcess (phần trăm hoàn thành)
        │
        ▼
LessonService::calculateLessonProcess()
  - Tính tiến độ bài học
        │
        ▼
LessonService::calculateCourseProcess()
  - Tính % hoàn thành toàn bộ khóa học
        │
        ▼
Xóa Redis cache (lesson menu)
```

### 4.5 Các loại bài tập

| Loại | Mã | Mô tả |
|---|---|---|
| Flashcard | 1 | Học từ vựng qua thẻ ghi nhớ |
| Nghe hiểu | 4 | Bài tập nghe |
| Đọc hiểu | 5 | Bài tập đọc |
| Viết | 7 | Bài tập viết |
| Dịch/Điền từ | 21 | Dịch thuật và điền vào chỗ trống |

**Flashcard:** FlashcardService::getFlashCards() trả về cặp từ/nghĩa để học viên ôn tập.

---

## 5. Luồng bài thi

### 5.1 Cấu trúc dữ liệu bài thi

```
ExamMeta (Kỳ thi tổng thể)
  └── Exam (Đề thi cụ thể)
        └── ExamConfig (Cấu hình: listening/reading/writing)
              └── ExamSchedule (Lịch thi)
                    └── TakeExam (Lần thi của user)
                          └── TakeAnswer (Câu trả lời từng câu)
```

### 5.2 Luồng thi

```
Bước 1: Kiểm tra quyền thi
GET /exam/permission?exam_schedule_id={id}
  - Kiểm tra user đã thi lần này chưa (TakeExam table)
        │
        ▼
Bước 2: Lấy thông tin đề thi
GET /exam/detail?exam_slug={slug}&exam_config_id={id}
  - Trả về cấu trúc đề (không có câu hỏi)

GET /exam/meta/detail?exam_meta_slug={slug}
  - Trả về thông tin kỳ thi + lịch thi
        │
        ▼
Bước 3: Lấy câu hỏi
GET /question/list?exam_id={id}
  - Trả về toàn bộ câu hỏi
  - Gồm: câu hỏi nhóm (đoạn văn + nhiều câu hỏi con)
    và câu hỏi độc lập
        │
        ▼
Bước 4: Học viên làm bài
  - Chọn đáp án (trắc nghiệm)
  - Nhập câu trả lời (tự luận)
        │
        ▼
Bước 5: Nộp bài
POST /exam/take-answer/submit
  {
    miss_exams: [],           // câu bỏ qua
    take_answers: [
      { question_id, answer_id, user_answer }
    ],
    exam_schedule_id,
    exam_slug,
    exam_meta_slug,
    exam_config_id
  }
        │
        ▼
ExamService::saveTakeExam()       ← Tạo TakeExam record
ExamService::saveTakeAnswerList() ← Lưu từng câu trả lời
ExamService::calculateScore()     ← Chấm điểm tự động
        │
   ┌────┴───────────┐
Trắc nghiệm       Tự luận (writing)
   │                   │
Chấm tự động       Đánh dấu is_send_exam_writting=1
(so sánh answer_id) Chờ giáo viên/admin chấm thủ công
   │                   │
   └────────┬──────────┘
            ▼
  Cập nhật TakeExam.exam_score
  Trả kết quả cho học viên
```

### 5.3 Xem kết quả & bảng xếp hạng

```
GET /take-exam/detail/
  - Params: user_id, exam_meta_slug, exam_schedule_id
  - Trả về: điểm thi, trạng thái bài tự luận,
    câu trả lời từng câu có review

GET /exam/ranking/list
  - Trả về: danh sách thí sinh xếp theo điểm (exam_score)
```

### 5.4 Chấm thi tự luận (Admin)

```
GET /mark-exam/get-score  (Admin)
  - Lấy danh sách bài tự luận cần chấm
        │
        ▼
Admin xem và cập nhật điểm
  - Nhập điểm cho từng câu tự luận
```

---

## 6. Luồng lớp học 1-1

### 6.1 Mô hình dữ liệu

```
ComboCoursesOneOne (Gói khóa học)
  - number_lesson: Số buổi học
  - price: Giá gói

NumberLesson (Số buổi còn lại của user)
  - user_id, number_lesson

BookLesson (Lịch dạy khả dụng)
  - date_time_start: Thời gian bắt đầu
  - teacher_id: Giáo viên
  - status_choose: 0=chưa đặt, 1=đã đặt, 2=hoàn thành

HistoryLessonStudent (Lịch sử đặt lớp)
  - student_id, teacher_id, book_lesson_id
  - date_time_start
  - active_flg: 0=chưa bắt đầu, 1=đang học, 2=hoàn thành
  - convId: ID cuộc gọi Stringee
```

### 6.2 Mua gói lớp học 1-1

```
Học viên mua ComboCoursesOneOne
        │
        ▼
NumberLesson.number_lesson += combo.number_lesson
(Cộng thêm số buổi học vào tài khoản học viên)
```

### 6.3 Đặt lịch học

```
GET /get-learner-booking?date={Y/m/d}
  - Lấy danh sách slot khả dụng trong ngày
        │
        ▼
POST /create-book-lesson
  - Params: date_time_start, student_id
        │
        ▼
Kiểm tra 1: Học viên còn buổi học?
  NumberLessonService::getNumberLessonUser() > 0
        │
        ▼
Kiểm tra 2: Slot còn trống?
  StudentService::getIdBookLesson() → tìm BookLesson phù hợp
        │
        ▼
Kiểm tra 3: Không trùng lịch?
  ConditionActiveBookLessonService::checkActiveDateBookLessonStudent()
        │
        ▼
Đặt lịch thành công:
  - BookLesson.status_choose = 1 (đã đặt)
  - NumberLesson.number_lesson -= 1
  - Tạo HistoryLessonStudent record
  - Gửi email thông báo (SendMailEvent)
  - Gửi push notification (SendNotification event)
```

### 6.4 Hủy lịch học

```
POST /cancel-book-lesson
        │
        ▼
Kiểm tra thời gian hủy:
  - Phải hủy trước ít nhất X giờ (config: time_cancel_book_lesson)
  - dateTimeLate = now + X giờ
  - Nếu lesson.date_time_start <= dateTimeLate → KHÔNG được hủy
        │
   ┌────┴────────────────────┐
Trong thời hạn hủy         Quá thời hạn hủy
   │                              │
Hủy thành công:             Trả lỗi,
  - HistoryLessonStudent        không cho hủy
    status_cancel = 1
  - NumberLesson += 1
    (hoàn lại 1 buổi)
  - Gửi email thông báo hủy
  - Tạo message: "Lớp học ... đã bị hủy"
```

### 6.5 Vào phòng học (Video call)

```
GET /get-access-token
        │
        ▼
Tạo JWT token Stringee:
  - apiKeySid: 'SKFFTfAFmwLeqP0BaQNqPRiKUKOVN1xPe2'
  - exp: now + 3600 giây
  - userId: 'user_call_{history_lesson_student_id}_{student_id}'
        │
        ▼
Trả về:
  - access_token (Stringee JWT)
  - student_id, teacher_id
  - convId (ID cuộc gọi)
  - Thông tin học viên và giáo viên
        │
        ▼
Học viên tham gia phòng học qua Stringee SDK
```

### 6.6 Sau buổi học

```
POST /update-conv-id
  - Lưu Stringee convId để tra lại video recording

POST /save-feedback
  - Học viên đánh giá buổi học (FeedbackBookLesson)

POST /student-review
  - Học viên cho điểm giáo viên
```

### 6.7 Theo dõi lịch học

```
GET /get-count-down-lesson
  - Trả về buổi học tiếp theo (để đếm ngược)

GET /get-history-book-lesson?start_date={date}
  - Lịch sử tất cả buổi học đã qua
```

---

## 7. Luồng Affiliate (Giới thiệu)

### 7.1 Tổng quan

Hệ thống affiliate cho phép admin tạo mã giới thiệu. Khi người dùng mua hàng qua mã này, họ được giảm giá. Mã được liên kết với từng người dùng cụ thể.

### 7.2 Loại mã affiliate

| Loại áp dụng | Mã | Loại giảm giá |
|---|---|---|
| Mua khóa học | BUY_COURSE = 1 | TYPE_PERCENT (1) hoặc TYPE_MONEY (2) |
| Mua sách | BUY_BOOK = 2 | TYPE_PERCENT (1) hoặc TYPE_MONEY (2) |
| Mua combo | BUY_COMBO = 3 | TYPE_PERCENT (1) hoặc TYPE_MONEY (2) |

### 7.3 Luồng tạo và sử dụng mã

```
Admin tạo mã affiliate (POST /affiliate-code/create)
  - code_number: Mã duy nhất
  - amount_money: Số tiền hoặc % giảm
  - number_of_use: Số lần tối đa được dùng
  - type: 1=percent, 2=fixed_money
  - affiliate_code_type: course/book/combo
        │
        ▼
Kích hoạt mã cho người dùng
GET /active-code?user_id={id}&code_number={code}
  - ManagerAffiliateCodeService::activeCode()
  - Liên kết mã với user (User.affiliate_id)
        │
        ▼
Người dùng áp mã khi mua hàng
GET /price-after-discount?affiliate_code={code}&type=2
        │
        ▼
Kiểm tra hợp lệ:
  ✓ Mã tồn tại và is_active = true
  ✓ Người dùng không dùng mã của chính mình
  ✓ Còn số lần sử dụng
        │
        ▼
Tính giá sau giảm:
  - BUY_COURSE: OrderService::getPriceAfterApplyAffiliateCode()
  - BUY_COMBO: OrderService::getPriceAfterApplyAffiliateCodeCombo()
  - BUY_BOOK: Áp dụng discount trực tiếp
        │
        ▼
Ghi nhận vào đơn hàng: Order.affiliate_code_id
```

### 7.4 Theo dõi (Admin)

```
POST /affiliate-code/list-active-code
  - Xem danh sách khóa học/sách/combo mã có thể dùng
  - Xem danh sách người dùng đã sử dụng mã
```

---

## 8. Luồng quản trị Admin

### 8.1 Quản lý nội dung khóa học

```
Tạo khóa học (Course)
  → Thêm bài học (Lesson)
      → Upload media (video/audio/ảnh lên AWS S3)
      → Tạo bài tập (Exercise)
          → Thêm câu hỏi (Question)
          → Thêm đáp án (Answer), đánh dấu đáp án đúng
```

**Loại bài tập có thể tạo:**

| Loại | Mô tả |
|---|---|
| Trắc nghiệm | Chọn đáp án đúng |
| Điền vào chỗ trống | Nhập từ còn thiếu |
| Nghe hiểu | Nghe audio rồi trả lời |
| Đọc hiểu | Đọc đoạn văn rồi trả lời |
| Hội thoại | Bài tập theo dạng đối thoại |
| Dịch thuật | Dịch câu/đoạn văn |
| Flashcard | Tạo bộ thẻ từ vựng |

### 8.2 Quản lý bài thi

```
Tạo ExamMeta (Kỳ thi)
  → Tạo ExamConfig (Cấu hình phần nghe/đọc/viết)
      → Tạo Exam (Đề thi)
          → Thêm Question + Answer
      → Tạo ExamSchedule (Lịch thi cụ thể)
```

**Sau khi học viên nộp bài:**
- Bài trắc nghiệm: Chấm điểm tự động ngay lập tức
- Bài tự luận: Admin vào `GET /mark-exam/get-score` để chấm thủ công

### 8.3 Quản lý đơn hàng

```
Xem danh sách đơn hàng → Lọc theo trạng thái/ngày
        │
        ▼
Cập nhật trạng thái đơn hàng (thủ công nếu cần)
        │
        ▼
Khi xác nhận thanh toán thành công:
  → Tạo CourseManager (cấp quyền truy cập cho học viên)
  → Gửi email xác nhận
```

### 8.4 Quản lý lớp học 1-1

```
Admin tạo BookLesson (slot thời gian khả dụng)
  - date_time_start: Thời điểm bắt đầu
  - teacher_id: Giáo viên phụ trách
  - active_flg: Kích hoạt/tắt slot
        │
        ▼
Học viên đặt lịch (xem luồng 6.3)
        │
        ▼
Admin theo dõi trạng thái lớp học
  - 0: Chưa đặt
  - 1: Đã đặt
  - 2: Đã hoàn thành
```

### 8.5 Quản lý mã giảm giá

```
Tạo DiscountCode:
  - code: Mã code
  - type: TYPE_PERCENT (1) hoặc TYPE_MONEY (2)
  - amount: Giá trị giảm
  - max_of_use: Số lần tối đa
  - start_time / end_time: Thời hạn hiệu lực
  - Gắn với khóa học/combo cụ thể
```

---

## Tóm tắt các sự kiện bất đồng bộ (Events & Jobs)

| Sự kiện | Kích hoạt khi | Kết quả |
|---|---|---|
| `SendMailEvent` | Đăng ký, đặt lịch, hủy lịch, thanh toán | Gửi email thông báo |
| `SendNotification` | Đặt lịch học, nhận thông báo mới | Push notification realtime |
| MoMo webhook | Thanh toán MoMo hoàn tất | Cấp quyền khóa học cho user |
| Queue Worker | Xử lý email/notification | Chạy nền qua Redis queue |

---

## Các ràng buộc nghiệp vụ quan trọng

| Nghiệp vụ | Ràng buộc |
|---|---|
| Truy cập khóa học | Phải thanh toán + còn hạn sử dụng |
| Sử dụng mã affiliate | Không dùng mã của chính mình |
| Hủy lớp học 1-1 | Phải hủy trước X giờ (cấu hình trong constants) |
| Đặt lịch học 1-1 | Phải có số buổi học > 0, không được trùng lịch |
| Mã giảm giá | Phải trong thời hạn và còn số lần sử dụng |
| Xác thực email | User phải xác thực email mới kích hoạt tài khoản |

---

*Tài liệu nghiệp vụ được tạo: 2026-03-24*
