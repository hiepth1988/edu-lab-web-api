# Seiko LMS - Tài liệu Dự án

> Hệ thống Quản lý Học tập (LMS) dành cho trung tâm đào tạo ngoại ngữ

---

## Mục lục

1. [Tổng quan](#tổng-quan)
2. [Kiến trúc hệ thống](#kiến-trúc-hệ-thống)
3. [Cấu trúc dự án](#cấu-trúc-dự-án)
4. [Công nghệ sử dụng](#công-nghệ-sử-dụng)
5. [Tính năng chính](#tính-năng-chính)
6. [Hướng dẫn cài đặt](#hướng-dẫn-cài-đặt)
7. [Cấu hình môi trường](#cấu-hình-môi-trường)
8. [API & Routes](#api--routes)
9. [Database Schema](#database-schema)
10. [Tích hợp bên thứ ba](#tích-hợp-bên-thứ-ba)
11. [Build & Deploy](#build--deploy)

---

## Tổng quan

**Seiko LMS** là hệ thống quản lý học tập toàn diện, bao gồm:
- Quản lý kỳ học, khóa học, lớp học và lịch dạy
- Giao và chấm bài tập, bài thi trực tuyến
- Điểm danh học sinh và quản lý đơn xin nghỉ
- Thông báo thời gian thực và xuất báo cáo Excel

**Ngôn ngữ hỗ trợ**: Tiếng Việt (chính), Tiếng Anh, Tiếng Nhật, Tiếng Hàn

---

## Kiến trúc hệ thống

```
┌───────────────────────────────────────────────────────┐
│           WEBSITE CÔNG KHAI (Nuxt.js 3)               │
│  Học sinh xem thông tin, đăng ký khóa học             │
└───────────────────┬───────────────────────────────────┘
                    │ HTTPS / REST API
                    ▼
┌───────────────────────────────────────────────────────┐
│              BACKEND API (Laravel 9)                  │
│   Auth │ Courses │ Exams │ Attendance │ Realtime      │
└──────────┬──────────┬──────────────────┬──────────────┘
           │          │                  │
       MySQL DB   File Storage       Pusher
       (seiko_v3)  (Local disk)    (Real-time)
                    │
┌───────────────────────────────────────────────────────┐
│            ADMIN DASHBOARD (Vue.js 3)                 │
│   Dashboard │ Quản lý lớp │ Bài tập │ Điểm danh      │
└───────────────────────────────────────────────────────┘
```

---

## Cấu trúc dự án

```
SourceEGLIFE/
├── lmsapi-develop/        # Backend API (Laravel 9)
├── lmsadmin-develop/      # Admin Dashboard (Vue 3 + TypeScript)
└── landingpage-develop/   # Website công khai (Nuxt 3)
```

### Backend API (`lmsapi-develop/`)
```
app/
├── Http/Controllers/
│   ├── Admin/           # ~20 controllers quản trị
│   ├── Student/         # Controllers dành cho học sinh
│   ├── Export/          # 7 controllers xuất Excel
│   ├── Import/          # 2 controllers nhập dữ liệu
│   └── AuthController.php
├── Models/              # 37+ Eloquent models
├── AppMain/Services/    # Business logic layer
├── Jobs/                # Xử lý bất đồng bộ
├── Events/              # Notification & mail events
└── Utilities/           # Hằng số, helper, response formatter
database/
├── migrations/          # 40+ migration files
└── seeds/
routes/
├── api.php              # Route tổng hợp
├── api-admin.php        # Routes Admin
├── api-student.php      # Routes Học sinh
└── api-lecture.php      # Routes Giảng viên
```

### Admin Dashboard (`lmsadmin-develop/`)
```
src/
├── main.ts              # Entry point, khởi tạo Vue, plugins
├── App.vue              # Root component
├── router/index.ts      # Định nghĩa 30+ routes, navigation guards
├── stores/              # Pinia stores (36 stores)
│   ├── auth.ts          # Xác thực
│   ├── class.ts         # Lớp học
│   ├── student.ts       # Học sinh
│   ├── teacher.ts       # Giảng viên
│   ├── exam.ts          # Bài thi
│   ├── exercise.ts      # Bài tập
│   └── notification.ts  # Thông báo
├── services/            # Axios, JWT, i18n, session storage
├── utils/               # Constants, time utilities
└── views/pages/         # Pages Vue
```

### Frontend (`landingpage-develop/`)
```
pages/
├── index.vue            # Trang chủ giới thiệu
└── register.vue         # Form đăng ký học sinh
components/              # Reusable components
layouts/                 # Layout templates
nuxt.config.ts           # Cấu hình Nuxt
```

---

## Công nghệ sử dụng

| Thành phần | Công nghệ | Phiên bản |
|---|---|---|
| Admin Frontend | Vue.js | 3.2.47 |
| Admin Language | TypeScript | 5.0.4 |
| Admin Build | Vite | 4.3.4 |
| Admin State | Pinia | 2.0.35 |
| Public Frontend | Nuxt.js | 3.8.1 |
| Backend | Laravel | 9.19 |
| PHP | PHP | 7.3+ / 8.0+ |
| Database | MySQL | 5.7+ |
| Auth | Laravel Sanctum | 2.11 |
| Real-time | Pusher.js | 8.2.0 |
| HTTP Client | Axios | 1.4.0 |
| Rich Text | TinyMCE Vue | 4.0.7 |
| Validation | VeeValidate | 4.9.2 |
| i18n | vue-i18n / @nuxtjs/i18n | 9 / 8.0.0-rc.5 |
| Excel | Maatwebsite Excel | 3.0 |
| Mail | PHPMailer / SMTP2GO | 6.5 |
| CSS | Tailwind CSS | 3.3.2 |

---

## Tính năng chính

### Hệ thống học tập (LMS)
- **Kỳ học / Khóa học / Lớp học**: Tạo và quản lý theo cấu trúc phân cấp
- **Lịch học**: Dạng lịch tháng, copy lịch, import từ Excel
- **Buổi học**: Tài liệu (PDF, Word, Audio, Link), bài tập, bài thi
- **Bài tập**: Upload đề bài + đáp án, theo dõi trạng thái nộp bài
- **Bài thi**: Mini Test và Comprehensive, chấm điểm thủ công
- **Theo dõi tiến độ**: Xem kết quả bài tập, bài thi theo lớp

### Điểm danh & Nghỉ phép
- **Điểm danh**: Có mặt, vắng có phép, vắng không phép, đến muộn, về sớm
- **Đơn xin nghỉ học sinh**: Nộp đơn, upload minh chứng, duyệt/từ chối
- **Đơn nghỉ giảng viên**: Nộp đơn, duyệt/từ chối kèm lý do
- **Timesheet**: Xuất báo cáo chấm công giảng viên và học sinh

### Người dùng & Phân quyền
- Đăng ký/Đăng nhập bằng Email (Sanctum Token)
- Phân quyền 4 cấp: Admin, Sub-Admin, Giảng viên, Học sinh
- Quản lý hồ sơ người dùng, đổi mật khẩu, reset qua email
- Hệ thống thông báo real-time (12 loại, qua Pusher)

### Import/Export
- **Import học sinh**: Upload Excel, validate 7 bước, xử lý nền (Queue)
- **Import lịch học**: Upload Excel tạo buổi học hàng loạt
- **Export Excel**: Lịch học, học tập, bài tập, chấm công GV/HS, danh sách HS

### Quản trị nội dung
- Dashboard tổng quan
- Đánh giá học sinh và lớp học theo tháng
- Tư vấn và quản lý đơn tư vấn (Advise)
- Đa ngôn ngữ: Việt, Anh, Nhật, Hàn

---

## Hướng dẫn cài đặt

### Yêu cầu hệ thống
- PHP 7.3+ hoặc 8.0+
- Node.js 16+
- MySQL 5.7+
- Composer
- npm/yarn

### 1. Backend API

```bash
cd lmsapi-develop

# Cài đặt dependencies
composer install

# Sao chép file cấu hình
cp .env.example .env

# Tạo application key
php artisan key:generate

# Chạy migrations
php artisan migrate

# Tạo symlink storage
php artisan storage:link

# Khởi động server (môi trường dev)
php artisan serve
```

### 2. Admin Dashboard

```bash
cd lmsadmin-develop

# Cài đặt dependencies
npm install

# Sao chép file cấu hình
cp .env.example .env
# Sửa VITE_URL_API=http://localhost:8000/api

# Khởi động môi trường dev
npm run dev

# Build production
npm run build
```

### 3. Landing Page (Nuxt.js)

```bash
cd landingpage-develop

# Cài đặt dependencies
npm install

# Sao chép file cấu hình
cp .env.example .env

# Khởi động môi trường dev
npm run dev

# Build production
npm run build
npm run start
```

### 4. Cấu hình Queue Worker

```bash
# Chạy queue worker (cần thiết cho import học sinh)
php artisan queue:work database --daemon
```

### 5. Cấu hình Cron Job

```bash
# Thêm vào crontab (cron scheduler Laravel)
* * * * * cd /path/to/lmsapi-develop && php artisan schedule:run >> /dev/null 2>&1
```

---

## Cấu hình môi trường

### Backend (`.env`)

```env
APP_NAME="Seiko LMS"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=seiko_v3
DB_USERNAME=root
DB_PASSWORD=

# File Storage
FILESYSTEM_DISK=public

# Queue
QUEUE_CONNECTION=database

# Pusher (Real-time)
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1
PUSHER_SCHEME=https
PUSHER_PORT=443

# Mail (SMTP2GO)
MAIL_MAILER=smtp
MAIL_HOST=mail.smtp2go.com
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

### Admin Dashboard (`.env`)

```env
VITE_URL_API=https://api.yourdomain.com/api
```

### Landing Page (`.env`)

```env
NUXT_PUBLIC_API_BASE=https://api.yourdomain.com/api
```

---

## API & Routes

### Nhóm routes chính (Backend)

| Nhóm | File | Mô tả |
|---|---|---|
| Tổng hợp | `routes/api.php` | Gộp tất cả route files |
| Admin | `routes/api-admin.php` | Quản trị hệ thống |
| Học sinh | `routes/api-student.php` | API cho học sinh |
| Giảng viên | `routes/api-lecture.php` | API cho giảng viên |

### Các endpoints tiêu biểu

**Authentication**
- `POST /api/login` - Đăng nhập
- `POST /api/logout` - Đăng xuất
- `POST /api/sendPasswordResetLink` - Gửi link reset mật khẩu
- `POST /api/auth/reset-password` - Đổi mật khẩu qua token
- `GET /api/auth/detail-user` - Thông tin người dùng hiện tại

**Lớp học**
- `GET /api/admin/class-room` - Danh sách lớp học
- `POST /api/admin/class-room` - Tạo lớp học
- `PUT /api/admin/class-room/{id}` - Cập nhật lớp học
- `DELETE /api/admin/class-room/{id}` - Xóa lớp học
- `GET /api/admin/class-room/list-student-in-class/{id}` - Học sinh trong lớp

**Bài tập & Bài thi**
- `POST /api/period/create-exercise` - Tạo bài tập
- `POST /api/period/create-exam` - Tạo bài thi
- `POST /api/period/mark` - Chấm điểm bài thi
- `POST /api/student/submit-answer-exercise-in-period/{id}` - Nộp bài tập
- `POST /api/student/submit-answer-exam-in-period/{id}` - Nộp bài thi

**Điểm danh & Nghỉ phép**
- `POST /api/period/attendance` - Điểm danh học sinh
- `POST /api/student/create-holiday-student` - Học sinh nộp đơn nghỉ
- `POST /api/admin/check-update-holiday/{id}` - Duyệt đơn nghỉ
- `GET /api/admin/student/list-holiday-student` - Danh sách đơn nghỉ

**Import/Export**
- `POST /api/admin/import/student` - Import học sinh từ Excel
- `GET /api/admin/export/schedule` - Xuất lịch học
- `GET /api/admin/export/student/timesheet` - Xuất chấm công học sinh
- `GET /api/admin/export/lecturer/timesheet` - Xuất chấm công giảng viên

---

## Database Schema

### Các bảng dữ liệu chính (37+ Models)

**Users & Auth**
- `users` - Người dùng (role: 0=Admin, 1=SubAdmin, 2=GV, 3=HS)
- `personal_access_tokens` - Sanctum API tokens
- `lecturer_details` - Thông tin hồ sơ giảng viên
- `student_details` - Thông tin hồ sơ học sinh

**Cấu trúc học vụ**
- `projects` - Kỳ học / Chương trình đào tạo
- `courses` - Khóa học thuộc kỳ học
- `class_rooms` - Lớp học (có màu, cấp độ, ngày bắt đầu/kết thúc)
- `class_room_users` - Đăng ký học sinh vào lớp
- `classroom_teachers` - Phân công giảng viên vào lớp
- `levels` - Cấp độ (N1–N5)
- `period_classes` - Buổi học (ngày, ca AM/PM, giảng viên)

**Nội dung bài học**
- `period_class_documents` - Tài liệu học tập
- `exercises` - Bài tập (đề + đáp án)
- `exercise_submits` - Bài nộp của học sinh
- `exams` - Bài thi (Mini Test / Comprehensive)
- `exam_submits` - Bài thi đã nộp
- `student_files` - File đính kèm bài nộp

**Điểm danh & Nghỉ phép**
- `timesheets` - Điểm danh theo buổi học
- `student_timesheets` - Đơn xin nghỉ của học sinh
- `lecturer_timesheets` - Lịch nghỉ của giảng viên
- `setting_holidays` - Cấu hình ngày nghỉ lễ

**Đánh giá & Tiện ích**
- `review_classroom` - Đánh giá lớp học
- `review_students` - Đánh giá học sinh
- `notifications` - Thông báo (12 loại, lưu JSON)
- `advises` - Đơn tư vấn từ trang landing
- `completed_jobs` - Theo dõi tiến trình import
- `schedules` / `schedule_details` - Quản lý lịch học

---

## Tích hợp bên thứ ba

| Dịch vụ | Mục đích |
|---|---|
| **Pusher** | Real-time events, thông báo tức thì |
| **SMTP2GO** | Gửi email giao dịch (reset mật khẩu, tạo tài khoản) |
| **Laravel Sanctum** | Xác thực API bằng Bearer Token |
| **Maatwebsite Excel** | Import/Export file Excel |
| **PHPMailer** | Gửi email với template HTML |

---

## Build & Deploy

### Admin Dashboard

```bash
npm run build
# Output: dist/ folder → deploy lên web server (Nginx/Apache)
```

### Landing Page (Nuxt.js)

```bash
# SSR Mode
npm run build
npm run start

# Static Mode
npm run generate
# Output: dist/ folder
```

### Backend (Laravel)

```bash
# Production setup
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link

# Queue worker (chạy như service)
php artisan queue:work database --daemon
```

### Yêu cầu server production
- **Web Server**: Nginx hoặc Apache
- **PHP-FPM**: 7.3+ hoặc 8.0+
- **MySQL**: 5.7+
- **SSL**: HTTPS (Let's Encrypt hoặc paid cert)
- **PM2** (cho Node.js Nuxt server)
- **Supervisor** (cho Laravel queue worker)

---

*Tài liệu được tạo: 2026-04-06*
