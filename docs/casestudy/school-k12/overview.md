# Franchise K-12 Language Learning Platform — Tài liệu Dự án

> **⚠️ LƯU Ý QUAN TRỌNG VỀ NGUỒN & BẢO MẬT:** Nội dung dưới đây được xây dựng dựa trên một dự án THẬT đã trúng thầu và đang triển khai (nguồn: RFP + BRD + Đề xuất kỹ thuật lưu trong thư mục này), nhưng đã được **ẩn danh hoàn toàn** — không nêu tên khách hàng, tên thương hiệu nền tảng, mã số RFP, hay bất kỳ chi tiết nào có thể suy ra danh tính khách hàng thật, do các tài liệu gốc chịu ràng buộc NDA/bảo mật. Khi viết case study công khai từ tài liệu này: **không** copy nguyên văn tên riêng, mã dự án, số liệu định danh (vd. số tỉnh/đối tác cụ thể) từ 3 file nguồn. Các con số ở mục "Mục tiêu & ngưỡng thiết kế" là **chỉ tiêu kỹ thuật đặt ra khi thiết kế**, KHÔNG phải kết quả vận hành đã được xác nhận — chưa có xác nhận go-live/số liệu thật để công bố là "kết quả". Khi có xác nhận chính thức (và được phép công bố), thay thế mục Kết quả bằng số liệu thật.

---

## Tổng quan

Nền tảng học tiếng Anh trực tuyến dành cho học sinh phổ thông (K-12), vận hành theo **mô hình nhượng quyền đa cấp** (tổng bộ → tỉnh/thành → đối tác/trung tâm). Nền tảng đã trải qua 2 giai đoạn phát triển trước đó và đang bước vào giai đoạn nâng cấp lớn (Phase 3) để mở rộng quy mô ra nhiều tỉnh thành, đồng thời hiện đại hóa 3 mảng: chấm điểm luyện nói bằng AI, đăng nhập hợp nhất (SSO), và cấu trúc nội dung giảng dạy linh hoạt hơn.

Đây là case khác biệt so với các case LMS trung tâm hiện có (Seiko, HanQuocNori) ở 3 điểm: (1) mô hình **nhượng quyền đa cấp** với ranh giới dữ liệu nghiêm ngặt giữa các đối tác, (2) **AI chấm điểm phát âm** cho trẻ em kết hợp con người duyệt lại (human-in-the-loop), và (3) bài toán **tái cấu trúc dữ liệu** từ mô hình "Tuần học cố định" sang "Bài học linh hoạt" trên nền dữ liệu lịch sử hàng chục nghìn học sinh đang vận hành — không được phép có downtime hay mất dữ liệu.

## Bối cảnh & Bài toán

Nền tảng ở giai đoạn trước tập trung vào một địa bàn với mô hình vận hành tập trung. Khi nhân rộng ra nhiều tỉnh/đối tác nhượng quyền, 5 vấn đề cốt lõi xuất hiện:

1. **Mô hình tập trung không còn phù hợp khi nhân rộng** — hệ thống cũ chưa hỗ trợ phân quyền theo tỉnh/đối tác/cơ sở, mọi thứ vận hành như một đơn vị duy nhất.
2. **Chấm điểm luyện nói hoàn toàn thủ công** — giáo viên phải nghe và chấm tay từng bài ghi âm, tốn thời gian và thiếu tính khách quan/nhất quán giữa các giáo viên.
3. **Trải nghiệm học sinh còn hạn chế** — giao diện chưa tối ưu cho thiết bị di động, thiếu phản hồi tức thời sau khi làm bài.
4. **Thiếu cơ chế xác thực tập trung (SSO)** — tài khoản phân mảnh giữa các hệ thống nội bộ, tiềm ẩn rủi ro bảo mật và gây khó khăn cho việc tổng hợp báo cáo theo người dùng.
5. **Cấu trúc nội dung "Tuần học" cứng nhắc** — bài học tự động mở theo lịch cố định bất kể lớp nghỉ lễ hay dạy chậm tiến độ, không phù hợp khi triển khai ở nhiều địa phương với lịch riêng.

## Mục tiêu dự án

| Mục tiêu | Mô tả |
|---|---|
| Mở rộng quy mô | Nhân rộng chương trình ra nhiều tỉnh thành và đối tác nhượng quyền trong năm đầu triển khai |
| Nâng cao chất lượng đánh giá | Tích hợp AI chấm điểm kỹ năng nói, có sự giám sát của giáo viên |
| Cải thiện trải nghiệm người dùng | Tối ưu giao diện và quy trình làm bài, đặc biệt trên thiết bị di động |
| Tăng cường bảo mật | Triển khai SSO, đảm bảo mọi tài khoản có xác thực an toàn |
| Linh hoạt quản lý nội dung | Chuyển đổi cấu trúc nội dung từ "Tuần học" cố định sang "Bài học" linh hoạt |

