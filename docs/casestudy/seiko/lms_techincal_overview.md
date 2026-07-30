# Seiko LMS — Tài Liệu Kỹ Thuật Chi Tiết

> **Phiên bản:** 1.0.0 | **Ngày:** 2026-03-24 | **Loại:** Technical Specification

---

## Mục lục

1. [Tổng quan kiến trúc](#1-tổng-quan-kiến-trúc)
2. [Backend — Laravel API](#2-backend--laravel-api)
   - 2.1 [Cấu trúc thư mục](#21-cấu-trúc-thư-mục)
   - 2.2 [Database Schema chi tiết](#22-database-schema-chi-tiết)
   - 2.3 [Model & Relationships](#23-model--relationships)
   - 2.4 [Authentication & Authorization](#24-authentication--authorization)
   - 2.5 [Service Layer](#25-service-layer)
   - 2.6 [API Routes](#26-api-routes)
   - 2.7 [Events & Broadcasting](#27-events--broadcasting)
   - 2.8 [Queue Jobs](#28-queue-jobs)
   - 2.9 [File Storage](#29-file-storage)
   - 2.10 [Error Handling Pattern](#210-error-handling-pattern)
3. [Admin Frontend — Vue 3](#3-admin-frontend--vue-3)
   - 3.1 [Cấu trúc thư mục](#31-cấu-trúc-thư-mục)
   - 3.2 [Router & Navigation Guards](#32-router--navigation-guards)
   - 3.3 [State Management — Pinia Stores](#33-state-management--pinia-stores)
   - 3.4 [API Service Layer](#34-api-service-layer)
   - 3.5 [Authentication Token](#35-authentication-token)
   - 3.6 [Internationalization](#36-internationalization)
   - 3.7 [Constants & Enums](#37-constants--enums)
   - 3.8 [Utility Functions](#38-utility-functions)
4. [Landing Page — Nuxt 3](#4-landing-page--nuxt-3)
5. [Cấu hình môi trường](#5-cấu-hình-môi-trường)
6. [Luồng dữ liệu & Sequence Diagrams](#6-luồng-dữ-liệu--sequence-diagrams)

---

## 1. Tổng quan kiến trúc

### 1.1 Kiến trúc tổng thể

```
┌─────────────────────────────────────────────────────────────────┐
│                         CLIENT SIDE                             │
│                                                                 │
│   ┌──────────────────┐          ┌──────────────────────────┐   │
│   │  Landing Page    │          │    Admin Dashboard        │   │
│   │  Nuxt 3 (SSR)    │          │    Vue 3 + TypeScript     │   │
│   │  Port: 3000      │          │    Port: 5173             │   │
│   └────────┬─────────┘          └────────────┬─────────────┘   │
└────────────┼───────────────────────────────  ┼ ────────────────┘
             │       Bearer Token (Sanctum)     │
             ▼                                  ▼
┌─────────────────────────────────────────────────────────────────┐
│                     LARAVEL 9 REST API                          │
│                        Port: 8000                               │
│                                                                 │
│  routes/api.php → Middleware → Controller → Service → Model     │
│                                                                 │
│  Middleware Stack:                                              │
│  - throttle:api                                                 │
│  - auth:sanctum                                                 │
│  - AdminMiddleware (role <= 1)                                  │
│  - AdminAndLecturerMiddleware (role <= 2)                       │
│  - LecturerMiddleware (role == 2)                               │
│  - StudentMiddleware (role == 3)                                │
└────────────────────┬──────────────────┬─────────────────────────┘
                     │                  │
           ┌─────────▼─────┐   ┌───────▼───────────────────┐
           │   MySQL DB    │   │    External Services        │
           │  seiko_v3     │   │                             │
           │  40+ tables   │   │  Pusher (Real-time push)    │
           │  UUID PKs     │   │  SMTP2GO (Transact email)   │
           │  Soft deletes │   │  Queue (Database driver)    │
           └───────────────┘   └─────────────────────────────┘
```

### 1.2 Tech stack tóm tắt

| Layer | Technology | Version |
|-------|-----------|---------|
| Backend API | Laravel (PHP) | 9.19 |
| Admin UI | Vue 3 + Vite + TypeScript | 3.2.47 / 4.3.4 / 5.0.4 |
| Landing Page | Nuxt 3 | 3.8.1 |
| Database | MySQL | 5.7+ |
| Auth | Laravel Sanctum | 2.11 |
| State Management | Pinia | 2.0.35 |
| Real-time | Pusher | JS 8.2.0 / PHP 7.2 |
| Styling | Tailwind CSS | 3.3.2 |
| Form Validation | VeeValidate | 4.9.2 |
| HTTP Client | Axios | 1.4.0 |

---

## 2. Backend — Laravel API

### 2.1 Cấu trúc thư mục

```
lmsapi-develop/
├── app/
│   ├── AppMain/
│   │   └── Services/               # Business logic layer
│   │       ├── AuthService.php
│   │       ├── UserService.php
│   │       ├── ClassRoomService.php
│   │       ├── ExamService.php
│   │       ├── ExerciseService.php
│   │       ├── StudentTimesheetService.php
│   │       ├── NotificationService.php
│   │       └── ...
│   ├── Console/
│   ├── Events/
│   │   ├── NotificationEvent.php
│   │   └── SendMailEvent.php
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              # Admin-scoped controllers
│   │   │   ├── Export/             # 7 export controllers
│   │   │   ├── Import/             # 2 import controllers
│   │   │   ├── Student/            # Student-scoped controllers
│   │   │   └── AuthController.php
│   │   ├── Middleware/
│   │   │   ├── AdminMiddleware.php
│   │   │   ├── StudentMiddleware.php
│   │   │   ├── LecturerMiddleware.php
│   │   │   └── CheckAdminOrTeacherMiddleware.php
│   │   └── Requests/               # Form request validation
│   ├── Jobs/
│   │   └── CreateStudentJob.php    # Async bulk import
│   ├── Models/                     # 37+ Eloquent models
│   └── Utilities/
│       ├── CommonUtility.php       # Response formatter
│       ├── MessageCode.php         # Error code constants
│       └── AppConst.php            # Application constants
├── config/
│   ├── auth.php                    # Sanctum config
│   └── cors.php                    # CORS policy
├── database/
│   ├── migrations/                 # 40+ migrations
│   └── seeders/
├── routes/
│   ├── api.php                     # Main route file
│   ├── api-admin.php               # Admin routes
│   ├── api-student.php             # Student routes
│   └── api-lecture.php             # Lecturer routes
└── storage/
    └── app/public/                 # File uploads
```

---

### 2.2 Database Schema chi tiết

#### Bảng `users`
```sql
CREATE TABLE users (
  id            VARCHAR(36)   PRIMARY KEY,        -- UUID
  role          TINYINT       NOT NULL,            -- 0=Admin, 1=SubAdmin, 2=Lecturer, 3=Student
  name          VARCHAR(255)  NOT NULL,
  email         VARCHAR(255)  UNIQUE NOT NULL,
  email_verified_at TIMESTAMP NULL,
  password      VARCHAR(255)  NOT NULL,
  size          VARCHAR(255)  NULL,               -- File quota
  remember_token VARCHAR(100) NULL,
  deleted_at    TIMESTAMP     NULL,               -- Soft delete
  created_at    TIMESTAMP     NULL,
  updated_at    TIMESTAMP     NULL
);
```

#### Bảng `student_details`
```sql
CREATE TABLE student_details (
  id            VARCHAR(36)   PRIMARY KEY,        -- UUID
  user_id       VARCHAR(36)   NOT NULL,            -- FK → users.id
  course_id     VARCHAR(36)   NOT NULL,            -- FK → courses.id
  phone         VARCHAR(255)  NULL,
  ims_code      VARCHAR(255)  NULL,               -- Mã học sinh
  gender        TINYINT       DEFAULT 0,          -- 0=Nam, 1=Nữ
  birth         VARCHAR(255)  NULL,
  address       VARCHAR(255)  NULL,
  note          LONGTEXT      NULL,
  deleted_at    TIMESTAMP     NULL,
  created_at    TIMESTAMP     NULL,
  updated_at    TIMESTAMP     NULL,
  UNIQUE KEY (ims_code)
);
```

#### Bảng `projects`
```sql
CREATE TABLE projects (
  id            VARCHAR(36)   PRIMARY KEY,
  name          VARCHAR(255)  NOT NULL,
  class         VARCHAR(255)  NULL,
  address       VARCHAR(255)  NULL,
  is_priority   TINYINT       DEFAULT 0,
  status        TINYINT       DEFAULT 1,          -- 0=Inactive, 1=Active
  image         VARCHAR(255)  NULL,
  start_date    DATE          NULL,
  user_id       VARCHAR(36)   NULL,               -- FK → users.id (owner)
  deleted_at    TIMESTAMP     NULL,
  created_at    TIMESTAMP     NULL,
  updated_at    TIMESTAMP     NULL
);
```

#### Bảng `courses`
```sql
CREATE TABLE courses (
  id            VARCHAR(36)   PRIMARY KEY,
  project_id    VARCHAR(36)   NOT NULL,            -- FK → projects.id
  name          VARCHAR(255)  NOT NULL,
  description   TEXT          NULL,
  start_date    DATE          NULL,
  status        TINYINT       DEFAULT 1,
  deleted_at    TIMESTAMP     NULL,
  created_at    TIMESTAMP     NULL,
  updated_at    TIMESTAMP     NULL
);
```

#### Bảng `class_rooms`
```sql
CREATE TABLE class_rooms (
  id                  VARCHAR(36)   PRIMARY KEY,
  name                VARCHAR(255)  NOT NULL,
  course_id           VARCHAR(36)   NOT NULL,      -- FK → courses.id
  level_id            TINYINT       NULL,           -- FK → levels (1–5)
  color               VARCHAR(50)   NULL,           -- Màu hiển thị lịch
  total_period_class  VARCHAR(255)  NULL,           -- Tổng số buổi học
  start_date          DATE          NULL,
  end_date            DATE          NULL,
  status              TINYINT       DEFAULT 1,      -- 0=Inactive, 1=Active
  note                TEXT          NULL,
  classroom_teacher_id VARCHAR(36)  NULL,           -- FK → classroom_teachers.id
  deleted_at          TIMESTAMP     NULL,
  created_at          TIMESTAMP     NULL,
  updated_at          TIMESTAMP     NULL
);
```

#### Bảng `class_room_users` (Enrollment)
```sql
CREATE TABLE class_room_users (
  id                    VARCHAR(36)   PRIMARY KEY,
  class_room_id         VARCHAR(36)   NOT NULL,    -- FK → class_rooms.id
  user_id               VARCHAR(36)   NOT NULL,    -- FK → users.id
  current_month_challenge TEXT        NULL,
  solution              TEXT          NULL,
  result                TEXT          NULL,
  next_month_challenge  TEXT          NULL,
  comment               TEXT          NULL,
  status                TINYINT       DEFAULT 1,   -- 0=Inactive, 1=Active
  time_update_status    DATE          NULL,        -- Ngày thay đổi trạng thái
  deleted_at            TIMESTAMP     NULL,
  created_at            TIMESTAMP     NULL,
  updated_at            TIMESTAMP     NULL
);
```

#### Bảng `period_classes` (Buổi học)
```sql
CREATE TABLE period_classes (
  id            VARCHAR(36)   PRIMARY KEY,
  class_room_id VARCHAR(36)   NOT NULL,            -- FK → class_rooms.id
  name          VARCHAR(255)  NOT NULL,
  shift         TINYINT       NOT NULL,            -- 0=AM, 1=PM
  date          DATE          NOT NULL,
  note          TEXT          NULL,
  user_id       VARCHAR(36)   NULL,               -- FK → users.id (giảng viên)
  index         INT           NULL,               -- Thứ tự buổi học
  status        TINYINT       DEFAULT 1,
  deleted_at    TIMESTAMP     NULL,
  created_at    TIMESTAMP     NULL,
  updated_at    TIMESTAMP     NULL
);
```

#### Bảng `exercises`
```sql
CREATE TABLE exercises (
  id              VARCHAR(36)   PRIMARY KEY,
  period_class_id VARCHAR(36)   NOT NULL,         -- FK → period_classes.id
  type            TINYINT       NULL,             -- 1=Vocab, 2=Grammar, 3=Reading
  type_question   TINYINT       NULL,             -- 1=File, 2=Link
  type_answer     TINYINT       NULL,             -- 1=File, 2=Link
  name            VARCHAR(255)  NOT NULL,
  time_late       INT           NULL,             -- Phút trễ cho phép
  start_date      DATETIME      NULL,
  end_date        DATETIME      NULL,
  url_question    VARCHAR(255)  NULL,             -- Path tới file đề bài
  url_answer      VARCHAR(255)  NULL,             -- Path tới file đáp án
  user_id         VARCHAR(36)   NULL,             -- FK → users.id (giảng viên tạo)
  type_exercise   VARCHAR(255)  NULL,
  description     LONGTEXT      NULL,
  deleted_at      TIMESTAMP     NULL,
  created_at      TIMESTAMP     NULL,
  updated_at      TIMESTAMP     NULL
);
```

#### Bảng `exercise_submits`
```sql
CREATE TABLE exercise_submits (
  id            VARCHAR(36)   PRIMARY KEY,
  exercise_id   VARCHAR(36)   NOT NULL,           -- FK → exercises.id
  user_id       VARCHAR(36)   NOT NULL,           -- FK → users.id (học sinh)
  student_file  VARCHAR(255)  NULL,               -- Path file bài nộp
  comment       TEXT          NULL,               -- Nhận xét giảng viên
  submitted_at  DATETIME      NULL,               -- Thời điểm nộp
  status        TINYINT       NULL,               -- 1=OnTime, 2=Late, 3=NotSubmit
  created_at    TIMESTAMP     NULL,
  updated_at    TIMESTAMP     NULL
);
```

#### Bảng `exams`
```sql
CREATE TABLE exams (
  id              VARCHAR(36)   PRIMARY KEY,
  period_class_id VARCHAR(36)   NOT NULL,         -- FK → period_classes.id
  type            TINYINT       NULL,             -- 1=MiniTest, 2=Comprehensive
  part            TINYINT       NULL,             -- 1=Vocab, 2=Grammar, 3=Reading, 4=Listen, 5=Pronounce
  type_question   TINYINT       NULL,             -- 1=File, 2=Link
  type_answer     TINYINT       NULL,
  score_scale     VARCHAR(255)  NULL,             -- Thang điểm (VD: "10", "100")
  start_date      DATETIME      NULL,
  end_date        DATETIME      NULL,
  url_question    VARCHAR(255)  NULL,
  url_answer      VARCHAR(255)  NULL,
  user_id         VARCHAR(36)   NULL,
  type_exam       VARCHAR(255)  NULL,
  is_scored       BOOLEAN       DEFAULT 0,        -- Đã chấm điểm?
  description     LONGTEXT      NULL,
  deleted_at      TIMESTAMP     NULL,
  created_at      TIMESTAMP     NULL,
  updated_at      TIMESTAMP     NULL
);
```

#### Bảng `exam_submits`
```sql
CREATE TABLE exam_submits (
  id            VARCHAR(36)   PRIMARY KEY,
  exam_id       VARCHAR(36)   NOT NULL,           -- FK → exams.id
  user_id       VARCHAR(36)   NOT NULL,           -- FK → users.id
  score         VARCHAR(255)  NULL,               -- Điểm số
  comment       TEXT          NULL,
  student_file  VARCHAR(255)  NULL,
  submitted_at  DATETIME      NULL,
  created_at    TIMESTAMP     NULL,
  updated_at    TIMESTAMP     NULL
);
```

#### Bảng `timesheets` (Điểm danh)
```sql
CREATE TABLE timesheets (
  id                  VARCHAR(36)   PRIMARY KEY,
  period_class_id     VARCHAR(36)   NOT NULL,     -- FK → period_classes.id
  type                TEXT          NULL,         -- JSON: [status_array]
  user_id             VARCHAR(36)   NOT NULL,     -- FK → users.id
  status              TINYINT       NULL,
  time_late           INT           NULL,         -- Phút đến muộn
  time_come_back_soon INT           NULL,         -- Phút về sớm
  reason              VARCHAR(255)  NULL,
  denided_reason      VARCHAR(255)  NULL,
  created_at          TIMESTAMP     NULL,
  updated_at          TIMESTAMP     NULL
);
```

#### Bảng `student_timesheets` (Đơn xin nghỉ)
```sql
CREATE TABLE student_timesheets (
  id                VARCHAR(36)   PRIMARY KEY,
  timesheet_id      VARCHAR(36)   NOT NULL,       -- FK → timesheets.id
  status            TINYINT       NULL,           -- Trạng thái duyệt
  reason            VARCHAR(255)  NULL,           -- Lý do
  detail_reason     TEXT          NULL,
  application_type  VARCHAR(255)  NULL,
  image             VARCHAR(255)  NULL,           -- Path file minh chứng
  check             BOOLEAN       DEFAULT 0,
  created_at        TIMESTAMP     NULL,
  updated_at        TIMESTAMP     NULL
);
```

#### Bảng `notifications`
```sql
CREATE TABLE notifications (
  id          VARCHAR(36)   PRIMARY KEY,
  type        TINYINT       NOT NULL,             -- 1–12 (loại thông báo)
  message     LONGTEXT      NOT NULL,             -- JSON encoded content
  status      TINYINT       DEFAULT 0,            -- 0=Unread, 1=Read
  user_id     VARCHAR(36)   NOT NULL,             -- FK → users.id (người gửi)
  receiver_id VARCHAR(36)   NOT NULL,             -- FK → users.id (người nhận)
  created_at  TIMESTAMP     NULL,
  updated_at  TIMESTAMP     NULL
);
```

#### Bảng `period_class_documents`
```sql
CREATE TABLE period_class_documents (
  id              VARCHAR(36)   PRIMARY KEY,
  period_class_id VARCHAR(36)   NOT NULL,         -- FK → period_classes.id
  url             VARCHAR(255)  NULL,             -- Path file tài liệu
  type            VARCHAR(255)  NULL,             -- Loại file
  description     TEXT          NULL,
  start_date      DATE          NULL,
  user_id         VARCHAR(36)   NULL,
  deleted_at      TIMESTAMP     NULL,
  created_at      TIMESTAMP     NULL,
  updated_at      TIMESTAMP     NULL
);
```

#### Bảng `student_files`
```sql
CREATE TABLE student_files (
  id          VARCHAR(36)   PRIMARY KEY,
  submit_id   VARCHAR(36)   NOT NULL,             -- FK → exam_submits/exercise_submits
  file        VARCHAR(255)  NOT NULL,             -- Path file
  type        TINYINT       NOT NULL              -- 0=Exam, 1=Exercise
  -- No timestamps
);
```

---

### 2.3 Model & Relationships

```php
// User Model
class User extends Authenticatable {
    use SoftDeletes, HasApiTokens;

    protected $primaryKey = 'id';        // UUID string
    public $incrementing = false;
    protected $keyType = 'string';

    public function studentDetail()   { return $this->hasOne(StudentDetail::class); }
    public function classRoomUsers()  { return $this->hasMany(ClassRoomUser::class); }
    public function lecturerTimesheets() { return $this->hasMany(LecturerTimesheet::class); }
}

// ClassRoom Model
class ClassRoom extends Model {
    use SoftDeletes;

    public function periodClasses()   { return $this->hasMany(PeriodClass::class); }
    public function course()          { return $this->hasOne(Course::class, 'id', 'course_id'); }
    public function classroomTeachers() { return $this->hasMany(ClassroomTeacher::class); }
    public function totalUser()       { return $this->hasMany(ClassRoomUser::class); }
    public function level()           { return $this->hasOne(Level::class, 'id', 'level_id'); }
}

// PeriodClass Model
class PeriodClass extends Model {
    use SoftDeletes;

    public function classRoom()       { return $this->belongsTo(ClassRoom::class); }
    public function teacher()         { return $this->hasOne(User::class, 'id', 'user_id'); }
    public function documents()       { return $this->hasMany(PeriodClassDocument::class); }
    public function exercises()       { return $this->hasMany(Exercise::class); }
    public function exams()           { return $this->hasMany(Exam::class); }
    public function lecturerTimesheets() { return $this->hasMany(LecturerTimesheet::class); }
}

// Exercise Model
class Exercise extends Model {
    use SoftDeletes;
    public function submits()         { return $this->hasMany(ExerciseSubmit::class); }
}

// Exam Model
class Exam extends Model {
    use SoftDeletes;
    public function submits()         { return $this->hasMany(ExamSubmit::class); }
}

// ExamSubmit Model
class ExamSubmit extends Model {
    public function studentFiles()    { return $this->hasMany(StudentFile::class, 'submit_id'); }
}

// Course Model
class Course extends Model {
    use SoftDeletes;
    public function classRooms()      { return $this->hasMany(ClassRoom::class); }
    public function project()         { return $this->hasOne(Project::class, 'id', 'project_id'); }
}

// Project Model
class Project extends Model {
    use SoftDeletes;
    public function courses()         { return $this->hasMany(Course::class); }
}

// ClassRoomUser Model (enrollment pivot)
class ClassRoomUser extends Model {
    use SoftDeletes;
    public function classRoom()       { return $this->hasOne(ClassRoom::class, 'id', 'class_room_id'); }
}
```

---

### 2.4 Authentication & Authorization

#### Sanctum Token Flow

```
POST /api/login
  ├── Validate {email, password}
  ├── Find User by email
  ├── Verify password (Hash::check)
  ├── [If Lecturer] Validate status == active
  ├── createToken('authToken') → PersonalAccessToken
  └── Return {token: plainTextToken, user: {...}}

GET /api/* (protected)
  ├── Header: Authorization: Bearer {token}
  ├── Sanctum resolves → User model
  └── Request continues

POST /api/logout
  └── $request->user()->currentAccessToken()->delete()
```

#### Middleware Chain

```php
// Kernel.php alias
'admin'                → AdminMiddleware::class
'adminAndLecturer'     → CheckAdminOrTeacherMiddleware::class
'lecturerAndStudent'   → CheckLecturerAndStudentMiddleware::class
'student'              → StudentMiddleware::class
'lecturer'             → LecturerMiddleware::class

// Middleware logic
AdminMiddleware:
  if ($user->role > 1) return 403;       // Only admin (0) & sub-admin (1)

AdminAndLecturerMiddleware:
  if ($user->role > 2) return 403;       // Admin + sub-admin + lecturer

LecturerMiddleware:
  if ($user->role != 2) return 403;      // Only lecturer (2)

StudentMiddleware:
  if ($user->role != 3) return 403;      // Only student (3)

LecturerAndStudentMiddleware:
  if ($user->role < 2) return 403;       // Lecturer (2) or Student (3)
```

#### Role Hierarchy

```
role = 0  →  Admin         (full access)
role = 1  →  Sub-Admin     (management access, no system admin)
role = 2  →  Lecturer      (class & content management)
role = 3  →  Student       (view & submit only)
```

---

### 2.5 Service Layer

#### AuthService

```php
/**
 * User authentication
 * @throws Exception if credentials invalid or lecturer inactive
 */
public function login(string $email, string $password): array
{
    // 1. Find user by email
    // 2. Hash::check($password, $user->password)
    // 3. If lecturer: validate status === ACTIVE
    // 4. $user->createToken('authToken')->plainTextToken
    // 5. Return ['token' => ..., 'user' => ...]
}

/**
 * Password reset via email link
 */
public function forgotPassword(string $email): void
{
    // 1. Find user by email
    // 2. Create temp token (random string)
    // 3. Fire SendMailEvent with reset URL
}

/**
 * Apply new password from reset token
 */
public function changePassword(string $token, string $newPassword): bool
{
    // 1. Decode user from token
    // 2. Hash::make($newPassword)
    // 3. Delete all existing tokens (security)
}
```

#### ClassRoomService

```php
/**
 * Create classroom with student & teacher assignments
 */
public function addNew(array $input): JsonResponse
{
    // 1. Validate course exists + active
    // 2. DB::transaction()
    // 3. Create ClassRoom record
    // 4. For each student_id in json_decode($input['student_ids']):
    //    ClassRoomUser::create([class_room_id, user_id, status=1])
    // 5. For each teacher_id in json_decode($input['teacher_ids']):
    //    ClassroomTeacher::create([class_room_id, user_id])
    // 6. Return success
}

/**
 * Update classroom (diff on students/teachers)
 */
public function update(string $id, array $input): JsonResponse
{
    // 1. Validate period dates don't conflict
    // 2. DB::transaction()
    // 3. Update ClassRoom record
    // 4. Diff: new_students - existing = to add
    // 5. Diff: existing - new_students = to remove (soft delete)
    // 6. Same for teachers
}

/**
 * Date overlap logic for student filtering
 */
private function checkTimeGetStudent(
    Carbon $searchStart, Carbon $searchEnd,
    Carbon $learnStart, Carbon $learnEnd
): bool {
    // Overlap: max(starts) <= min(ends)
    return max($searchStart, $learnStart) <= min($searchEnd, $learnEnd);
}
```

#### ExamService

```php
/**
 * Create new exam with file upload
 * @return array ['checkFile' => bool, 'data' => Exam|null]
 */
public function store(array $data): array
{
    // 1. Validate: no duplicate exam name in same period
    // 2. Validate: comprehensive exam type unique per period
    // 3. Upload question file → storage/period/exam/
    // 4. Upload answer file → storage/period/exam/answer/
    // 5. Track file size usage (FileService)
    // 6. Create Exam record
    // 7. NotificationService: notify all students in class
    // 8. Return exam data
}

/**
 * Grade student exam submission
 */
public function markExam(array $input): JsonResponse
{
    // 1. Find ExamSubmit by exam_id + user_id
    // 2. Update: score, comment
    // 3. NotificationService: notify student of grade
}
```

#### ExerciseService

```php
/**
 * Create exercise with deadline
 */
public function store(array $data): array
{
    // 1. Validate unique name per period
    // 2. Upload files to storage
    // 3. Create Exercise record
    // 4. NotificationService: new exercise notification
}

/**
 * Student submits exercise answer
 */
public function submitExercise(Request $request, string $id): JsonResponse
{
    // 1. Find exercise by id
    // 2. Check current time vs end_date
    // 3. status = (now <= end_date) ? ON_TIME : LATE
    // 4. Upload student file → storage/exercise/user/answer/
    // 5. Update/Create ExerciseSubmit
}
```

#### StudentTimesheetService

```php
/**
 * Student submits absence request
 */
public function createHolidayStudent(array $input): JsonResponse
{
    // 1. Find all periods matching: date + shift + student's classes
    // 2. Validate period is active (status != 0)
    // 3. Create Timesheet records for each period
    // 4. Create StudentTimesheet with image evidence
    // 5. NotificationService: notify class teacher(s)
}

/**
 * Admin/Teacher reviews absence request
 */
public function checkUpdateHoliday(array $input, string $id): JsonResponse
{
    // 1. Find StudentTimesheet by id
    // 2. Update status (approved/denied)
    // 3. If denied: save denied_reason
    // 4. NotificationService: notify student of decision
}
```

#### NotificationService — 12 Loại thông báo

```php
// Notification type routing
public function saveNotification(array $query): void
{
    switch ($query['type']) {
        case 1:  // Student requests absence → notify teacher
        case 2:  // Student updates absence → notify teacher
        case 3:  // Teacher approves/denies → notify student
        case 4:  // Class absent (period cancelled) → notify students
        case 5:  // Class not absent → notify students
        case 6:  // Teacher changed → notify students
        case 7:  // New exercise created → notify students
        case 8:  // Student submits exercise → notify teacher
        case 9:  // New exam created → notify students
        case 10: // Student submits exam → notify teacher
        case 11: // Cancel absence approval → notify student
        case 12: // Exam time updated → notify students
    }
    // 1. Create Notification record in DB
    // 2. Fire NotificationEvent (Pusher broadcast)
}
```

---

### 2.6 API Routes

#### CORS Configuration

```php
// config/cors.php
'paths'           => ['api/*', 'sanctum/csrf-cookie'],
'allowed_methods' => ['*'],
'allowed_origins' => ['*'],                   // Cần restrict trong production
'allowed_headers' => ['*'],
'supports_credentials' => false,
```

#### Route Groups

```php
// routes/api.php structure
Route::prefix('api')->group(function () {

    // === PUBLIC (No Auth) ===
    POST   /login
    POST   /logout
    POST   /sendPasswordResetLink
    POST   /auth/reset-password
    GET    /project
    GET    /course
    POST   /advise-add

    // === AUTHENTICATED (Sanctum) ===
    Route::middleware('auth:sanctum')->group(function () {

        GET    /auth/detail-user
        GET    /check-permission

        // === ADMIN / SUB-ADMIN ===
        Route::middleware('admin')->group(function () {
            // CRUD: /admin/admin, /admin/project, /admin/course
            // CRUD: /admin/class-room, /admin/lecturer, /admin/student
            // Export: /admin/export/*
            // Import: /admin/import/*
            // Notification: /admin/notifications, /admin/read-notification
        });

        // === ADMIN / LECTURER (shared) ===
        Route::middleware('adminAndLecturer')->group(function () {
            // /period/create-document, /period/attendance
            // /period/create-exercise, /period/create-exam
            // /period/mark, /period/list-answer-exercise
        });

        // === LECTURER ONLY ===
        Route::middleware('lecturer')->group(function () {
            // /lecturer/list-projects
            // /lecturer/save-holiday-lecturer
        });

        // === STUDENT ONLY ===
        Route::middleware('student')->group(function () {
            // /student/list-class
            // /student/submit-answer-exercise-in-period/{id}
            // /student/submit-answer-exam-in-period/{id}
            // /student/create-holiday-student
        });

        // === LECTURER + STUDENT ===
        Route::middleware('lecturerAndStudent')->group(function () {
            GET /get-current-period
        });
    });
});
```

#### Response Format

```json
// Success
{
  "status": true,
  "code": "message.SUC_01",
  "data": { ... }
}

// Paginated
{
  "status": true,
  "code": "message.SUC_01",
  "data": {
    "data": [...],
    "total": 100,
    "per_page": 20,
    "current_page": 1,
    "last_page": 5
  }
}

// Error
{
  "status": false,
  "code": "ERR_01",
  "message": "Thông báo lỗi"
}

// Validation Error (422)
{
  "status": false,
  "code": 422,
  "errors": {
    "email": [{"type": "required", "field": "email"}]
  }
}
```

---

### 2.7 Events & Broadcasting

#### NotificationEvent

```php
class NotificationEvent implements ShouldBroadcast
{
    public string $message;         // JSON-encoded notification

    public function broadcastOn(): Channel
    {
        return new Channel('my-channel');   // Pusher channel name
    }

    public function broadcastAs(): string
    {
        return 'my-event';                  // Pusher event name
    }
}
```

#### SendMailEvent

```php
class SendMailEvent
{
    public Information $data;       // Contains: email, subject, template, user, link
    // Templates:
    //   'auth.forgot-password'    → Password reset email
    //   'auth.send-password'      → New user welcome email (with default pass)
}
```

---

### 2.8 Queue Jobs

#### CreateStudentJob

```php
class CreateStudentJob implements ShouldQueue
{
    /**
     * Xử lý import học sinh từ Excel
     * Validate 7 bước → DB transaction → Ghi kết quả vào completed_jobs
     */
    public function handle(): void
    {
        // STEP 1: Validate course tồn tại trong project
        // STEP 2: Validate classroom tồn tại trong course
        // STEP 3: Check unique email (within import + database)
        // STEP 4: Check unique ims_code (within import + database)
        // STEP 5: Validate required fields (name, email, ims_code)
        // STEP 6: Validate field lengths (name<=100, phone=10-15 digits)
        // STEP 7: Validate email format (regex + DNS MX lookup)

        // If errors found:
        //   CompletedJob::create([status='failed', errors=...])
        //   return;

        // DB::beginTransaction()
        // For each valid row:
        //   User::create([id=Uuid::generate(4), role=3, password=Hash::make('123456')])
        //   StudentDetail::create([user_id, ims_code, phone, ...])
        //   ClassRoomUser::create([class_room_id, user_id, status=1])
        // DB::commit()

        // CompletedJob::create([status='success', count=N])
    }

    // Headers at row 1+2 (data starts row 3)
    // Excel columns: name, email, ims_code, phone, gender, birth, address, note, course, class
}
```

---

### 2.9 File Storage

#### Upload Path Mapping

```
storage/app/public/
├── period/
│   ├── document/               ← Tài liệu bài học (upload bởi giảng viên)
│   ├── exercise/               ← File đề bài tập
│   │   └── answer/             ← File đáp án bài tập
│   └── exam/                   ← File đề thi
│       └── answer/             ← File đáp án thi
├── exercise/
│   └── user/
│       └── answer/             ← File bài nộp của học sinh (bài tập)
├── exam/
│   └── user/
│       └── answer/             ← File bài nộp của học sinh (thi)
├── holiday/
│   └── student/                ← File minh chứng xin nghỉ
├── projects/                   ← Ảnh project
└── file_size/
    └── file-{user_id}          ← File track quota dung lượng
```

#### URL Transformation

```php
// Lưu vào DB: 'public/period/document/filename.pdf'
// Truy cập: str_replace('public', 'storage', $path)
// → URL: 'storage/period/document/filename.pdf'
// → Full URL: http://domain.com/storage/period/document/filename.pdf
```

---

### 2.10 Error Handling Pattern

#### Controller Pattern

```php
public function store(Request $request): JsonResponse
{
    try {
        $result = $this->service->store($request->all());
        return CommonUtility::getSuccessResponseCode($result, 'Thành công', 200);
    } catch (\Exception $e) {
        Log::error($e->getMessage());
        return CommonUtility::getErrorResponseErrorCode('ERR_CODE', $e->getMessage());
    }
}
```

#### AppConst — Application Constants

```php
class AppConst {
    // Roles
    const ROLE_ADMIN      = 0;
    const ROLE_SUB_ADMIN  = 1;
    const ROLE_LECTURER   = 2;
    const ROLE_STUDENT    = 3;

    // Status
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    const STATUS_NOT_ACTIVE = 3;

    // Shift
    const SHIFT_AM = 0;
    const SHIFT_PM = 1;

    // File type
    const TYPE_FILE = 1;
    const TYPE_LINK = 2;

    // Submission status
    const SUBMIT_ON_TIME   = 1;
    const SUBMIT_LATE      = 2;
    const NOT_SUBMITTED    = 3;

    // Exam types
    const EXAM_MINI        = 1;
    const EXAM_COMPREHENSIVE = 2;

    // Notification types (1–12)
    const NOTI_HOLIDAY_REQUEST  = 1;
    const NOTI_HOLIDAY_UPDATE   = 2;
    const NOTI_APPROVAL         = 3;
    const NOTI_ABSENT_PERIOD    = 4;
    // ... up to 12
}
```

---

## 3. Admin Frontend — Vue 3

### 3.1 Cấu trúc thư mục

```
lmsadmin-develop/src/
├── assets/                         # CSS, images, fonts
├── components/                     # Reusable components
│   ├── Calendar/
│   │   ├── CalendarDesktop.vue
│   │   ├── CalendarMobile.vue
│   │   └── ScheduleCalendar.vue
│   ├── Modal/
│   ├── Form/
│   └── ...
├── composables/                    # Vue 3 composables
├── router/
│   └── index.ts                    # Route definitions + guards
├── services/
│   ├── api.js                      # Axios instance + interceptors
│   ├── jwt.service.js              # Cookie token management
│   ├── jws.service.js              # localStorage/sessionStorage
│   └── i18n.js                     # Vue i18n config
├── stores/                         # Pinia stores (36 stores)
│   ├── auth.ts
│   ├── project.ts
│   ├── class.ts
│   ├── student.ts
│   ├── teacher.ts
│   ├── course.ts
│   ├── exam.ts
│   ├── exercise.ts
│   ├── period.ts
│   ├── notification.ts
│   ├── absent.ts
│   ├── absentStudent.ts
│   ├── assess.ts
│   ├── schedule.ts
│   ├── dashboard.ts
│   ├── loading.ts
│   ├── toasted.ts
│   ├── error.ts
│   └── ...
├── utils/
│   ├── constant.js                 # App-wide constants & enums
│   └── time.js                     # Date/time formatting utilities
├── views/pages/                    # Page components
│   ├── Login.vue
│   ├── Dashboard.vue
│   ├── class-room/
│   ├── users/
│   ├── period/
│   └── ...
└── main.ts                         # App entry point
```

---

### 3.2 Router & Navigation Guards

#### Route Định nghĩa (24 routes)

```typescript
// Public routes (no auth required)
{ path: '/login',           component: Login }
{ path: '/send-email',      component: SendEmail }
{ path: '/change-password', component: ChangePassword }

// Admin/SubAdmin routes (meta.role: [ROLE_ADMIN, ROLE_SUB_ADMIN])
{ path: '/list-project',    component: ListProject }
{ path: '/list-admin',      component: ListAdmin }
{ path: '/list-teacher',    component: ListTeacher }
{ path: '/project/:slug_project/list-student',  component: ListStudent }
{ path: '/project/:slug_project/class-room',     component: ClassRoom }
{ path: '/project/:slug_project/list-assess',    component: ListAssess }

// Teacher routes (meta.role: [ROLE_TEACHER])
{ path: '/teaching-schedules/:id', component: TeachingSchedules }
{ path: '/list-absent',            component: ListAbsent }

// Student routes (meta.role: [ROLE_STUDENT])
{ path: '/schedule-student',  component: ScheduleStudent }
{ path: '/list-class',         component: ListClass }
{ path: '/list-exercise',      component: ListExercise }
{ path: '/list-exam',          component: ListExam }
```

#### Navigation Guard Logic

```typescript
router.beforeEach(async (to, from, next) => {
    // 1. Check if route requires auth
    if (!to.meta.requiresAuth) return next();

    // 2. Validate JWT token exists
    const token = getToken();
    if (!token) return next('/login');

    // 3. Load current user (if not loaded)
    await authStore.getDetailUser();

    // 4. Check role access
    const allowedRoles = to.meta.role as number[];
    if (allowedRoles && !allowedRoles.includes(authStore.role)) {
        return next('/'); // Redirect to home
    }

    // 5. Student auto-redirect: '/' → '/schedule-student'
    if (authStore.role === ROLE_STUDENT && to.path === '/') {
        return next('/schedule-student');
    }

    // 6. Permission check (some routes need server-side check)
    if (to.meta.checkPermission) {
        const allowed = await authStore.checkPermission({...});
        if (!allowed) return next('/');
    }

    // 7. Validate project slug against user's accessible projects
    if (to.params.slug_project) {
        const valid = await validateProject(to.params.slug_project);
        if (!valid) return next('/list-project');
    }

    next();
});
```

---

### 3.3 State Management — Pinia Stores

#### Auth Store

```typescript
// src/stores/auth.ts
export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null as any,
        is_authenticated: false,
        user_id: '' as string,
        role: -1 as number,
        total_size: 0 as number,   // Total file quota (bytes)
        size: 0 as number,         // Used file size (bytes)
        isChangePassword: false
    }),

    actions: {
        async login(payload: {email: string, password: string}) {
            const res = await api.post(API.LOGIN, payload);
            saveToken(res.token);
            this.user = res.user;
            this.role = res.user.role;
            this.is_authenticated = true;
        },

        async getDetailUser() {
            const res = await api.get(API.GET_DETAIL_USER);
            // Loads user + file size info
        },

        async checkPermission(payload: object): Promise<boolean> {
            const res = await api.get(API.CHECK_PERMISSION, {params: payload});
            return res.status;
        }
    }
});
```

#### Class Store

```typescript
// src/stores/class.ts
interface Class {
    id: number;
    name: string;
    course_id: string;
    level_id: string;
    status: number;
    color: string;
    user_id: string;            // Primary teacher
    total_user_count: number;
    total_period: number;
}

export const useClassStore = defineStore('class', {
    state: () => ({
        listClass: [] as Class[],
        detailClass: {} as Class,
        studentsInClass: [] as Student[],
        studentsInCourse: [] as Student[],
        total: 0,
        page: 1
    }),

    actions: {
        async getListClass(payload?: SearchClassPayload) {
            // GET /admin/class-room?project_id=...&search=...&page=...
        },

        async addClassRoom(payload: FormData) {
            // POST /admin/class-room (FormData with student_ids JSON)
        },

        async deleteStudentsInClass(studentId: string) {
            // DELETE /admin/class-room/delete-student-in-class/:studentId
        }
    }
});
```

#### Period Store

```typescript
// src/stores/period.ts
export const usePeriodStore = defineStore('period', {
    state: () => ({
        information: null,
        documents: [] as any[],
        exercises: [] as any[],
        exams: [] as any[],
        teachers: [] as any[],
        period: null
    }),

    actions: {
        async getDetailPeriodClass(id: string) {
            // GET /period/:id
            // Loads: information, documents, exercises, exams, teachers
        },

        async studentUploadAnswerExam(payload: FormData, id: string) {
            // POST /student/submit-answer-exam-in-period/:id
            // FormData with multiple files
        },

        addExercise(exercise: any)  { this.exercises.push(exercise); },
        deleteExercise(ex: any)     { this.exercises = this.exercises.filter(e => e.id !== ex.id); },
        addExam(exam: any)          { this.exams.push(exam); },
        deleteExam(exam: any)       { this.exams = this.exams.filter(e => e.id !== exam.id); }
    }
});
```

---

### 3.4 API Service Layer

#### Axios Configuration

```javascript
// src/services/api.js
const apiClient = axios.create({
    baseURL: import.meta.env.VITE_URL_API,
    headers: { 'Content-Type': 'application/json' }
});

// REQUEST INTERCEPTOR
apiClient.interceptors.request.use((config) => {
    // 1. Attach Bearer token
    const token = getToken();  // from Cookie
    if (token) config.headers.Authorization = `Bearer ${token}`;

    // 2. Auto-convert date fields to UTC
    //    Unless config.is_convert === false
    const dateFields = ['start_date', 'end_date'];
    if (config.data && !config.data.is_convert) {
        dateFields.forEach(field => {
            if (config.data[field]) {
                config.data[field] = convertDateTimeToUTC(config.data[field]);
            }
        });
    }
    return config;
});

// RESPONSE INTERCEPTOR
apiClient.interceptors.response.use(
    (response) => {
        if (!response.config.disableToastSuccess) {
            toastStore.showSuccess(response.data.message);
        }
        return response.data;   // Returns only payload (no axios wrapper)
    },
    (error) => {
        const { status, data } = error.response;

        if (!error.config.disableToastError) {
            toastStore.showError(data.message);
        }

        if (status === 422) {
            // Parse field errors → errorStore
            // Map error codes to field names:
            // EXE_05 → 'name'
            // EXE_06 → 'file_question'
            // EXE_07 → 'file_answer'
            // EXA_07 → 'file_question'
            // EXA_08 → 'file_answer'
            // DOC_05 → 'file'
            errorStore.setErrors(data.errors);
        }

        return Promise.reject(error);
    }
);
```

---

### 3.5 Authentication Token

```javascript
// src/services/jwt.service.js
const TOKEN_KEY = 'token';
const EXPIRY = 60 * 60 * 24;   // 24 hours in milliseconds

export const getToken    = ()        => Cookies.get(TOKEN_KEY);
export const saveToken   = (token)   => Cookies.set(TOKEN_KEY, token, { expires: EXPIRY });
export const destroyToken = ()       => Cookies.remove(TOKEN_KEY);

// src/services/jws.service.js — Session/Local Storage
export const getProjectIDDefault    = () => sessionStorage.getItem('projectIDDefault');
export const saveProjectIDDefault   = (id) => sessionStorage.setItem('projectIDDefault', id);
export const getLanguage            = () => localStorage.getItem('language') || 'vi';
export const setLanguage            = (lang) => localStorage.setItem('language', lang);
```

---

### 3.6 Internationalization

```javascript
// src/services/i18n.js
const i18n = createI18n({
    legacy: false,
    globalInjection: true,
    locale: getLanguage() || 'vi',   // Default: Vietnamese
    messages: {
        vi: viMessages,
        ja: jaMessages,
        kr: krMessages,
        en: enMessages
    }
});

// Language switching (requires page reload for full effect)
function switchLanguage(lang) {
    setLanguage(lang);
    location.reload();
}
```

Supported locales: `vi` (Vietnamese), `ja` (Japanese), `kr` (Korean), `en` (English)

---

### 3.7 Constants & Enums

```javascript
// src/utils/constant.js

// === ROLES ===
export const ROLE_ADMIN     = 0;
export const ROLE_SUB_ADMIN = 1;
export const ROLE_TEACHER   = 2;
export const ROLE_STUDENT   = 3;

// === SHIFT ===
export const SHIFT = { '0': 'AM', '1': 'PM', 'AM': 0, 'PM': 1 };

// === CLASS STATUS ===
export const CLASS_STATUS = { PROCESS: 1, DONE: 0, ALL: 2 };

// === SUBMISSION STATUS ===
export const SUBMIT      = 1;   // Nộp đúng hạn
export const SUBMIT_LATE = 2;   // Nộp trễ
export const NOT_SUBMIT  = 3;   // Chưa nộp

// === FILE TYPES ===
export const IS_FILE = 1;
export const IS_LINK = 2;
export const AUDIO      = 1;
export const MICROSOFT  = 2;
export const PDF        = 3;
export const LINK       = 4;
export const IMAGE      = 5;

// === EXAM TYPES ===
export const TYPE_MINI_TEST     = 1;
export const TYPE_COMPREHENSIVE = 2;

// === LEVEL ===
export const LEVEL = { N1: 1, N2: 2, N3: 3, N4: 4, N5: 5 };

// === ABSENCE REASONS ===
export const ABSENT_REASON = {
    ILL: 1,
    FAMILY_PROBLEMS: 2,
    EXAM_AGAIN: 3,
    OTHER: 4
};

// === NOTIFICATION MESSAGE TYPES ===
export const MES_01 = 'MES_01';   // Student absent request
export const MES_02 = 'MES_02';   // Update absent request
export const MES_03 = 'MES_03';   // Approval/denial
export const MES_04 = 'MES_04';   // Period cancelled
export const MES_05 = 'MES_05';   // Period not cancelled
export const MES_06 = 'MES_06';   // Teacher changed
export const MES_07 = 'MES_07';   // New exercise
export const MES_08 = 'MES_08';   // Student submits exercise
export const MES_09 = 'MES_09';   // New exam
export const MES_10 = 'MES_10';   // Student submits exam
export const MES_11 = 'MES_11';   // Cancel absence approval
export const MES_12 = 'MES_12';   // Exam time updated

// === DATE FORMATS ===
export const FORMAT_DATE_API     = 'YYYY-MM-DD';
export const FORMAT_DATE_VN      = 'DD/MM/YYYY';
export const FORMAT_DATE_JA      = 'YYYY/MM/DD';
export const FORMAT_DATE_EN      = 'MM/DD/YYYY';
export const FORMAT_DATE_KR      = 'YYYY년 MM월 DD일';
export const FORMAT_DATE_TIME_VN = 'HH:mm:ss DD/MM/YYYY';

// === API ENDPOINTS (all) ===
export const API = {
    LOGIN: '/login',
    PROJECT: 'project',
    COURSE: 'course',
    CLASS: '/admin/class-room',
    STUDENT: '/admin/student',
    TEACHER: '/admin/lecturer',
    PERIOD: 'period',
    ATTENDANCE: '/period/attendance',
    MARK: '/period/mark',
    UPLOAD_EXERCISE: '/period/create-exercise',
    UPDATE_EXERCISE: '/period/update-exercise',
    DELETE_EXERCISE: '/period/delete-exercise',
    UPLOAD_EXAM: '/period/create-exam',
    UPDATE_EXAM: '/period/update-exam',
    DELETE_EXAM: '/period/delete-exam',
    CHECK_IS_SCORED: '/period/check-is-scored/{id}',
    UPDATE_STATUS_EXERCISE: '/period/update-status-exercise',
    GET_LIST_ANSWER_EXERCISE: '/period/list-answer-exercise/{exercise_id}/class/{class_room_id}',
    GET_LIST_ANSWER_EXAM: '/period/list-answer-exam/{exam_id}/class/{class_room_id}',
    GET_LIST_STUDENT_ATTENDANCE: '/period/list-student-attendance/{id}/class/{class_room_id}',
    STUDENT_SUBMIT_ANSWER: '/student/submit-answer-exercise-in-period/{id}',
    STUDENT_SUBMIT_ANSWER_EXAM: '/student/submit-answer-exam-in-period/{id}',
    IMPORT_SCHEDULE: '/admin/import-schedule',
    CHECK_PERMISSION: '/check-permission',
    // ... (40+ endpoints)
};
```

---

### 3.8 Utility Functions

```javascript
// src/utils/time.js

// Format cho hiển thị UI (locale-aware)
formatDateTime(date, format = 'DD/MM/YYYY HH:mm:ss')
formatDateExam(date, format = 'DD/MM/YYYY')

// Convert sang UTC trước khi gửi API
convertDateTimeToUTC(date, format = 'YYYY-MM-DD HH:mm:ss')

// Convert từ UTC về local time khi nhận API
timeToLocate(date, format = 'DD/MM/YYYY HH:mm:ss')
dateTimeToLocate(date, format = 'DD/MM/YYYY HH:mm:ss')
formatLocalTimeDate(date, format = 'HH:mm:ss DD/MM/YYYY')

// Validation
isValidDate(d): boolean
```

**Lưu ý quan trọng:** Axios interceptor tự động convert `start_date` và `end_date` sang UTC khi gửi request (trừ khi config `is_convert: false`).

---

## 4. Landing Page — Nuxt 3

### Cấu trúc

```
landingpage-develop/
├── pages/
│   ├── index.vue           # Trang chủ giới thiệu
│   └── register.vue        # Form đăng ký học sinh
├── components/             # Shared components
├── composables/            # Vue 3 composables
├── layouts/                # Nuxt layouts
├── i18n/
│   ├── vi/                 # Vietnamese translations
│   └── en/                 # English translations
├── store/                  # Pinia stores
├── plugins/                # Nuxt plugins
├── assets/                 # Images, styles
└── nuxt.config.ts
```

### Tính năng

- **SSR** (Server-Side Rendering) via Nuxt 3
- Form đăng ký với validation (VeeValidate)
- Đa ngôn ngữ: Tiếng Việt, Tiếng Anh
- Responsive design (Tailwind CSS)
- Image optimization (@nuxt/image)
- Kết nối API để gửi đơn đăng ký

---

## 5. Cấu hình môi trường

### Backend `.env`

```env
APP_NAME="Seiko LMS"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=seiko_v3
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=pusher
CACHE_DRIVER=file
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
SESSION_DRIVER=file

PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_key
PUSHER_APP_SECRET=your_secret
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

MAIL_MAILER=smtp
MAIL_HOST=mail.smtp2go.com
MAIL_PORT=2525
MAIL_USERNAME=your_smtp_user
MAIL_PASSWORD=your_smtp_pass
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

### Admin Frontend `.env`

```env
VITE_URL_API=http://localhost:8000/api
```

### Landing Page `.env`

```env
NUXT_PUBLIC_API_BASE=http://localhost:8000/api
```

---

## 6. Luồng dữ liệu & Sequence Diagrams

### 6.1 Đăng nhập

```
Client                    API                      DB
  │                         │                       │
  ├─ POST /api/login ───────►│                       │
  │  {email, password}       │                       │
  │                         ├─ SELECT users WHERE ──►│
  │                         │   email = ?            │
  │                         │◄─ User record ─────────┤
  │                         │                       │
  │                         ├─ Hash::check()        │
  │                         ├─ createToken() ───────►│
  │                         │                       │(insert personal_access_tokens)
  │◄─ {token, user} ────────┤                       │
```

### 6.2 Học sinh nộp bài thi

```
Student App               API                      DB / Storage
     │                     │                           │
     ├─ POST /student/      │                           │
     │  submit-answer-exam  │                           │
     │  /{exam_id}          │                           │
     │  [FormData files]    │                           │
     │                     ├─ Validate exam exists ────►│
     │                     ├─ Check end_date < now      │
     │                     ├─ Upload files to ──────────►Storage
     │                     │  storage/exam/user/answer/ │
     │                     ├─ Create ExamSubmit ───────►│
     │                     ├─ Create StudentFiles ─────►│
     │                     ├─ Fire NotificationEvent    │
     │                     │  (notify teacher)          │
     │◄─ {success} ────────┤                           │
```

### 6.3 Thông báo thời gian thực

```
Event (server)          Pusher              Client (Frontend)
     │                    │                      │
     ├─ NotificationEvent │                      │
     │  broadcastOn()     │                      │
     ├─ push to ──────────►                      │
     │  'my-channel'      │                      │
     │  event: 'my-event' │                      │
     │                    ├─ WebSocket push ─────►│
     │                    │                      ├─ notificationStore.addMessage()
     │                    │                      ├─ Update badge count
     │                    │                      └─ Show toast
```

### 6.4 Import học sinh từ Excel

```
Admin                  API              Queue (DB)          DB
  │                     │                   │               │
  ├─ POST /import/student                   │               │
  │  [Excel file]        │                   │               │
  │                     ├─ Parse Excel      │               │
  │                     ├─ Dispatch Job ───►│               │
  │◄─ {job_id} ─────────┤                   │               │
  │                     │                   │               │
  │ (polling)            │                   │               │
  ├─ GET /check-job-working                 │               │
  │                     │                   ├─ Validate 7 steps
  │                     │                   ├─ DB::transaction
  │                     │                   ├─ Create users ►│
  │                     │                   ├─ Create details►│
  │                     │                   ├─ Enroll classes►│
  │                     │                   ├─ CompletedJob ►│
  │◄─ {status: done} ───┤◄──────────────────┤               │
```

---

*Tài liệu này được tạo từ phân tích toàn bộ source code của dự án Seiko LMS — SourceEGLIFE*
