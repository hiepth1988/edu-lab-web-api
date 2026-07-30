# HanQuocNori - Tài liệu Dự án

> Nền tảng học tiếng Hàn trực tuyến (EdTech Platform)

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

**HanQuocNori** là nền tảng học tiếng Hàn trực tuyến toàn diện, bao gồm:
- Hệ thống quản lý học tập (LMS) với khóa học, bài học, bài tập, bài thi
- Thương mại điện tử: mua khóa học, sách, combo khóa học
- Lớp học 1-1 với giáo viên
- Quản trị nội dung và người dùng

**Ngôn ngữ hỗ trợ**: Tiếng Việt (chính), Tiếng Anh, Tiếng Hàn

---

## Kiến trúc hệ thống

```
┌───────────────────────────────────────────────────────┐
│            WEBSITE CÔNG KHAI (Nuxt.js)                │
│  Người dùng xem khóa học, mua bài, làm bài thi       │
└───────────────────┬───────────────────────────────────┘
                    │ HTTPS / REST API
                    ▼
┌───────────────────────────────────────────────────────┐
│              BACKEND API (Laravel 7)                  │
│   Auth │ Courses │ Exams │ Payments │ Realtime        │
└──────────┬──────────┬──────────────────┬──────────────┘
           │          │                  │
       MySQL DB   Redis Cache        AWS S3
       (Dữ liệu)  (Session/Cache)   (Media)
                    │
┌───────────────────────────────────────────────────────┐
│            ADMIN DASHBOARD (Vue.js 2)                 │
│   Dashboard │ Quản lý nội dung │ Người dùng          │
└───────────────────────────────────────────────────────┘
```

---

## Cấu trúc dự án

```
SourceGit/
├── admin-hanquocnori/      # Admin Dashboard (Vue.js 2 + Vuetify)
├── api-hanquocnori/        # Backend API (Laravel 7)
└── front-hanquocnori/      # Website công khai (Nuxt.js 2)
```

### Admin Dashboard (`admin-hanquocnori/`)
```
src/
├── main.js              # Entry point, khởi tạo Vue, plugins
├── App.vue              # Root component
├── router.js            # Định nghĩa 100+ routes admin
├── store/               # Vuex modules (~30 modules)
│   ├── auth/            # Xác thực
│   ├── courses/         # Khóa học
│   ├── lessons/         # Bài học
│   ├── exercises/       # Bài tập
│   ├── exams/           # Bài thi
│   ├── orders/          # Đơn hàng
│   └── users/           # Người dùng
├── views/               # Pages (301 Vue files)
└── components/          # Reusable components
```

### Backend API (`api-hanquocnori/`)
```
app/
├── Http/Controllers/
│   ├── Admin/           # ~150+ controllers quản trị
│   ├── Api/             # ~100+ controllers API công khai
│   ├── Auth/            # Xác thực, đăng nhập xã hội
│   └── Front/           # Endpoints website
├── Models/              # 76 Eloquent models
├── Services/            # Business logic
├── Jobs/                # Asynchronous tasks
└── Repositories/        # Data access layer
database/
├── migrations/          # 186 migration files
└── seeds/
routes/
├── api.php              # API routes
├── web-admin.php        # Admin routes
├── web-front.php        # Frontend API routes
└── web.php
```

### Frontend (`front-hanquocnori/`)
```
pages/                   # 177 Vue pages
├── khoa-hoc/            # Trang khóa học
├── course-1-1/          # Lớp học 1-1
├── account/             # Tài khoản người dùng
├── exam/                # Bài thi
├── book/                # Cửa hàng sách
├── teacher/             # Danh sách giáo viên
├── sign-up.vue          # Đăng ký
├── cart.vue             # Giỏ hàng
├── payment.vue          # Thanh toán khóa học
├── payment-book.vue     # Thanh toán sách
└── payment-combo.vue    # Thanh toán combo
components/              # Reusable components
layouts/                 # Layout templates
nuxt.config.js           # Cấu hình Nuxt
```

---

## Công nghệ sử dụng