## Phạm vi giải pháp

- **Read Aloud AI** — học sinh luyện nói theo quy trình Nghe → Ghi âm → Nghe lại → Nộp bài → Đọc hiểu; AI chấm 3 tiêu chí (phát âm, ngữ điệu, độ trôi chảy); kho nhận xét mẫu; admin cấu hình tham số AI theo khối/tuần/môn.
- **Phân quyền đa cấp** — mô hình 3 cấp Tổng bộ (Province) – Đối tác (Partner) – Cơ sở (School); phân quyền theo phạm vi dữ liệu; quản lý người dùng, lớp học, giáo viên, học sinh theo từng cấp.
- **SSO** — đăng nhập hợp nhất chuẩn OIDC; xử lý xung đột tài khoản khi email đã tồn tại; công cụ merge tài khoản thủ công cho admin; audit log cho hoạt động đăng nhập.
- **Quản lý nội dung giảng dạy** — chuyển đổi cấu trúc Tuần → Bài học; quản lý khóa học, kho học liệu.
- **Báo cáo & Analytics** — dashboard và báo cáo theo từng vai trò (ban học thuật, đối tác, giáo viên).
- **Đào tạo giáo viên** — hệ thống quản lý khóa đào tạo nội bộ, theo dõi tiến độ.
- **Gamification** — tích lũy sao theo kỹ năng, bảng xếp hạng.
- **Quản lý vòng đời học sinh** — chuyển cấp/chuyển trường trong năm, giữ lịch sử học tập.
- **Tối ưu UI/UX** — thiết kế lại giao diện học sinh, responsive trên mọi thiết bị.

*Ngoài phạm vi: tích hợp thanh toán trực tuyến, xây dựng mobile app native, tích hợp SSO với nhiều Identity Provider cùng lúc.*

## Thách thức kỹ thuật trọng tâm

### 1. Tái cấu trúc dữ liệu Tuần → Bài học (không downtime, không mất dữ liệu)
Thách thức nằm ở việc duy trì toàn vẹn dữ liệu lịch sử của hàng chục nghìn học sinh khi thay đổi mô hình dữ liệu lõi, trong khi hệ thống vẫn phải hoạt động liên tục.

**Giải pháp:**
- **Shadow Tables**: không `ALTER TABLE` trực tiếp trên các bảng hàng triệu bản ghi (gây table lock, treo ứng dụng) — khởi tạo cấu trúc bảng mới song song, chuyển dữ liệu theo lô (batch) chạy nền.
- **Auto-mapping có giám sát**: thuật toán tự động ánh xạ Tuần → Bài học dựa trên khung chương trình; các trường hợp đặc biệt (dạy bù, dạy gộp) đưa vào hàng đợi "chờ xử lý thủ công" với giao diện riêng cho giáo viên/admin.
- **Tương thích hai chiều tạm thời**: tầng ứng dụng xử lý được cả dữ liệu cũ (Tuần) và mới (Bài học) trong giai đoạn chuyển tiếp, tránh crash logic ở các module phụ thuộc.

### 2. Phân quyền đa cấp không rò rỉ dữ liệu giữa các đối tác nhượng quyền
Thiết lập sai ma trận phân quyền tiềm ẩn rủi ro rò rỉ dữ liệu kinh doanh giữa các đối tác — đồng thời truy vấn tổng hợp từ hàng trăm cơ sở có thể gây nghẽn hiệu năng khi hệ thống mở rộng.

**Giải pháp:**
- RBAC chuẩn hóa theo 5 nhóm quyền (Super Admin / Province Admin / Partner Admin / School Admin / Teacher), mỗi user được gán (binding) trực tiếp với một hoặc nhiều thực thể quản lý (Province/Partner/School ID).
- **Chặn ngay từ tầng Controller**: một lớp middleware/interceptor trích xuất Role + Entity ID từ token, đối chiếu với phạm vi dữ liệu của request trước khi logic nghiệp vụ được thực thi — truy cập trái phép trả về 403 ngay lập tức, không chạm tới database.
- **Đánh chỉ mục theo ID định danh** (B-Tree index trên Province/Partner/School ID) để truy vấn nhảy thẳng đến vùng dữ liệu liên quan thay vì quét toàn bảng.
- **Phân vùng dữ liệu theo thời gian** (range partitioning theo năm học) cho các bảng tăng trưởng nhanh (điểm số, nhật ký học tập, điểm danh) — dữ liệu năm học hiện tại ưu tiên lưu ở tầng tốc độ cao, dữ liệu cũ có thể tách (detach) sang lưu trữ rẻ hơn khi hết niên khóa.

