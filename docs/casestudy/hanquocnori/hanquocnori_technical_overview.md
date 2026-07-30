# HanQuocNori - Tài liệu Kỹ thuật Chi tiết

> Mô tả kỹ thuật đầy đủ về kiến trúc, database, API và cấu hình hệ thống

---

## Mục lục

1. [Kiến trúc tổng thể](#1-kiến-trúc-tổng-thể)
2. [Backend - Laravel 7](#2-backend---laravel-7)
3. [Database Schema](#3-database-schema)
4. [Frontend - Nuxt.js 2](#4-frontend---nuxtjs-2)
5. [Admin - Vue.js 2](#5-admin---vuejs-2)
6. [API Routes](#6-api-routes)
7. [Authentication & JWT](#7-authentication--jwt)
8. [Hệ thống Cache & Queue](#8-hệ-thống-cache--queue)
9. [Tích hợp bên thứ ba](#9-tích-hợp-bên-thứ-ba)
10. [Dependencies & Versions](#10-dependencies--versions)
11. [Cấu hình môi trường](#11-cấu-hình-môi-trường)

---

## 1. Kiến trúc tổng thể

```
┌─────────────────────┐     ┌─────────────────────┐
│   front-hanquocnori │     │  admin-hanquocnori   │
│   Nuxt.js 2 (SSR)   │     │  Vue.js 2 + Vuetify │
│   Port: 3000        │     │  Port: 8080          │
└──────────┬──────────┘     └──────────┬───────────┘
           │  REST/JSON                │  REST/JSON
           │  Bearer Token             │  Bearer Token
           ▼                           ▼
┌─────────────────────────────────────────────────┐
│              api-hanquocnori                    │
│              Laravel 7.0                        │
│   ┌─────────────────────────────────────────┐   │
│   │  Routes → Middleware → Controller       │   │
│   │          ↓                              │   │
│   │   Service Layer (Business Logic)        │   │
│   │          ↓                              │   │
│   │   Repository Layer (Data Access)        │   │
│   │          ↓                              │   │
│   │   Eloquent Models                       │   │
│   └─────────────────────────────────────────┘   │
└──────────────────┬──────────────────────────────┘
                   │
       ┌───────────┼────────────┐
       ▼           ▼            ▼
   MySQL DB    Redis          AWS S3
 (Persistent) (Cache/Queue)  (Media Files)
```

---

## 2. Backend - Laravel 7

### 2.1 Design Pattern

Dự án áp dụng **Repository Pattern + Service Layer**:

```
Controller → Service → Repository → Eloquent Model → MySQL
```

| Lớp | Thư mục | Trách nhiệm |
|---|---|---|
| Controller | `app/Http/Controllers/` | Nhận request, trả response |
| Service | `app/Services/` | Business logic |
| Repository | `app/Repositories/` | Data access, query |
| Model | `app/Models/` | Eloquent ORM, relationships |
| Job | `app/Jobs/` | Xử lý bất đồng bộ (queue) |
| Event | `app/Events/` | Event broadcasting |

### 2.2 Middleware

| Middleware | Mục đích |
|---|---|
| `Authenticate` | Validate JWT Bearer token, trả lỗi 401 nếu invalid |
| `AdminRole` | Kiểm tra quyền admin (guard + type validation) |
| `RedirectIfAuthenticated` | Chặn user đã đăng nhập vào route auth |
| `CheckActiveCourse` | Kiểm tra quyền truy cập khóa học |
| `CheckType` | Validate user type |
| `SocialMiddleware` | Xử lý social login |
| `Cors` | CORS headers (wildcard origin, credentials) |
| `TrustProxies` | Cấu hình reverse proxy |
| `VerifyCsrfToken` | CSRF protection |
| `TrimStrings` | Auto-trim input strings |

### 2.3 Services

```
app/Services/
├── BaseService.php              ← Abstract base, checkCollectionNotNull()
├── CourseService.php            ← courseDetail(), checkUserBoughtCourse()
├── UserService.php              ← register(), login()
├── OrderService.php             ← getPriceOrder(), checkDiscountCode()
├── ExamService.php              ← saveTakeExam(), calculateScore()
├── LessonService.php            ← calculateLessonProcess(), calculateCourseProcess()
├── FlashcardService.php         ← getFlashCards()
├── StudentService.php           ← getIdBookLesson(), updateStatusChoose()
├── NumberLessonService.php      ← getNumberLessonUser(), updateNumberLesson()
├── ManagerAffiliateCodeService  ← activeCode()
└── ConditionActiveBookLessonService ← checkActiveDateBookLessonStudent()
```

### 2.4 Repositories

Có **47+ repositories** theo pattern:

```
app/Repositories/
├── Contracts/
│   ├── RepositoryInterface.php
│   ├── UserRepositoryInterface.php
│   ├── CourseRepositoryInterface.php
│   └── ... (interface cho từng entity)
└── Eloquent/
    ├── EloquentRepository.php        ← Base: getAll, getById, create, update, delete, findWhere, paginate
    ├── UserEloquentRepository.php
    ├── CourseEloquentRepository.php
    ├── OrderEloquentRepository.php
    ├── ExamEloquentRepository.php
    ├── TakeExamEloquentRepository.php
    ├── CourseManagerEloquentRepository.php
    ├── LessonEloquentRepository.php
    ├── ExerciseEloquentRepository.php
    ├── QuestionEloquentRepository.php
    ├── BookLessonEloquentRepository.php
    ├── HistoryLessonStudentRepository.php
    ├── NumberLessonRepository.php
    └── ...
```

### 2.5 Async Jobs (Queue)

| Job | Kích hoạt khi | Chức năng |
|---|---|---|
| `BoughtCourseSuccess` | Mua khóa học thành công | Gửi email xác nhận mua hàng (template: `email.order`) |
| `ProcessEncryptVideo` | Upload video | Mã hóa/xử lý video |
| `SendEmailCancleLesson` | Hủy lịch học 1-1 | Gửi email thông báo hủy |

### 2.6 Events

| Event | Dữ liệu | Kết quả |
|---|---|---|
| `SendMailEvent` | `$information` (email, subject, data, htmlContent) | Gửi email qua queue |
| `SendNotification` | Notification data | Push notification real-time (Pusher) |
| `KeyExamRedis` | Exam cache key | Xóa cache Redis của bài thi |
| `KeyLessonRedis` | Lesson cache key | Xóa cache Redis của bài học |

### 2.7 Constants (`config/constants.php`)

**File types:**
```
file_image_type = 0
file_video_type = 1
file_audio_type = 2
file_pdf_type   = 3
```

**Exercise types (21 loại):**
```
FLASHCARD                 = 1
TRANSLATE_FILL_WORD       = 21
LISTENING                 = 4
READING                   = 5
WRITING                   = 7
GRAMMAR                   = ...
READING_COMMON            = ...
READING_PRIVATE           = ...
LISTENING_WITH_ANSWERS    = ...
```

**Exam configuration:**
```
topicI_reading    = 2    topicI_listening   = 1
topicII_reading   = 4    topicII_listening  = 3    topicII_writing = 5

exam_level:   topicI (1), topicII (2)
exam_types:   listening (1), reading (2), writing (3)
question_types: select (1), fill (2)
question_score: 2 points/question

exam_status:    inactive (0), active (1)
schedule_status: not_happen (0), done (1), doing (2)
group_question_type: general (1), custom (2)
```

**Khác:**
```
sign_up_types: normal (1), facebook (2)
time_cancel_book_lesson: 5 hours (trước buổi học)
```

---

## 3. Database Schema

Tổng cộng **186 migration files** (2020–2022), **76 Eloquent models**.

### 3.1 Bảng người dùng

**`users`**
```
id, email, password, remember_token
full_name, phone_number
sex             (0: Nam, 1: Nữ)
birthday, level (0: Sơ cấp, 1: Trung cấp, 2: Nâng cao, 3: Phiên dịch)
type            (0: Student, 1: Teacher, 2: Admin)
country, avatar_url, status
face_id         — Facebook OAuth ID
affiliate_id    — FK → affiliate_codes
teacher_id      — FK → teachers
password_reset_token
info_content, video_id
fb_link, yt_link, insta_link, pinterest_link, zalo_link
sign_up_type    (1: normal, 2: facebook)
email_verified_at, created_at, updated_at
```

### 3.2 Bảng khóa học & học tập

**`courses`**
```
id, title, price, description, introduction
status              (0: Lock, 1: Unlock)
lesson_number, number_month_expired
image_id, responsive_image_id
is_recommend, is_free, is_deal
category_id, url_lading_page, not_buy
slug (auto-generated), meta
deleted_at (soft delete)
```

**`lessons`**
```
id, title, description, lesson_index
status  (0: Lock, 1: Unlock)
type    (0: init, 1: content, 2: exercise, 3: flashcard)
content (text), slug, reference_id, course_id
is_free, active, time, order
seo fields (meta_title, meta_description)
```

**`exercises`**
```
id, title, description
type    — loại bài tập (từ constants)
course_id, lesson_id, status
seo fields
```

**`lesson_processes`**
```
id, user_id, lesson_id, course_id
process     — % hoàn thành (decimal)
created_at, updated_at
```

**`course_managers`** — Quyền truy cập khóa học
```
id, user_id, course_id, order_id
active_date      — ngày kích hoạt
expiration_date  — ngày hết hạn
```

**`flashcards`**
```
id, korean, vietnamese
image_id, audio_id, course_id
```

### 3.3 Bảng bài tập

**`exercised_questions`**
```
id, content, content_translated
exercise_id, group_id, index, audio_id
```

**`exercised_answers`**
```
id, exercised_question_id
content, is_answer, description, updated_answer
```

**`exercised_translates`** — Bài tập dịch thuật

**`filled_words`**, **`linked_words`** — Bài tập điền từ

**`reading_materials`**
```
id, content, group_question_id, index
```

**`listening_materials`**
```
id, title, audio, group_question_id, index
```

### 3.4 Bảng bài thi

**`exam_metas`** — Kỳ thi tổng thể
```
id, title, question_number
start_date_time, status
level           (1: topicI, 2: topicII)
slug, exam_duration
```

**`exams`** — Đề thi cụ thể
```
id, max_score, exam_type, exam_duration
question_number, exam_meta_id
slug, is_clone, order
```

**`exam_configs`** — Cấu hình đề thi (listening/reading/writing sections)

**`exam_schedules`** — Lịch thi
```
id, exam_meta_id, start_date, end_date
status  (0: not_happen, 1: done, 2: doing)
```

**`exam_group_questions`** — Nhóm câu hỏi (đoạn văn đọc hiểu, bài nghe)

**`exam_questions`** — Liên kết câu hỏi với đề thi

**`questions`**
```
id, content (mediumtext)
type        (1: select, 2: fill)
original_exam_id, group_question_id
score_per_question, audio_id, explanation
question_type
```

**`answers`** / **`choose_answers`**
```
id, question_id, content
is_answer   — đánh dấu đáp án đúng
image_id
```

**`take_exams`** — Lần thi của người dùng
```
id, user_id, exam_id, exam_meta_id
exam_schedule_id, exam_config_id
exam_score              (decimal)
is_send_exam_writting   — 1: có phần tự luận chờ chấm
created_at, updated_at
```

**`take_answers`** — Câu trả lời từng câu
```
id, take_exam_id, question_id
answer_id, user_answer (text — cho tự luận)
```

**`exam_results`** — Kết quả chi tiết

### 3.5 Bảng đơn hàng & thanh toán

**`orders`**
```
id, full_name, email, phone_number, address
course_id, user_id, combo_id
status              (1: Processing, 2: Completed, 3: Failed)
payment_category_id (1: Online, 2: COD, 3: MoMo)
discount_code_id, affiliate_code_id
current_price_course
register_date, date_ship_code
code (order code)
```

**`order_details`** — Book items trong đơn hàng

**`orders_deal`** — Deal course items trong đơn hàng
```
id, order_id, product_id, current_price
```

**`account_payments`** — Thông tin thanh toán qua tài khoản

**`shipcod_payments`** — Thông tin COD

**`office_payments`** — Thông tin thanh toán tại văn phòng

**`discount_codes`**
```
id, code, type (1: percent, 2: money)
discount_percent / amount_money
number_of_use, max_of_use
start_time, end_time
combo_id, is_active
```

**`affiliate_codes`**
```
id, code_number, amount_money
type (1: percent, 2: money)
affiliate_code_type (1: course, 2: book, 3: combo)
number_of_use, user_id, status
```

**`manage_affiliate_codes`** — Phân phối mã affiliate cho user

### 3.6 Bảng lớp học 1-1

**`book_lessons`** — Slot thời gian dạy học
```
id, date_time_start, teacher_id
active_flg, status_cancel
status_choose   (0: chưa đặt, 1: đã đặt, 2: hoàn thành)
mail_sent, title_lesson, columns_type
```

**`number_lessons`** — Số buổi học còn lại
```
id, user_id, number_lesson (integer)
```

**`history_lesson_student`** — Lịch sử đặt lịch học
```
id, student_id, teacher_id, book_lesson_id
course_id, lesson_id
date_time_start
active_flg      (0: chưa học, 1: đang học, 2: hoàn thành)
status_cancel   (0: không hủy, 1: đã hủy)
conv_id         — Stringee conversation ID
type
```

**`lesson_one_to_one`** — Cấu hình lớp 1-1

**`combo_courses_one_one`** — Gói lớp học 1-1
```
id, number_lesson, description
price, title, slug, level, amount, sale
```

**`feedback_book_lessons`** — Đánh giá sau buổi học

**`student_reviews`** — Review giáo viên của học viên

### 3.7 Bảng nội dung & khác

**`books`**
```
id, title, price, description
status, is_free, deleted_at
media references
```

**`carts`** — Giỏ hàng
```
id, user_id, book_id, number (quantity)
```

**`news`**
```
id, title, content, slug
category_id, status, top (ranking)
seo fields
```

**`comments`**
```
id, content, user_id, lesson_id
is_image, url
```

**`messages`** — Thông báo nội bộ
```
id, user_id, content, type
read_at (nullable — null = chưa đọc)
```

**`settings`** — Cấu hình global (key-value)

**`user_socials`** — Liên kết tài khoản mạng xã hội

**`user_gifts`** — Quà tặng/phần thưởng

### 3.8 Model Relationships chính

**User:**
```php
hasOne:       Teacher, NumberLesson, AffiliateCode
hasMany:      UserSocial
belongsToMany: Course (via course_user pivot)
```

**Course:**
```php
hasOne:       Media (image), Media (responsiveImage)
hasMany:      CourseManager, Lesson, LessonCourse1_1
belongsToMany: User
Traits:       Sluggable (auto slug từ title), SoftDelete
```

**Order:**
```php
hasOne:       Course, ComboCoursesOneOne, DiscountCode, AffiliateCode
hasMany:      OderDetail, OrdersDeal
belongsTo:    User
```

**Exam:**
```php
hasMany:      ExamQuestion, Question (via original_exam_id)
belongsTo:    ExamMeta
hasOne:       ExamConfig
belongsToMany: Question (via exam_questions pivot)
Traits:       Sluggable
```

**TakeExam:**
```php
hasOne:       User, Exam, ExamMeta, ExamSchedule
```

---

## 4. Frontend - Nuxt.js 2

### 4.1 Cấu hình Nuxt (`nuxt.config.js`)

**Mode:** SSR (Server-Side Rendering), target: `server`

**Head mặc định:**
```
title: "HanquocNori - Nền tảng học tiếng Hàn số một Việt Nam"
meta: charset, viewport, Open Graph tags
favicon: /logo.png
font: Roboto (Google Fonts)
```

**CSS toàn cục:**
```
@/assets/scss/app.scss
@/assets/css/materialicons.css
```

**Auto-import components:**
```
~/components/          → global
~/components/elements/ → global (no prefix)
~/components/widgets/  → global (no prefix)
~/components/dialog/   → global (no prefix)
```

**Build modules:**
```
@nuxtjs/vuetify   → Material Design UI
@nuxtjs/dotenv    → Load .env
@nuxtjs/device    → Device detection (mobile/desktop)
```

**Modules:**
```
@nuxtjs/axios              → HTTP client (baseURL: process.env.API_URL)
nuxt-i18n                  → i18n (vi, en, kr)
@nuxtjs/toast              → Toast notifications
cookie-universal-nuxt      → Cookie management
@nuxtjs/sitemap            → Tạo sitemap.xml
@nuxtjs/google-tag-manager → GTM (ID: process.env.ID_GTM)
@nuxtjs/google-analytics   → GA (ID: process.env.ID_GA)
```

**Vuetify theme:**
```
Light mode
primary color: #0C0B0B
Icons: Material Design Icons
Custom vars: ~/assets/variables.scss
```

### 4.2 Plugins

| Plugin | Mode | Chức năng |
|---|---|---|
| axios config | both | Cấu hình base URL, interceptors |
| API service | both | Khởi tạo API service |
| Vue Flipcard | CSR only | Card flip animation |
| Facebook SDK | CSR only | Facebook JS SDK |
| V-emoji-picker | CSR only | Emoji picker |

### 4.3 Vuex Store (35 modules)

| Module | Chức năng |
|---|---|
| `auth` | Đăng nhập, đăng xuất, user state, JWT token |
| `courses` | Danh sách & chi tiết khóa học, kiểm tra quyền truy cập |
| `lessons` | Nội dung bài học, tiến độ học |
| `exercises` | Bài tập |
| `exams` | Thi, lấy câu hỏi, nộp bài |
| `examResults` | Kết quả thi |
| `questions` | Câu hỏi bài tập/bài thi |
| `takeAnswers` | Câu trả lời của user |
| `flashcards` | Flashcard học từ vựng |
| `orders` | Đơn hàng |
| `payments` | Thanh toán |
| `books` | Danh sách & giỏ hàng sách |
| `combo` | Combo khóa học |
| `dealCourses` | Khóa học deal/ưu đãi |
| `courses1_1` | Lớp học 1-1 (chung) |
| `courses1_1_student` | Lớp học 1-1 (học viên) |
| `courses1_1_teacher` | Lớp học 1-1 (giáo viên) |
| `teacher` | Dashboard giáo viên |
| `users` | Hồ sơ người dùng |
| `message` | Thông báo/tin nhắn |
| `comments` | Bình luận bài học |
| `categories` | Danh mục |
| `new` | Tin tức/blog |
| `calendar` | Lịch học |
| `chat` | Real-time chat |
| `setting` | Cài đặt người dùng |
| `userGifts` | Quà tặng |
| `alert` | Alert notifications |
| `loading` / `loadings` | Loading state |
| `popup` | Modal/popup |
| `localStorage` | Lưu trữ local |
| `mixin` | Shared utilities |
| `privacy` | Chính sách bảo mật |
| `index` | Root store (cart/session) |

**Pattern của Auth store:**
```javascript
// Constants
LOGIN, LOGOUT, REGISTER, LOGIN_FACE, SEND_EMAIL_RESET_PASSWORD

// State
loggedIn, registered, user, error, is_authenticated, isLogged

// Actions (async)
login(email, password, rememberMe) → JWT token
register(userData)                 → tạo tài khoản
logout()                           → revoke token
```

### 4.4 Cấu trúc Pages (177 pages)

```
pages/
├── index.vue                    # Trang chủ
├── sign-up.vue                  # Đăng ký
├── cart.vue                     # Giỏ hàng
├── payment.vue                  # Thanh toán khóa học
├── payment-book.vue             # Thanh toán sách
├── payment-combo.vue            # Thanh toán combo
├── take-exam.vue                # Làm bài thi
├── policy.vue                   # Chính sách
├── rule.vue                     # Điều khoản
├── support.vue                  # Hỗ trợ
├── khoa-hoc/                    # Danh sách & chi tiết khóa học
├── course-1-1/                  # Lớp học 1-1
├── account/                     # Tài khoản (lịch sử, thông báo, đổi MK)
├── exam/                        # Kỳ thi
├── book/                        # Cửa hàng sách
└── teacher/                     # Giáo viên
```

---

## 5. Admin - Vue.js 2

### 5.1 Khởi tạo (main.js)

**Thứ tự khởi tạo:**
1. Vue core + CKEditor 5 integration
2. `ApiService.init()` — Khởi tạo HTTP client với JWT
3. Plugins: Toasted, DatetimePicker, i18n, Vuetify, vee-validate
4. Global styles: @mdi/font
5. Router + Store
6. Mount to `#app`

### 5.2 API Service (`api.service.js`)

```javascript
Base URL: process.env.VUE_APP_API_URL
Auth: Bearer {JWT_TOKEN}

Methods:
- query(resource, params)           → GET với query params
- get(resource, slug)               → GET với URL path
- post(resource, params, config)    → POST
- update(resource, slug, params)    → PUT
- delete(resource, slug)            → DELETE
- setHeader()                       → Set Authorization header thủ công
- handleResponse(data)              → Xử lý response thống nhất
```

### 5.3 Cấu trúc Admin (301 Vue files)

**Vuex Modules (~30 modules):**
```
auth, courses, lessons, exercises, exams,
orders, users, categories, settings,
media, news, books, teachers, flashcards,
discountCodes, affiliateCodes, ...
```

**Router (100+ routes)** — phân nhóm theo tính năng:
```
/dashboard
/courses/*         — Quản lý khóa học
/lessons/*         — Quản lý bài học
/exercises/*       — Quản lý bài tập
/exams/*           — Quản lý bài thi
/questions/*       — Quản lý câu hỏi
/users/*           — Quản lý người dùng
/teachers/*        — Quản lý giáo viên
/orders/*          — Quản lý đơn hàng
/books/*           — Quản lý sách
/news/*            — Quản lý tin tức
/flashcards/*      — Quản lý flashcard
/settings/*        — Cài đặt hệ thống
/media/*           — Thư viện media
/deal-courses/*    — Quản lý deal
/combo-courses/*   — Quản lý combo
```

---

## 6. API Routes

### 6.1 Frontend Routes (`web-front.php`)

**Authentication (`/auth/`)**
```
POST   /auth/register
POST   /auth/login
POST   /auth/login-facebook
GET    /auth/refresh
GET    /auth/email/verify/{id}/{token}
GET    /auth/email/resend
POST   /auth/send-email-reset-password
POST   /auth/reset/password
--- Protected (auth:api) ---
GET    /auth/user
POST   /auth/logout
GET    /auth/login/{services}           ← OAuth redirect
GET    /auth/login/{services}/callback  ← OAuth callback
```

**Courses & Lessons**
```
GET    /course/list
GET    /course/detail
GET    /course/active
GET    /course/purchase
POST   /course/update-date-active
GET    /lesson/list
GET    /lesson/detail
POST   /lesson/process/save
GET    /lesson/process/detail
GET    /flashcard/list
```

**Exercises**
```
GET    /exercise/content/detail
GET    /exercise/detail
GET    /exercise/filledword/list
POST   /exercise/material/detail
```

**Exams**
```
GET    /exam/permission              ← (auth required)
GET    /exam/detail
GET    /exam/ranking/list
GET    /exam/meta/detail
GET    /exam/schedule/going-on
GET    /exam/schedule/closest
GET    /exam/result/detail
POST   /exam/take-answer/submit
POST   /exam/send-exam-writting
GET    /take-exam/isExist
GET    /take-exam/list/user
GET    /take-exam/list/exam-meta
GET    /take-exam/detail/
GET    /take-exam/detail-score
GET    /question/list
GET    /review-exam/question/list
```

**Orders & Payments**
```
POST   /order/save
GET    /order/list
GET    /order/detail-book/{id}
GET    /order/detail-course/{id}
GET    /price-after-discount
GET    /payment/deal-course
GET    /payment/account/
GET    /payment/office/
POST   /notify-momo                  ← MoMo webhook callback
```

**Books**
```
GET    /books/
GET    /books/list-id-book-active-not-buy
POST   /books/detail
POST   /books/add-to-cart
POST   /books/delete-to-cart
POST   /books/update-number-cart
POST   /books/remove-to-cart
GET    /books/list-to-cart
GET    /books/count-to-cart
POST   /books/order
```

**User**
```
GET    /user/detail
POST   /user/avatar/upload
GET    /user/avatar/
POST   /user/update/
POST   /user/change-password
GET    /user/affiliate-code
```

**Lớp học 1-1**
```
GET    /get-learner-booking
POST   /create-book-lesson
POST   /cancel-book-lesson
GET    /get-history-book-lesson
GET    /get-count-down-lesson
GET    /get-access-token             ← Stringee JWT token
POST   /update-conv-id
POST   /save-feedback
GET    /get-feedback
POST   /student-review
```

**Khác**
```
GET    /comment/list
POST   /comment/save
GET    /news/list
POST   /news/detail
GET    /news/categories
GET    /news/list/categories
GET    /setting/get-homepage-config
GET    /setting
GET    /get-list-message
GET    /count-message-un-read
POST   /update-message-read-at
GET    /sitemap/list
GET    /policy
```

### 6.2 Admin Routes (`web-admin.php`)

Tất cả routes admin được bảo vệ bởi middleware `adminrole:admin`.

```
POST   /login

--- Exercise Management ---
GET/POST   /exercise/detail|save|update|delete
GET/POST   /exercise/content/detail|list-reading|list-listening|save|update|delete
GET/POST   /exercise/material/detail|update|upload
GET/POST   /exercise/filledword/list|save|delete
GET/POST   /exercise/reading-material|listen-material|translate/*
GET/POST   /exercise/answer|choose-answer/*

--- Exam Management ---
GET/POST   /exam/schedule/*
GET/POST   /exam/meta/*
GET/POST   /exam/config/*
POST       /exam/save|update|generate
DELETE     /exam/delete/{id}
GET        /mark-exam/*              ← Chấm thi tự luận

--- Question Management ---
GET/POST   /question/*

--- Course Management ---
GET/POST   /course/*
GET/POST   /lesson/*
GET/POST   /category/*

--- User & Order ---
GET/POST   /user/*
GET/POST   /order/*
GET/POST   /payment/*

--- Content ---
GET/POST   /news/*
GET/POST   /book/*
GET/POST   /flashcard/*
GET/POST   /discount-code/*
GET/POST   /affiliate-code/*
GET/POST   /setting/*

--- Media ---
POST       /ckeditor/image/upload
POST       /media/upload
```

---

## 7. Authentication & JWT

### 7.1 Cấu hình JWT (`config/jwt.php`)

```
secret:           env('JWT_SECRET')       ← HS256 symmetric key
algorithm:        HS256 (default)
ttl:              10080 phút (7 ngày)
refresh_ttl:      20160 phút (14 ngày)
leeway:           0 (không cho phép clock skew)
blacklist:        enabled (hỗ trợ revoke token)
lock_subject:     true (chặn token impersonation)
```

**Required claims trong JWT payload:**
```
iss  — issuer
iat  — issued at
exp  — expiration time
nbf  — not before
sub  — subject (user ID)
jti  — JWT ID (unique per token)
```

**Providers:**
```
JWT:     Tymon\JWTAuth\Providers\JWT\Lcobucci
Auth:    Tymon\JWTAuth\Providers\Auth\Illuminate
Storage: Tymon\JWTAuth\Providers\Storage\Illuminate
```

### 7.2 Luồng xác thực kỹ thuật

```
Client → POST /auth/login (email + password)
              ↓
         UserService::login()
              ↓
         Illuminate Auth → bcrypt check
              ↓
         JWTAuth::attempt() → tạo JWT
              ↓
         Response: { token: "eyJ..." }

Client → Request với header:
         Authorization: Bearer eyJ...
              ↓
         Middleware Authenticate
              ↓
         JWTAuth::parseToken()->authenticate()
              ↓
         Inject $request->user()
```

### 7.3 Stringee JWT (lớp học 1-1)

```
apiKeySid:    'SKFFTfAFmwLeqP0BaQNqPRiKUKOVN1xPe2'
exp:          now + 3600 giây
userId format: 'user_call_{history_lesson_id}_{student_id}'

Payload: { jti, iss: apiKeySid, exp, userId }
```

---

## 8. Hệ thống Cache & Queue

### 8.1 Redis Cache

**Lesson menu cache:**
```
Key: nori:lesson-menu-active-by-course-{slug}-by-{user_id}
Xóa khi: Học viên lưu tiến độ bài học (KeyLessonRedis event)
```

**Exam cache:**
```
Key: nori:exam-*
Xóa khi: Admin cập nhật đề thi (KeyExamRedis event)
```

### 8.2 Queue (Laravel Queue + Redis)

**Queue driver:** Redis

**Các loại job trong queue:**
```
BoughtCourseSuccess     → Gửi email mua hàng (high priority)
SendEmailCancleLesson   → Gửi email hủy lịch
ProcessEncryptVideo     → Xử lý video (low priority)
```

**Cách chạy queue worker:**
```bash
php artisan queue:work redis --daemon
```

**Với Supervisor (production):**
```ini
[program:hqnr-worker]
command=php artisan queue:work redis --sleep=3 --tries=3
directory=/path/to/api-hanquocnori
autostart=true
autorestart=true
```

### 8.3 Broadcasting (Real-time)

**Driver:** Pusher

```
Events broadcast:
- SendMailEvent    → PrivateChannel (email queue)
- SendNotification → Pusher channel (push notification)
```

**Frontend nhận event:**
```javascript
// pusher-js ^7.0.3
const pusher = new Pusher(process.env.PUSHER_APP_KEY, {
  cluster: process.env.PUSHER_APP_CLUSTER
})
pusher.subscribe('channel-name').bind('event-name', callback)
```

---

## 9. Tích hợp bên thứ ba

### AWS S3

```
Driver: s3 (trong config/filesystems.php)
Dùng để: Upload và serve media (ảnh, video, audio)
SDK: aws/aws-sdk-php-laravel ~3.0
Region: ap-southeast-1 (Singapore)
```

### Pusher

```
SDK (PHP): pusher/pusher-php-server ^4.1
SDK (JS):  pusher-js ^7.0.3
Dùng để: Real-time notifications, broadcast events
```

### OAuth (Socialite)

```
SDK: laravel/socialite ^5.2
Providers: Facebook, Google
Flow: Redirect → Callback → Tạo/cập nhật user → JWT
```

### MoMo Payment

```
Webhook: POST /notify-momo
Callback fields: errorCode (0 = success)
Action khi success: Tạo CourseManager, gửi notification
```

### Stringee (Video call 1-1)

```
Dùng để: Video call cho lớp học 1-1
Auth: JWT token với apiKeySid
convId: Lưu vào history_lesson_student.conv_id
```

### Video Streaming

```
Protocol: DASH (Dynamic Adaptive Streaming over HTTP)
Player:   video.js ^7.15.4 + videojs-contrib-dash ^2.11.0
Library:  dashjs ^3.2.0
```

### CKEditor 5 (Admin)

```
Packages: @ckeditor/ckeditor5-* ^23.x
Features: alignment, formatting, image, table,
          link, code, paste-from-office
Image upload: POST /ckeditor/image/upload → AWS S3
```

---

## 10. Dependencies & Versions

### Backend (PHP / Composer)

| Package | Version | Chức năng |
|---|---|---|
| laravel/framework | ^7.0 | Core framework |
| tymon/jwt-auth | dev-develop | JWT authentication |
| laravel/passport | ^8.2 | OAuth server |
| laravel/socialite | ^5.2 | Social OAuth |
| firebase/php-jwt | ^5.2 | JWT utilities |
| lcobucci/jwt | 3.3.3 | JWT provider |
| predis/predis | ^1.1 | Redis client |
| pusher/pusher-php-server | ^4.1 | Pusher broadcasting |
| guzzlehttp/guzzle | ^6.3 | HTTP client |
| cviebrock/eloquent-sluggable | 7.0 | Auto slug generation |
| doctrine/dbal | ^2.12.1 | Database abstraction |
| aws/aws-sdk-php-laravel | ~3.0 | AWS S3 SDK |

### Frontend (Node / npm)

| Package | Version | Chức năng |
|---|---|---|
| nuxt | ^2.14.11 | SSR framework |
| @nuxtjs/axios | ^5.12.4 | HTTP client |
| vuetify | ^2.5.5 | Material Design UI |
| pusher-js | ^7.0.3 | Real-time events |
| video.js | ^7.15.4 | Video player |
| dashjs | ^3.2.0 | DASH streaming |
| moment | ^2.29.1 | Date/time utilities |
| vee-validate | ^3.4.13 | Form validation |
| zxcvbn | ^4.4.2 | Password strength |
| @fullcalendar/vue | ^5.5.0 | Calendar UI |
| @nuxtjs/google-analytics | ^2.4.0 | Google Analytics |
| @nuxtjs/google-tag-manager | ^2.3.2 | Google Tag Manager |

### Admin (Node / npm)

| Package | Version | Chức năng |
|---|---|---|
| vue | ^2.6.11 | Core framework |
| vuetify | ^2.2.13 | Material Design UI |
| axios | ^0.19.2 | HTTP client |
| @ckeditor/ckeditor5-* | ^23.x | Rich text editor |
| bootstrap-vue | ^2.5.0 | Bootstrap components |
| vee-validate | ^3.3.9 | Form validation |
| vuelidate | ^0.7.5 | Form validation |
| vue-i18n | ^8.15.3 | Internationalization |
| chart.js | — | Data visualization |
| sweetalert2 | — | Alert dialogs |

---

## 11. Cấu hình môi trường

### Backend (`api-hanquocnori/.env`)

```env
# Application
APP_NAME=HanQuocNori
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://api.hanquocnori.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hanquocnori
DB_USERNAME=root
DB_PASSWORD=

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Cache & Queue
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

# JWT
JWT_SECRET=

# AWS S3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=

# Pusher
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=ap3
BROADCAST_DRIVER=pusher

# Mail
MAIL_DRIVER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=noreply@hanquocnori.com

# Social OAuth
FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
FACEBOOK_REDIRECT=

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT=
```

### Frontend (`front-hanquocnori/.env`)

```env
API_URL=https://api.hanquocnori.com
PUSHER_APP_KEY=
PUSHER_APP_CLUSTER=ap3
FACEBOOK_APP_ID=
ID_GA=UA-XXXXXXXX-X
ID_GTM=GTM-XXXXXXX
```

### Admin (`admin-hanquocnori/.env`)

```env
VUE_APP_API_URL=https://api.hanquocnori.com/admin
```

---

*Tài liệu kỹ thuật được tạo: 2026-03-24*