| Thành phần | Công nghệ | Phiên bản |
|---|---|---|
| Admin Frontend | Vue.js | 2.6.11 |
| Admin UI | Vuetify | 2.2.13 |
| Admin State | Vuex | 3.1.2 |
| Public Frontend | Nuxt.js | 2.14.11 |
| Public UI | Vuetify | 2.5.5 |
| Backend | Laravel | 7.0 |
| PHP | PHP | 7.2.5+ |
| Database | MySQL | - |
| Cache | Redis | - |
| File Storage | AWS S3 | - |
| Auth | JWT (tymon/jwt-auth) | - |
| OAuth | Laravel Socialite | - |
| Real-time | Pusher.js | - |
| HTTP Client | Axios | 0.19.2 |
| Rich Text | CKEditor 5 | - |
| Video | Video.js + DASH | 7.15.4 |
| Validation | Vee-validate | 3.3.9 |
| i18n | vue-i18n / nuxt-i18n | - |
| Analytics | Google Analytics, GTM | - |
| Sitemap | @nuxtjs/sitemap | - |

---

## Tính năng chính

### Hệ thống học tập (LMS)
- **Khóa học**: Tạo, quản lý khóa học theo cấp độ, danh mục
- **Bài học**: Video (DASH streaming), audio, tài liệu văn bản
- **Bài tập**: 6 loại bài tập:
  - Trắc nghiệm (Multiple choice)
  - Điền vào chỗ trống (Fill in the blanks)
  - Nghe hiểu (Listening)
  - Đọc hiểu (Reading)
  - Dịch thuật (Translation)
  - Hội thoại (Dialogue)
- **Flashcard**: Học từ vựng qua thẻ ghi nhớ
- **Bài thi/Kiểm tra**: Thi tự động, chấm điểm tự động
- **Theo dõi tiến độ**: Dashboard người học

### Thương mại điện tử
- **Giỏ hàng & Thanh toán**: Mua khóa học, sách
- **Combo & Deal**: Gói khóa học ưu đãi
- **Mã giảm giá**: Discount code, voucher
- **Affiliate**: Chương trình giới thiệu, chia sẻ hoa hồng
- **Lịch sử đơn hàng**: Xem và quản lý đơn hàng
- **Phương thức thanh toán**: Tài khoản, văn phòng, COD

### Lớp học 1-1
- Đặt lịch học với giáo viên
- Quản lý buổi học riêng
- Theo dõi tiến trình học 1-1

### Người dùng & Giáo viên
- Đăng ký/Đăng nhập (Email, OAuth xã hội)
- Hồ sơ người dùng, giáo viên
- Phân quyền: Học sinh, Giáo viên, Admin
- Hệ thống thông báo real-time (Pusher)
- Nhắn tin giáo viên-học sinh

### Quản trị nội dung
- Blog/Tin tức với danh mục
- Cửa hàng sách điện tử
- Thư viện media (hình ảnh, âm thanh, video)
- Đa ngôn ngữ (Việt, Anh, Hàn)
- QR code cho khóa học

---

## Hướng dẫn cài đặt

### Yêu cầu hệ thống
- PHP 7.2.5+
- Node.js 12+
- MySQL 5.7+
- Redis
- Composer
- npm/yarn

### 1. Backend API

```bash
cd api-hanquocnori

# Cài đặt dependencies
composer install

# Sao chép file cấu hình
cp .env.example .env

# Tạo application key
php artisan key:generate

# Tạo JWT secret
php artisan jwt:secret

# Chạy migrations
php artisan migrate

# Tạo tài khoản admin
php artisan create:admin

# Khởi động server (môi trường dev)
php artisan serve
```

### 2. Admin Dashboard

```bash
cd admin-hanquocnori

# Cài đặt dependencies
npm install

# Sao chép file cấu hình
cp .env.example .env
# Sửa VUE_APP_BASE_URL=http://localhost:8000/admin

# Khởi động môi trường dev
npm run serve

# Build production
npm run build
```

### 3. Frontend (Nuxt.js)

```bash
cd front-hanquocnori

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

### 4. Cấu hình Redis

```bash
# Ubuntu/Debian
sudo apt install redis-server
sudo systemctl start redis
sudo systemctl enable redis

# Test kết nối
redis-cli ping  # => PONG
```

### 5. Cấu hình Cron Job

```bash
# Thêm vào crontab (cron scheduler Laravel)
* * * * * cd /path/to/api-hanquocnori && php artisan schedule:run >> /dev/null 2>&1
```

---

## Cấu hình môi trường

### Backend (`.env`)

```env
APP_NAME=HanQuocNori
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

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

# JWT
JWT_SECRET=

# AWS S3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=

# Pusher (Real-time)
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=