### 3. Chấm điểm phát âm bằng AI cho trẻ em — chính xác, có giám sát, không quá tải
Giọng đọc trẻ em có âm sắc cao, tốc độ không đều, hay ngọng — AI khó đạt độ chính xác 100%; chấm quá khắt khe khiến học sinh nản, quá lỏng lẻo khiến phụ huynh mất niềm tin. Đồng thời cần tránh nghẽn hệ thống khi nhiều học sinh nộp bài cùng lúc.

**Giải pháp — pipeline bất đồng bộ:**
1. Học sinh ghi âm → upload trực tiếp lên object storage qua presigned URL (không qua API server, giảm tải băng thông).
2. API server tạo bản ghi trạng thái `pending`, đẩy job vào hàng đợi message queue, trả phản hồi ngay cho học sinh (không bắt chờ).
3. Worker chuyên biệt (tách riêng khỏi API chính) tiêu thụ hàng đợi, gọi dịch vụ AI Speech cấp doanh nghiệp (đã được kiểm chứng, huấn luyện trên giọng đọc đa dạng) để lấy điểm accuracy/fluency/phoneme/prosody.
4. Kết quả thô từ AI được đưa qua một bước LLM để sinh nhận xét bằng ngôn ngữ tự nhiên, thân thiện, dễ hiểu với học sinh — thay vì hiển thị số liệu khô khan.
5. **Human-in-the-loop**: bài chấm ở trạng thái "chờ duyệt", giáo viên nghe lại và có thể giữ nguyên hoặc chỉnh sửa điểm/nhận xét trước khi công bố chính thức cho học sinh — đảm bảo AI hỗ trợ chứ không thay thế hoàn toàn vai trò giáo viên.
6. Tách worker xử lý AI riêng khỏi API chính: nếu dịch vụ AI bên ngoài chậm/lỗi, chỉ worker bị ảnh hưởng, không kéo sập toàn hệ thống; giáo viên vẫn chấm tay được nếu AI gián đoạn (fallback).

### 4. Đăng nhập hợp nhất (SSO) & xử lý xung đột tài khoản
Chuyển từ tài khoản phân mảnh sang SSO chuẩn OIDC, nhưng phải xử lý được trường hợp một email đã tồn tại qua phương thức đăng nhập khác (vd. email/password) trước khi có SSO.

**Giải pháp:** khi phát hiện xung đột, hệ thống trả lỗi rõ ràng và hướng dẫn liên hệ admin thay vì tự động gộp (tránh rủi ro chiếm đoạt tài khoản); admin có giao diện riêng để duyệt và merge thủ công, ghi nhận liên kết nhiều định danh (identity) về cùng một user nội bộ — từ đó về sau, dù người dùng đăng nhập bằng phương thức nào, hệ thống đều nhận diện đúng một tài khoản duy nhất mà không cần can thiệp lại.

### 5. Bảng xếp hạng & báo cáo không làm chậm hệ thống chính
Tính toán thứ hạng của hàng chục nghìn học sinh theo thời gian thực, và các truy vấn báo cáo quét hàng triệu bản ghi điểm số, đều có thể làm chậm các tác vụ làm bài tập thường ngày của học sinh nếu chạy trực tiếp trên database chính.

**Giải pháp — Pre-computed Report Engine:** một tiến trình chạy định kỳ vào khung giờ thấp điểm (đêm) tính toán sẵn các chỉ số (điểm trung bình, tỷ lệ hoàn thành, thứ hạng...) và lưu kết quả vào tầng cache/object storage; khi người dùng xem báo cáo hoặc bảng xếp hạng, hệ thống đọc thẳng từ dữ liệu đã tính sẵn — gần như "zero-query" trên database chính, tách hoàn toàn khỏi luồng học tập thời gian thực của học sinh.

## Kiến trúc & Công nghệ đề xuất

Định hướng công nghệ: **Spring Boot 3 (Java) cho backend, Nuxt 3 / Vue 3 cho frontend** — tận dụng kinh nghiệm Vue 3 + Vite (admin) / Nuxt 3 (landing) đã có từ case Seiko LMS, đồng thời đáp ứng yêu cầu năng lực kỹ thuật "React, NodeJS/Java" của bên mời thầu (Java là lựa chọn được chấp nhận thay cho NodeJS).

