# Đặc tả chức năng chi tiết — MSD E-Learning Platform

> Tài liệu này mô tả chi tiết từng chức năng nghiệp vụ dựa trên việc đọc trực tiếp source code tại `f:/Projects/MSD/Source` (3 repo: `msd-api` — Laravel 11, `msd-admin` — Vue 3 SPA quản trị, `msd-front` — Nuxt 3 cổng học viên). Mọi field, route, enum, đường dẫn file nêu dưới đây đều lấy trực tiếp từ code hiện tại, không suy diễn.
>
> Dùng kèm với [`case-study-msd.md`](./case-study-msd.md) (bản tóm tắt cho case study công khai). Tài liệu này dùng để tham khảo nội bộ khi viết case study chi tiết hơn, hoặc khi cần biết chính xác một chức năng đã hoàn thiện tới đâu.
>
> **Quan trọng**: Mục cuối cùng ["Các vấn đề/khoảng trống kỹ thuật cần lưu ý"](#các-vấnđềkhoảng-trống-kỹ-thuật-cần-lưu-ý) liệt kê những chỗ code đã có scaffold/schema nhưng **chưa hoạt động thực sự** hoặc có sai lệch giữa các lớp (DB/API/FE). Nên đọc mục này trước khi viết case study để tránh mô tả quá mức (over-claim) những tính năng chưa hoàn thiện.

---

## Mục lục

1. [Khóa học (Courses)](#1-khóa-học-courses)
2. [Bài học (Lessons)](#2-bài-học-lessons)
3. [Quiz / Bài tập (Quiz Engine)](#3-quiz--bài-tập-quiz-engine)
4. [Chứng chỉ (Certificates)](#4-chứng-chỉ-certificates)
5. [Xử lý Video (Video Pipeline)](#5-xử-lý-video-video-pipeline)
6. [Thông báo (Notifications)](#6-thông-báo-notifications)
7. [Người dùng & Phân quyền (Users & Roles)](#7-người-dùng--phân-quyền-users--roles)
8. [Báo cáo / Dashboard (Reporting)](#8-báo-cáo--dashboard-reporting)
9. [Nội dung CMS](#9-nội-dung-cms)
10. [Bình luận (Comments)](#10-bình-luận-comments)
11. [Tìm kiếm (Search)](#11-tìm-kiếm-search)
12. [Các vấn đề/khoảng trống kỹ thuật cần lưu ý](#các-vấnđềkhoảng-trống-kỹ-thuật-cần-lưu-ý)

---

## 1. Khóa học (Courses)

### Mô tả nghiệp vụ
Đơn vị nội dung lớn nhất của nền tảng. Mỗi khóa học có tiêu đề, mô tả, giá, thời hạn truy cập, danh mục, tag, giáo viên phụ trách và danh sách bài học. Học viên "ghi danh" vào khóa học để bắt đầu học và theo dõi tiến độ.

### Model & bảng dữ liệu
`Course` (`msd-api/app/Models/Course.php`, bảng `courses`):
`title, price, description, introduction, number_month_expired, slug, meta, status, lesson_number, image_id, responsive_image_id, category_id, is_free, url_lading_page, not_buy, active, is_deal, what_you_will_learn, teacher_id` (cột cũ, xem ghi chú), `is_recommend`.

- `status`: 0=Lock, 1=Unlock. `active`: bool (mặc định 1). `is_free`: 1=miễn phí, 0=trả phí.
- Tự sinh `slug` từ `title` khi tạo/sửa (ném lỗi nếu trùng).
- Quan hệ: `image()`/`responsiveImage()` (Media), `teachers()` (nhiều-nhiều qua bảng `course_teacher`), `lessons()`, `tags()` (qua `course_tags`), `users()` (qua `course_managers`), `courseResources()`, `courseManagers()`.

**Bảng `course_managers`** (ghi danh học viên): `user_id, course_id, active_date, expiration_date, certificate` (đường dẫn PDF chứng chỉ), `completed` (0/1 — xem lưu ý ở mục 12).

**Category** (`categories`: `url, title, slug, description, active`), **Tag** (`tags`: `name, tag_type_id`, phân theo `TagType` với cờ `show_in_course`).

### API — public (`msd-api/routes/api.php`)
| Method | Path | Mô tả |
|---|---|---|
| GET | `/course/{slug}` | Chi tiết khóa học |
| GET | `/courses` | Danh sách khóa học |
| GET | `/categories` | Danh sách danh mục |
| GET | `/tags` | Danh sách tag |
| POST | `/course/{course}/learn` | Ghi danh (tạo `course_managers`), yêu cầu đăng nhập |
| GET | `/user/my-courses` | Khóa học của tôi |

*Không có route thanh toán/checkout* — "mua khóa học" hiện chỉ là gọi API ghi danh, không kiểm tra thanh toán nào ở backend.

### API — quản trị (`msd-api/routes/admin.php`, `auth:admin`)
`Route::resource('courses', ...)` (CRUD chuẩn) + `GET admin/courses/all` + `POST admin/courses/upload-image`. Phân quyền theo permission `create_course/update_course/delete_course/view_course/list_course`.

### Luồng nghiệp vụ chính
- **Tạo/sửa khóa học** (`CourseService`): xử lý ảnh (chính + responsive), đồng bộ giáo viên (pivot), tạo kèm bài học lồng nhau (kèm cả nhóm câu hỏi/câu hỏi/đáp án trong cùng request), đồng bộ tag, đẩy vào chỉ mục tìm kiếm nội bộ.
- **Ghi danh**: `msd-front` tự động gọi ghi danh **ngay khi học viên mở trang chi tiết khóa học** (không cần bấm nút riêng).
- **Tiến độ hiển thị**: % tiến độ = số bài học đã hoàn thành / tổng số bài học, **tính ở phía trình duyệt** (không phải từ cột `completed`).
- Trang danh sách khóa học lọc theo tag ở phía client (không gọi lại API khi đổi bộ lọc).
- Trang chi tiết hiện tab "Giảng viên" nhưng dùng field số ít `course.teacher` dù quan hệ dữ liệu là nhiều giáo viên/khóa học (xem mục 12).

---

## 2. Bài học (Lessons)

### Mô tả nghiệp vụ
Đơn vị học tập nhỏ nhất, thuộc về một khóa học, có thể tổ chức theo cấu trúc cha/con (chương → bài). Hỗ trợ 4 loại nội dung: Video, Văn bản/Hình ảnh, Bài tập, Flashcard.

### Model & bảng dữ liệu
`Lesson` (bảng `lessons`): `title, lesson_index, type, status, content, slug, reference_id, course_id, order, is_free, active, seo_title, seo_description, seo_key, time, allow_video_rewind`.

- `type`: `1=VIDEO, 2=TEXT_IMAGE, 3=EXCERCISE, 4=FLASHCARD`.
- `reference_id`: bài "cha" (chương) có giá trị NULL; bài "con" (nội dung thực) có `reference_id` = id bài cha.
- `allow_video_rewind`: 0=không cho tua, 1=cho tua video.
- `is_free`: 0/1 — gắn cờ miễn phí cho từng bài (xem lưu ý gating ở mục 12).

`LessonProcess` (bảng `lesson_processes`): `user_id, lesson_id, course_id, percent` (percent **luôn = 100**, thực chất là cờ nhị phân "đã hoàn thành bài học", không phải % xem video thật — xem mục 12).

### Cách hiển thị theo từng loại nội dung
- **VIDEO**: phát qua `video.js`, ưu tiên phát bản HLS đã mã hóa nếu có; nếu `allow_video_rewind=0` thì chặn tua video bằng plugin tùy chỉnh. Có thể gắn quiz bật lên tại mốc thời gian cụ thể trong video. Tự động đánh dấu hoàn thành khi còn ≤10 giây.
- **EXCERCISE (bài tập)**: chấm điểm hoàn toàn ở phía client; đạt ngưỡng **≥80% câu đúng** thì tự động đánh dấu hoàn thành bài học (ngưỡng hard-code, không cấu hình được từ admin).
- **TEXT_IMAGE**: có component hiển thị riêng nhưng **hiện chưa được gắn vào logic chọn loại bài học ở trang học** (xem mục 12) — cần xác nhận với dev đây là đang dang dở hay đã cố ý tắt.
- **FLASHCARD**: chỉ có bảng dữ liệu (`flashcards`), **chưa có API/giao diện xử lý nào** — tính năng chưa triển khai.

### Theo dõi tiến độ
Mỗi khi học viên hoàn thành 1 bài học, hệ thống ghi 1 bản ghi `lesson_processes` (không tạo trùng). Tiến độ khóa học = đếm số bản ghi này theo user/course, chia cho tổng số bài học, tính ở phía front-end.

---

## 3. Quiz / Bài tập (Quiz Engine)

### Mô tả nghiệp vụ
Hệ thống câu hỏi gắn với bài học hoặc mốc thời gian trong video, hỗ trợ nhiều loại câu hỏi (chọn 1, chọn nhiều, tự luận, đúng/sai, sắp xếp).

### Model & bảng dữ liệu
- `GroupQuestion` (`group_questions`): `lesson_id, title, time` (mốc giây trong video để bật popup câu hỏi).
- `Question` (`questions`): `content, question_type, index, score, level (độ khó), audio_id (câu hỏi có âm thanh), essay_answer, explanation, score_or_not`.
- `Answer` (`answers`): `content, is_correct, question_id, image_id, index_correct` (dùng cho loại sắp xếp).
- Loại câu hỏi (`question_type`): `1=Chọn 1 đáp án, 2=Chọn nhiều đáp án, 3=Tự luận, 4=Ghép cặp (chưa có UI riêng), 5=Câu có câu hỏi con (đang tắt trong UI admin), 6=Đúng/Sai, 7=Sắp xếp (kéo-thả)`.

### Chấm điểm
**Không có chấm điểm phía server.** Toàn bộ việc so đáp án và tính % đúng được thực hiện ở front-end (Nuxt), tính lại mỗi lần làm bài, không lưu lại kết quả vào database.

- Bảng `take_answers` (dự kiến lưu bài làm của học viên) **tồn tại nhưng chưa được kết nối** — Controller/Service rỗng, không có route API nào để nộp bài. Tức là hệ thống hiện **không lưu lịch sử làm bài, không có giới hạn thời gian, không giới hạn số lần làm, không có ngưỡng đạt/không đạt cấu hình được** — chỉ có ngưỡng cứng 80% ở từng bài EXCERCISE (mục 2).

---

## 4. Chứng chỉ (Certificates)

### Mô tả nghiệp vụ
Sau khi hoàn thành khóa học, học viên có thể tự tải chứng chỉ hoàn thành (PDF/PNG) được cá nhân hóa theo tên, tên khóa học, thời lượng và ngày hoàn thành.

### Model & bảng dữ liệu
- `CertificateTemplate` (`certificate_templates`): `name, image (ảnh nền), fields (JSON tọa độ top/left cho 4 trường: full_name, title, total_time, current_date), is_default`.
- `CourseCertificate` (`course_certificates`): `course_id (unique), certificate_template_id` — mỗi khóa học chỉ gán được **một** mẫu chứng chỉ.

### Luồng sinh chứng chỉ
1. Học viên bấm "Tải chứng chỉ" (nút chỉ hiện khi tiến độ = 100%, tính client-side) → gọi `POST /user/generate-certificate`.
2. Server chọn mẫu chứng chỉ gán cho khóa học (hoặc mẫu mặc định nếu chưa gán), vẽ 4 trường thông tin lên ảnh nền bằng thư viện xử lý ảnh (font `Lobster-Regular`/`Saira-Regular`), xuất PNG rồi nhúng vào PDF khổ A4 ngang.
3. Lưu đường dẫn PDF vào `course_managers.certificate`.
4. **Server không kiểm tra lại** rằng học viên đã thực sự hoàn thành 100% khóa học trước khi cho phép sinh chứng chỉ — việc kiểm tra chỉ nằm ở chỗ ẩn/hiện nút phía giao diện.

### Công cụ tạo mẫu chứng chỉ (msd-admin)
Là **form nhập tọa độ Top/Left bằng số** cho 4 trường cố định (không phải giao diện kéo-thả trực quan như thường thấy ở các nền tảng khác), có chức năng xem trước (Preview) trước khi lưu.

---

## 5. Xử lý Video (Video Pipeline)

### Mô tả nghiệp vụ
Mỗi video bài giảng khi được tải lên sẽ tự động chạy qua một pipeline xử lý nền: tạo phụ đề song ngữ bằng AI, gắn watermark, mã hóa sang định dạng streaming (HLS), rồi tùy chọn đẩy lên lưu trữ đám mây (S3).

### Model & trạng thái xử lý
`EncryptMediaProcess` (`encrypt_media_processes`): `lesson_id, pid, start, end, status, message, encrypt_folder, file_name, media_id, is_canceled`.

Các trạng thái (thực dùng trong code): `START(1) → ADD_TRANSCRIPT(3) → ADD_WATER_MARK(4) → ENCRYPTING(5) → PUSH_TO_STORAGE(6) → COMPLETE(7)`, có `ERROR(99)` nếu có lỗi ở bất kỳ bước nào. Mỗi bước tạo một bản ghi mới (giữ lại lịch sử xử lý, không ghi đè).

### Các bước xử lý (Job `ProcessEncryptVideo`, chạy nền, timeout 5 giờ)
1. Di chuyển file video vào thư mục riêng của bài học.
2. **Tạo phụ đề tự động** (nếu bật trong cấu hình video): tách audio bằng `ffmpeg`, gửi qua **OpenAI Whisper** để nhận dạng giọng nói tiếng Việt (`.vtt`), sau đó dịch từng đoạn phụ đề sang tiếng Anh bằng **GPT-3.5** để tạo phụ đề song ngữ.
3. **Gắn watermark** (nếu bật): dùng `ffmpeg` để chèn chữ hoặc hình ảnh watermark (vị trí, màu, cỡ chữ cấu hình được trong phần Cài đặt của admin).
4. **Mã hóa/chuyển đổi sang HLS**: chạy script xử lý (`hls.sh`) để tạo video streaming thích ứng (`1080p.m3u8` + các segment `.ts`).
5. **Đẩy lên lưu trữ đám mây** (nếu bật): đồng bộ thư mục video lên AWS S3 qua AWS CLI.
6. Đánh dấu hoàn tất.

### Nơi phát video
Video HLS được phục vụ như file tĩnh công khai qua Laravel storage (không có endpoint streaming riêng, không có signed-URL/token hết hạn) — việc bảo vệ nội dung chủ yếu dựa vào: (a) phải gọi đúng API để lấy được URL, và (b) chặn tua video ở trình phát, chứ **không mã hóa quyền truy cập file** ở tầng hạ tầng.

---

## 6. Thông báo (Notifications)

### Mô tả nghiệp vụ
Hệ thống gửi thông báo trong ứng dụng (và email) cho học viên, hỗ trợ gửi ngay hoặc lên lịch theo ngày/tuần/tháng/ngày cụ thể, nhắm tới toàn bộ học viên, danh sách tùy chỉnh, hoặc học viên của một khóa học cụ thể.

### Model & bảng dữ liệu
- `Notification` (`notifications`): `title, image, message, link, type, audience (all/course/custom), target_ids (JSON), send_time_type (specific_date/daily/weekly/monthly), specific_date, start_date, end_date, send_day (JSON — thứ trong tuần hoặc ngày trong tháng), send_type (now/set_a_timer), status (0=tắt/1=bật)`.
- `NotificationTime` (`notification_times`): `notification_id, time` — một thông báo có thể có nhiều mốc giờ gửi trong ngày.
- `UserNotification` (`user_notifications`): bản ghi "hộp thư" đã snapshot sẵn nội dung cho từng người dùng (`user_id, notification_id, title, image, message, link, is_read, read_at`).

### Cơ chế xác định đối tượng nhận (khi gửi)
- `all` → toàn bộ user đang hoạt động (active).
- `custom` → danh sách user theo `target_ids`.
- `course` → toàn bộ học viên đã ghi danh khóa học chỉ định.

### Cơ chế lên lịch gửi (2 tầng, chạy qua Artisan command)
1. **`notifications:schedule`** (chạy mỗi giờ): quét các mốc giờ gửi rơi vào 1 giờ tới, xác định thông báo nào cần gửi hôm nay (theo loại lịch specific_date/daily/weekly/monthly); nếu đúng giờ hiện tại thì gửi ngay, nếu còn trong vòng 1 giờ tới thì đẩy vào Redis chờ.
2. **`notifications:process-redis`** (chạy tần suất dày hơn, ví dụ mỗi phút): quét Redis, gửi các thông báo đã đến đúng giờ.
3. **`notify:inactive-users`** (chạy 1 lần/ngày): tìm học viên không có hoạt động (không có `UserLog` nào) trong N ngày gần nhất (N cấu hình được trong phần Cài đặt), gửi email nhắc học lại.

> Lưu ý: cả 3 lệnh trên không được đăng ký lịch chạy tự động ở trong code Laravel — cần cron ở tầng server để kích hoạt định kỳ (xem mục 12).

### API cho học viên
`GET /user/notifications` (danh sách), `POST /user/notifications/read/{id}` (đánh dấu đã đọc), `POST /user/notifications/read-all`, `GET /user/notifications/unread-count`.

---

## 7. Người dùng & Phân quyền (Users & Roles)

### Người dùng (học viên)
`User` (bảng `users`): `email, password, full_name, phone, sex, birthday, type (0=Học viên/1=Giáo viên/2=Quản trị), country, avatar, active, address, ethnicity (dân tộc), disability (tình trạng khuyết tật), education (trình độ học vấn, từ Tiểu học đến Tiến sĩ), organization`.

- Các trường `ethnicity`, `disability`, `education` phục vụ trực tiếp mục đích **báo cáo tác động xã hội** của MSD (đo lường mức độ tiếp cận nhóm yếu thế).
- Người dùng học viên **không có hệ thống vai trò/quyền riêng** (không dùng Spatie Roles) — chỉ có cờ `type`.

### Quản trị viên (Admin)
`Admin` (bảng `admins`): `name, user_name, email, password, is_main, is_super_admin`. Dùng hệ thống phân quyền Spatie (roles/permissions) với **guard riêng `admin`**, tách biệt hoàn toàn với tài khoản học viên.

- Vai trò được seed sẵn: **"Admin Tổng"** (toàn quyền). Có định nghĩa vai trò "Admin Đơn Vị" trong code nhưng hiện **chưa được kích hoạt seed** (đang comment).
- Danh sách nhóm quyền: dashboard, role, user, category, course, student, tag, resources, comment, faq, news, certificate_template, course_certificate, notification, user_notification, teacher, user_log, banner, about_msd, course_banner, testimonial, learning_statistics, storage, watermark, video, contact — mỗi nhóm thường có quyền create/update/delete/view/list riêng.
- Tài khoản admin mặc định khi cài đặt: `user_name: admin`, mật khẩu khởi tạo `msd@2025` (**cần đổi ngay sau khi triển khai**).

### Nhật ký hoạt động
`UserLog` (`user_logs`: `user_id, course_id, lesson_id`) ghi lại 2 loại sự kiện: học viên **bắt đầu học một khóa học** và **xem một bài học** — dữ liệu này là nguồn cho biểu đồ "lượt truy cập theo ngày" ở dashboard và cho cơ chế nhắc học viên không hoạt động (mục 6).

### Đăng nhập
- **Học viên**: chỉ đăng nhập qua **Google OAuth** (xác thực token Google ở server, tự tạo tài khoản mới nếu email chưa tồn tại). Không có đăng ký/đăng nhập bằng email-mật khẩu đang hoạt động (code có sẵn nhưng đã bị vô hiệu hóa).
- **Quản trị viên**: đăng nhập bằng tên đăng nhập/mật khẩu riêng, không qua Google.
- Cả hai đều dùng token truy cập kiểu Bearer (Sanctum), token hiện **không có thời hạn hết hạn tự động**.

---

## 8. Báo cáo / Dashboard (Reporting)

### Mô tả nghiệp vụ
Trang tổng quan cho đội vận hành MSD theo dõi quy mô và hiệu quả chương trình đào tạo, có thể lọc theo khoảng thời gian.

### Các chỉ số hiển thị
- Tổng số học viên, tổng số khóa học, tổng số danh mục (không lọc theo ngày).
- Số học viên đang hoạt động / đã hoàn thành ít nhất 1 khóa học (lọc theo khoảng ngày).
- **Tỷ lệ hoàn thành theo từng khóa học** (biểu đồ cột) — *xem lưu ý ở mục 12, chỉ số này hiện luôn trả về 0%*.
- Top 5 giáo viên có nhiều khóa học nhất (biểu đồ Polar Area).
- Top 5 học viên hoàn thành nhiều khóa học nhất (biểu đồ Radar).
- Biểu đồ lượt truy cập theo ngày (Line chart, dựa trên `UserLog`).
- Biểu đồ bong bóng: số học viên mỗi khóa học; biểu đồ scatter: số học viên hoàn thành mỗi khóa học.

### Vị trí trong code
API: `GET admin/report/dashboard?start_date=...&end_date=...`. Giao diện: trang Dashboard trong `msd-admin`, dùng Chart.js để vẽ biểu đồ.

---

## 9. Nội dung CMS

Các module quản lý nội dung tĩnh/marketing, mỗi module có CRUD riêng trong `msd-admin`:

| Module | Trường chính | Ghi chú |
|---|---|---|
| **Tin tức (News)** | title, media, link, description, ngôn ngữ | Có tìm kiếm/upload ảnh riêng trong admin |
| **Tài nguyên (Resource)** | title, loại, url/media, ảnh thumbnail, gắn tag | Tài nguyên dùng chung, có thể gắn nhiều tag |
| **Tài nguyên theo khóa học (CourseResource)** | title, url/media, ảnh thumbnail, course_id | Tài nguyên riêng cho từng khóa học |
| **Hỏi đáp (FAQ)** | câu hỏi, câu trả lời, loại, ngôn ngữ | |
| **Banner** | title, url khóa học liên kết, nội dung, ảnh, ngôn ngữ | Có bảng con "List Content" cho các khối nội dung phụ trong banner |
| **Testimonial (Cảm nhận học viên)** | tên, ảnh, nội dung giới thiệu, url khóa học, ngôn ngữ | |
| **Giới thiệu MSD (About MSD)** | nội dung, ảnh/video, ngôn ngữ, loại | |
| **Giáo viên (Teacher)** | họ tên, email, sđt, giới tính, avatar, chức danh, đơn vị công tác, tiểu sử, lĩnh vực chuyên môn, kỹ năng, mạng xã hội (LinkedIn/Zalo/Facebook), trạng thái | Có thể liên kết với 1 tài khoản Admin |

---

## 10. Bình luận (Comments)

### Mô tả nghiệp vụ
Học viên bình luận theo từng bài học, hỗ trợ trả lời (reply) theo luồng 1 cấp (bình luận → trả lời), gắn thẻ (mention) người dùng khác.

### Model & bảng dữ liệu
`Comment` (`comments`): `lesson_id, course_id, user_id, content, parent_id, tag_user_id, reply_count`.

### Luồng nghiệp vụ
- Lấy danh sách bình luận gốc của một bài học (`parent_id IS NULL`), phân trang.
- Lấy danh sách trả lời theo `parent_id`, phân trang riêng.
- Khi tạo trả lời, tăng `reply_count` của bình luận cha.
- **Kiểm duyệt ở admin**: chỉ có xem danh sách và **xóa cứng** (không có chức năng duyệt/ẩn/gắn cờ vi phạm) — xóa bình luận cha không tự xóa các trả lời con (để lại dữ liệu mồ côi).

---

## 11. Tìm kiếm (Search)

### Mô tả nghiệp vụ
Tìm kiếm nội bộ (không dùng dịch vụ bên thứ ba như Elasticsearch/Algolia) trên 4 loại nội dung: Khóa học, Bài học, Tài nguyên, Tin tức.

### Cơ chế
- Bảng `search` (`title, source_id, type, data JSON`) được **đồng bộ tự động theo thời gian thực**: mỗi khi Khóa học/Bài học/Tin tức/Tài nguyên được tạo/sửa/xóa, một job nền cập nhật lại bảng `search` tương ứng.
- Có thêm lệnh `search:sync-all` để đồng bộ lại toàn bộ (dùng khi cần rebuild index).
- API tìm kiếm: `GET /api/search?q=...`, so khớp kiểu "chứa từ khóa" (LIKE %keyword%) trên tiêu đề, sắp xếp theo mới nhất — **chưa có thuật toán xếp hạng theo độ liên quan**.
- **Giao diện tìm kiếm ở `msd-front` hiện là ô nhập liệu chưa được kết nối** với API tìm kiếm (chưa có trang kết quả tìm kiếm, chưa submit khi nhấn Enter) — tính năng backend đã sẵn sàng nhưng chưa được sử dụng ở giao diện học viên.

---

## Các vấn đề/khoảng trống kỹ thuật cần lưu ý

Danh sách này tổng hợp từ việc đọc trực tiếp source code — nên tham khảo trước khi viết case study để tránh mô tả các tính năng chưa hoàn thiện như đã hoàn thiện:

1. **Tỷ lệ hoàn thành khóa học trên dashboard luôn = 0%**: cột `course_managers.completed` không có bất kỳ nơi nào trong code gán = 1. Trạng thái "hoàn thành" mà học viên thấy chỉ tính ở giao diện, không phản ánh vào số liệu báo cáo admin.
2. **Chứng chỉ có thể bị tải mà không hoàn thành thật 100%**: API sinh chứng chỉ không kiểm tra lại điều kiện hoàn thành ở server, chỉ ẩn/hiện nút ở giao diện.
3. **Flashcard**: chỉ có bảng dữ liệu, chưa có API/CRUD/giao diện nào — tính năng chưa triển khai, dù đã liệt kê là 1 trong 4 loại bài học.
4. **Loại bài học Văn bản/Hình ảnh (TEXT_IMAGE) và Flashcard không hiển thị được ở trang học bài** hiện tại (trang học chỉ xử lý Video và Bài tập) — cần xác nhận với đội dev đây là lỗi hay tính năng đang dang dở.
5. **Không lưu lịch sử làm bài quiz**: bảng `take_answers` chưa được kết nối API nào; không có giới hạn thời gian làm bài, không giới hạn số lần làm lại, không có ngưỡng đạt/không đạt cấu hình được (chỉ có ngưỡng cứng 80% cho bài tập).
6. **Công cụ thiết kế mẫu chứng chỉ** thực chất là form nhập tọa độ số (Top/Left), chưa phải giao diện kéo-thả trực quan như tên gọi "drag-drop" gợi ý.
7. **Video không có cơ chế bảo vệ bằng signed-URL/token hết hạn** — sau khi lấy được đường dẫn, file HLS được phục vụ như file tĩnh công khai.
8. **3 lệnh xử lý thông báo theo lịch chưa được đăng ký lịch chạy tự động trong code** — cần xác nhận cron ở tầng server (bên ngoài repo) đã được cấu hình đúng tần suất hay chưa.
9. **Tính năng tìm kiếm chưa được kết nối ở giao diện học viên** dù API backend đã hoạt động.
10. **Chưa có kiểm tra quyền truy cập nội dung trả phí ở tầng API** (cờ `is_free` tồn tại nhưng chưa gate được nội dung/API bài học theo quyền sở hữu khóa học).
11. Chưa có bộ test tự động thực sự (chỉ có test mẫu mặc định) và chưa có pipeline CI/CD trong cả 3 repo.