# Mail
MAIL_DRIVER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
```

### Frontend (`.env`)

```env
API_BASE_URL=https://api.yourdomain.com
PUSHER_APP_KEY=
FACEBOOK_APP_ID=
GOOGLE_ANALYTICS_ID=
GOOGLE_TAG_MANAGER_ID=
```

### Admin (`.env`)

```env
VUE_APP_BASE_URL=https://api.yourdomain.com/admin
```

---

## API & Routes

### Nhóm routes chính (Backend)

| Nhóm | File | Mô tả |
|---|---|---|
| API công khai | `routes/api.php` | REST API cho frontend |
| Admin | `routes/web-admin.php` | Quản trị hệ thống |
| Frontend | `routes/web-front.php` | API cho Nuxt frontend |
| Chung | `routes/web.php` | Routes web chung |

### Các endpoints tiêu biểu

**Authentication**
- `POST /api/login` - Đăng nhập
- `POST /api/register` - Đăng ký
- `POST /api/logout` - Đăng xuất
- `GET /api/auth/{provider}` - OAuth (Facebook, Google)

**Khóa học**
- `GET /api/courses` - Danh sách khóa học
- `GET /api/courses/{slug}` - Chi tiết khóa học
- `GET /api/lessons/{id}` - Chi tiết bài học
- `POST /api/courses/{id}/enroll` - Đăng ký học

**Bài thi**
- `GET /api/exams` - Danh sách bài thi
- `POST /api/exams/{id}/start` - Bắt đầu thi
- `POST /api/exams/{id}/submit` - Nộp bài thi

**Thanh toán**
- `POST /api/orders` - Tạo đơn hàng
- `POST /api/payments` - Xử lý thanh toán
- `GET /api/orders/history` - Lịch sử đơn hàng

---

## Database Schema

### Các bảng dữ liệu chính (76 Models)

**Users & Auth**
- `users` - Người dùng (học sinh)
- `admins` - Tài khoản quản trị
- `teachers` - Giáo viên

**Khóa học & Học tập**
- `courses` - Khóa học
- `lessons` - Bài học
- `exercises` - Bài tập
- `questions` - Câu hỏi
- `answers` - Đáp án
- `course_users` - Enrollment (người dùng - khóa học)
- `flashcards` - Thẻ ghi nhớ từ vựng

**Bài thi**
- `exams` - Bài kiểm tra/thi
- `take_exams` - Lần thi của người dùng
- `exam_results` - Kết quả thi

**Thương mại**
- `orders` - Đơn hàng
- `payments` - Thanh toán
- `carts` - Giỏ hàng
- `discount_codes` - Mã giảm giá
- `affiliate_codes` - Mã affiliate

**Lớp 1-1**
- `lesson_course_1_1` - Lớp học 1-1
- `combo_courses_one_one` - Combo khóa 1-1
- `course_managers` - Quản lý lớp học

**Nội dung**
- `books` - Sách
- `book_lessons` - Nội dung sách
- `news` - Tin tức/Blog
- `comments` - Bình luận
- `media` - Thư viện media

---

## Tích hợp bên thứ ba

| Dịch vụ | Mục đích |
|---|---|
| **AWS S3** | Lưu trữ media (hình ảnh, video, âm thanh) |
| **Pusher** | Real-time events, thông báo |
| **Google Analytics** | Theo dõi traffic website |
| **Google Tag Manager** | Quản lý tracking tags |
| **Facebook OAuth** | Đăng nhập bằng Facebook |
| **Google OAuth** | Đăng nhập bằng Google |
| **Infusionsoft CRM** | Quản lý khách hàng, marketing |
| **PayPal** | Thanh toán quốc tế |
| **Video.js + DASH** | Stream video bài học |

---

## Build & Deploy

### Admin Dashboard

```bash
npm run build
# Output: dist/ folder → deploy lên web server
```

### Frontend (Nuxt.js)

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

# Queue worker (cần chạy như service)
php artisan queue:work redis --daemon
```

### Yêu cầu server production
- **Web Server**: Nginx hoặc Apache
- **PHP-FPM**: 7.2.5+
- **MySQL**: 5.7+
- **Redis**: Lastest stable
- **SSL**: HTTPS (Let's Encrypt hoặc paid cert)
- **PM2** (cho Node.js Nuxt server)
- **Supervisor** (cho Laravel queue worker)

---

*Tài liệu được tạo: 2026-03-24*