| Thành phần | Công nghệ |
|---|---|
| Frontend (dashboard HS/GV/Admin) | Vue 3 + Vite (SPA sau đăng nhập) |
| Frontend (trang đăng nhập/SSO callback) | Nuxt 3 (SSR) |
| Backend API | Spring Boot 3 (Java 21 LTS), Spring Web |
| Authentication / SSO | Spring Security OAuth2 Client + Resource Server, xác thực JWT qua JWKS của Identity Provider (OIDC) |
| Phân quyền đa cấp | Spring `HandlerInterceptor`/filter chặn ở tầng request + `@PreAuthorize` method-level security |
| Database | PostgreSQL (Spring Data JPA/Hibernate), range partitioning theo năm học |
| Migration & data backfill | Flyway (versioned migration) + Spring Batch (xử lý theo chunk, tránh table lock) |
| Cache / Session | Redis (Spring Data Redis, chiến lược cache-aside) |
| Hàng đợi bất đồng bộ | Message queue (SQS-compatible) + `@Async`/worker riêng cho pipeline AI chấm điểm |
| AI Speech & LLM feedback | Gọi dịch vụ AI Speech cấp doanh nghiệp qua WebClient (reactive HTTP client), kết hợp LLM sinh nhận xét tự nhiên |
| Object storage | S3-compatible, upload qua presigned URL |
| Real-time (giáo viên duyệt bài) | Pusher (đã có kinh nghiệm triển khai thật ở HanQuocNori/Seiko) |
| API documentation | springdoc-openapi (tự sinh OpenAPI/Swagger từ annotation) |
| CI/CD | GitHub Actions — build/test/deploy tự động theo GitFlow |
| Testing | JUnit 5 + Testcontainers (Postgres/Redis thật trong test), performance & security test trước go-live |
| Hạ tầng | Container hóa (Docker) trên hạ tầng cloud có Auto Scaling, CDN + WAF ở tầng biên, Load Balancer, database Primary/Replica, cache managed service |

## Mục tiêu & ngưỡng thiết kế

*(Đây là chỉ tiêu kỹ thuật đặt ra khi thiết kế hệ thống — chưa phải kết quả vận hành đã xác nhận. Không dùng các số liệu định danh của khách hàng thật; các ngưỡng dưới đây đã được khái quát hóa.)*

- Thời gian tải trang dashboard/danh sách: dưới 3 giây.
- Thời gian xử lý bản ghi âm sau khi nộp bài: dưới 5 giây.
- Thời gian AI chấm điểm và trả kết quả: trong vòng 24 giờ (có thể cấu hình).
- Hệ thống chịu tải hàng nghìn người dùng đồng thời mà không suy giảm hiệu năng.
- Tổng thời gian xác thực SSO (từ lúc chọn đăng nhập đến khi vào được dashboard): dưới 10 giây.
- 100% tài khoản có cơ chế xác thực an toàn sau khi hoàn tất triển khai SSO.

## Rủi ro kỹ thuật & phương án giảm thiểu

| Rủi ro | Phương án giảm thiểu |
|---|---|
| Dịch vụ AI chấm điểm chậm/không ổn định | Kiến trúc hàng đợi bất đồng bộ tách biệt; giáo viên vẫn chấm thủ công được nếu AI lỗi; cơ chế retry |
| Số lượng lớn xung đột tài khoản khi bật SSO | Chuẩn bị script migrate hàng loạt trước go-live; công cụ merge hàng loạt cho admin |
| Tải cao đột biến vào giờ cao điểm | Load test trước go-live, auto scaling, read replica database, cache Redis |
| Sai sót trong phân quyền đa cấp gây rò rỉ dữ liệu | Security interceptor chặn ở tầng request, indexing theo entity ID, kiểm thử bảo mật riêng cho ma trận phân quyền |
| Mất dữ liệu/gián đoạn khi migrate Tuần → Bài học | Chiến lược shadow-table (không sửa bảng cũ), auto-mapping có giám sát thủ công cho trường hợp đặc biệt |

## Kết quả

*Chưa công bố số liệu vận hành — dự án đã trúng thầu và đang triển khai nhưng chưa có xác nhận chính thức về kết quả go-live để đưa vào case study công khai. Khi có xác nhận (và được phép công bố ở dạng ẩn danh), cập nhật phần này bằng kết quả thật thay vì để trống.*
