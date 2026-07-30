# Case Study Draft — MSD E-Learning Platform (for XO Edu Lab)

> Tài liệu nháp để viết case study đăng tại `edulab.xotech.space/case-studies`.
> Định dạng bám theo case study mẫu đã có trên trang ("TopThi — Living Lab cho Exam & AI Learning"):
> Headline → Subtitle → 3 chỉ số nổi bật → Vấn đề → Giải pháp → Kết quả.
> Các chỗ đánh dấu **[ ]** là số liệu/thông tin cần bạn xác nhận hoặc điền — mình không có quyền truy cập số liệu vận hành thực tế nên không tự bịa.

---

## ⚠️ Cần xác nhận trước khi đăng công khai

1. **MSD Vietnam** là một tổ chức phi lợi nhuận có thật (Viện Nghiên cứu Quản lý Phát triển bền vững, thành viên United Way Vietnam từ 2021). Trước khi public case study nêu tên/logo/dữ liệu của họ, nên xin xác nhận/đồng ý từ MSD — đặc biệt vì hệ thống có lưu dữ liệu nhạy cảm của người học (dân tộc, tình trạng khuyết tật).
2. Chưa rõ MSD có phải là dự án do XO Edu Lab trực tiếp xây dựng/vận hành hay không, hay chỉ là dự án bạn tham chiếu để làm case study nội bộ. Nếu đây là dự án của một công ty/khách hàng khác, cần làm rõ quyền sử dụng thông tin trước khi đăng lên trang case study công khai của XO Edu Lab.
3. Số liệu (uptime, số lượt học, tỷ lệ hoàn thành...) hiện **không có trong repo** — cần lấy từ dashboard báo cáo thực tế (`ReportController@dashboard` trong `msd-admin`) hoặc từ MSD.

---

## Headline

**MSD Learning Platform — Nền tảng học trực tuyến vì phát triển bền vững**

## Subtitle

Xây dựng nền tảng LMS phục vụ đào tạo cộng đồng, hỗ trợ đo lường tác động xã hội (người học dân tộc thiểu số, người khuyết tật) và bảo vệ nội dung khóa học có bản quyền.

## Chỉ số nổi bật (3 cột — cần điền số liệu thật)

| Chỉ số | Gợi ý nguồn lấy số liệu |
|---|---|
| **[ ]% Tỷ lệ hoàn thành khóa học** | Dashboard báo cáo admin (`ReportService` — completion rate) |
| **[ ]+ Người học đã tham gia** | Bảng `users` / dashboard tổng số user |
| **[ ]+ Khóa học / Chứng chỉ đã cấp** | Bảng `courses`, `course_certificates` |

*(Có thể thay bằng chỉ số khác nếu phù hợp hơn: số lượt xem video, số bài kiểm tra đã làm, % người học thuộc nhóm yếu thế...)*

---

## Vấn đề

MSD Vietnam cần một nền tảng học trực tuyến để triển khai các chương trình đào tạo cộng đồng về phát triển bền vững, hướng đến cả nhóm học viên phổ thông lẫn các nhóm yếu thế (dân tộc thiểu số, người khuyết tật) — vốn là đối tượng ưu tiên trong báo cáo tác động với các nhà tài trợ. Bài toán đặt ra gồm:

- Quản lý nội dung khóa học đa định dạng (video, văn bản, bài tập, flashcard) với quy trình biên tập, kiểm duyệt rõ ràng cho đội ngũ vận hành.
- Bảo vệ nội dung video có bản quyền/trả phí khỏi bị tải lậu hoặc "tua nhanh" để né học đủ bài — cần đảm bảo học viên học đúng, đủ nội dung trước khi được công nhận hoàn thành.
- Đo lường và báo cáo được mức độ tiếp cận tới các nhóm học viên yếu thế (dân tộc, khuyết tật, trình độ học vấn) để phục vụ báo cáo tài trợ/tác động xã hội.
- Gửi thông báo, nhắc nhở học tập đúng lúc, đúng đối tượng (theo khóa học, theo nhóm, hoặc toàn bộ học viên) để duy trì tỷ lệ hoàn thành khóa học.
- Tự động cấp chứng chỉ hoàn thành và có khả năng mở rộng nội dung song ngữ để tăng khả năng tiếp cận.

## Giải pháp

Xây dựng hệ thống 3 lớp: **msd-front** (Nuxt 3 — cổng học viên công khai), **msd-admin** (Vue 3 SPA — quản trị nội bộ) và **msd-api** (Laravel 11 — API trung tâm), với các năng lực chính:

- **LMS lõi**: Khóa học → Bài học (4 loại nội dung: video, văn bản/hình ảnh, bài tập, flashcard) → Bài kiểm tra/thi (ngân hàng câu hỏi có gắn độ khó, chấm điểm tự động, hỗ trợ câu hỏi âm thanh và tự luận) → theo dõi tiến độ học viên theo từng bài học và khóa học.
- **Bảo vệ nội dung video**: pipeline xử lý video nền (mã hóa, gắn watermark, tạo phụ đề) trước khi đẩy lên lưu trữ; trình phát video tùy chỉnh (Video.js) vô hiệu hóa tua nhanh để đảm bảo học viên xem đủ nội dung — mô hình đào tạo kiểu "chứng nhận hoàn thành".
- **Phụ đề song ngữ tự động bằng AI**: tích hợp OpenAI Whisper để tự động tạo phụ đề tiếng Việt và dịch sang tiếng Anh cho bài giảng video, tăng khả năng tiếp cận cho người học.
- **Cấp chứng chỉ tự động**: công cụ thiết kế mẫu chứng chỉ (kéo-thả vị trí trường thông tin), tự động sinh PDF/hình ảnh cá nhân hóa khi học viên hoàn thành khóa học.
- **Hệ thống thông báo có lịch trình**: engine thông báo chạy nền qua Redis + Pusher (real-time), hỗ trợ gửi ngay hoặc theo lịch (ngày/tuần/tháng/thời điểm cụ thể), nhắm đối tượng theo toàn bộ, theo danh sách tùy chỉnh, hoặc theo khóa học — kèm cơ chế tự động nhắc học viên không hoạt động.
- **Dữ liệu học viên phục vụ đo lường tác động**: mô hình dữ liệu người học ghi nhận dân tộc, tình trạng khuyết tật, trình độ học vấn, cho phép xuất báo cáo Excel phục vụ báo cáo tài trợ/tác động xã hội.
- **Dashboard báo cáo cho đội vận hành**: tổng số học viên, học viên hoạt động/hoàn thành, tỷ lệ hoàn thành khóa học, giáo viên/học viên nổi bật, theo dõi hoạt động theo thời gian.
- **Tìm kiếm nội bộ**: cơ chế đồng bộ và tìm kiếm riêng cho khóa học, bài học, tài liệu và tin tức mà không phụ thuộc dịch vụ bên thứ ba.
- **Đăng nhập Google (OAuth)** cho học viên, phân quyền vai trò (RBAC) riêng cho đội ngũ quản trị.

## Kết quả

*(Điền sau khi có số liệu thực tế/xác nhận từ MSD — gợi ý các hướng có thể nêu:)*

- Nền tảng vận hành ổn định phục vụ chương trình đào tạo cộng đồng của MSD, với khả năng mở rộng thêm khóa học/nội dung mới.
- Quy trình cấp chứng chỉ, gửi thông báo và bảo vệ nội dung được tự động hóa hoàn toàn, giảm tải vận hành thủ công cho đội ngũ MSD.
- Dữ liệu học viên theo nhóm yếu thế được thu thập có hệ thống, hỗ trợ trực tiếp công tác báo cáo tác động xã hội với nhà tài trợ/United Way Vietnam.
- [ ] Số liệu cụ thể: số học viên, tỷ lệ hoàn thành, số chứng chỉ đã cấp, thời gian vận hành ổn định (uptime)...

---

## Phụ lục kỹ thuật (tham khảo nội bộ, không cần đưa hết lên trang case study)

### Kiến trúc
- 3 repo tách biệt, dùng chung 1 backend API:
  - `msd-api` (Laravel 11, PHP 8.2) — REST API, tách 2 nhóm route: `api.php` (học viên) và `admin.php` (quản trị, guard riêng `auth:admin` + Spatie Permission).
  - `msd-front` (Nuxt 3) — cổng học viên, SSR/SEO.
  - `msd-admin` (Vue 3 + Vite + TypeScript + Pinia) — SPA quản trị nội bộ.
- Database quan hệ (SQLite dev / MySQL production), 73 migration, ~40+ bảng.
- Queue/cache: Redis (predis) + DB queue, xử lý nền qua Artisan command chạy cron:
  ```
  0 * * * *  php artisan notifications:schedule
  * * * * *  php artisan notifications:process-redis
  0 19 * * * php artisan notify:inactive-users
  ```
- Không có CI/CD (không có GitHub Actions/Dockerfile) — triển khai thủ công trên server kiểu LAMP truyền thống.

### Tech stack
- **Backend**: Laravel 11, Sanctum (auth), Spatie Permission (RBAC), DomPDF + Intervention Image (chứng chỉ), Maatwebsite Excel (export), Pusher, Redis, Guzzle (gọi OpenAI Whisper), Google API Client.
- **Admin (msd-admin)**: Vue 3, Vite, TypeScript, Pinia, Tailwind + SCSS, VeeValidate, Chart.js, FilePond, Quill (rich text), Vue Draggable.
- **Front (msd-front)**: Nuxt 3, Pinia, Nuxt i18n (đa ngôn ngữ), Nuxt Sitemap + nuxt-jsonld (SEO), Google Sign-In, Firebase SDK, Video.js (+ plugin tùy chỉnh chặn tua video), Pusher-js, Swiper.

### Tích hợp bên thứ ba
- Google OAuth (đăng nhập duy nhất đang hoạt động)
- OpenAI Whisper (phụ đề tự động song ngữ)
- AWS S3 (tùy chọn lưu trữ file)
- Pusher (real-time notification)
- Redis (hàng đợi + xử lý thông báo)
- ffmpeg (xử lý/mã hóa video)

### Điểm kỹ thuật đáng chú ý
- Pipeline xử lý video có trạng thái rõ ràng: `REMOVE_OLD_FILE → ADD_TRANSCRIPT → ADD_WATER_MARK → ENCRYPTING → PUSH_TO_STORAGE → COMPLETE`.
- Engine câu hỏi/bài thi đầy đủ: nhóm câu hỏi → câu hỏi (có độ khó, câu hỏi âm thanh, tự luận) → đáp án → bài làm của học viên.
- Mô hình dữ liệu học viên phục vụ báo cáo tác động xã hội (dân tộc, khuyết tật, trình độ học vấn) — điểm khác biệt so với LMS thương mại thông thường.
- Hiện chưa có test coverage thực sự (chỉ có test mẫu placeholder) và chưa có pipeline CI/CD — có thể nêu như định hướng cải thiện trong case study nếu muốn thể hiện tính khách quan/roadmap tiếp theo.
