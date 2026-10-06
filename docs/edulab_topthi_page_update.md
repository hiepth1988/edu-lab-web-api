# Cập nhật trang TopThi trên Edulab — nội dung cho đội phát triển

- Trang cần sửa: https://edulab.xotech.space/our-work/topthi (slug `topthi`)
- Ngày soạn: 2026-10-06
- Khung nội dung theo `_TEMPLATE.md` (các trường `project_translations` + bảng con). File này thay cho phần nội dung biên tập trong `casestudy/topthi/overview_updated.md`.
- Locale: bản này chỉ có `vi`. Bản `en` sẽ gửi sau.

## Tóm tắt thay đổi

Trang hiện tại giới thiệu TopThi là "nền tảng thi online" (chấm nhanh, chống gian lận, dashboard). Bản mới giới thiệu TopThi là **vòng học tập thích ứng có cơ sở khoa học**: đo năng lực theo từng kỹ năng, sinh đề luyện cá nhân mỗi ngày, ôn giãn cách, kiểm chứng tiến bộ bằng đánh giá độc lập.

Cần làm theo thứ tự:

1. **Gỡ ngay** những nội dung sai hoặc chưa có nguồn (mục 1). Việc này làm được trước, không cần chờ nội dung mới.
2. **Thay nội dung** theo các trường CMS ở mục 2.
3. **Thêm một section mới** tên "Khoa học phía sau" (mục 3). CMS hiện chưa có trường cho section này.
4. Chuẩn bị ảnh cho từng vị trí trong danh sách ở mục 5.
5. Giữ `status = draft` cho tới khi xong checklist ở mục 6.

---

## 1. Gỡ ngay khỏi trang hiện tại

| Vị trí trên trang | Nội dung hiện tại | Việc cần làm | Lý do |
|---|---|---|---|
| Hero stats, Results & Impact | "40% reduction in grading time" | Xóa | Không có nguồn. Với trắc nghiệm, chấm tự động gần như 100% nên con số này không có nghĩa |
| Hero stats, Results & Impact | "10,000+ exam submissions monthly" | Xóa | Chưa đo. Sẽ thay bằng số đã xác nhận (mục 4) |
| Hero stats, Results & Impact | "99.9% platform uptime" | Xóa | Chưa có monitoring uptime, và đã có sự cố thật |
| User Journey bước 2, Feature Map | "multiple choice/essay" | Bỏ "essay" | TopThi không chấm tự luận |
| Challenge #1, Key Features | "Anti-cheat / Chống gian lận" | Hạ xuống thành một ý nhỏ: "Thời gian làm bài xác thực phía server" | Không có giám sát (proctoring). Ghi "anti-cheat" dễ bị hiểu là có camera hoặc khóa trình duyệt |
| Feature Map → Phân tích | "Knowledge Graph (experimental)" | Đổi thành "Đồ thị kỹ năng tiên quyết" (không ghi experimental) | Tính năng đã chạy trên production |
| Menu Sản phẩm & công nghệ | "AI Learning Engine", "Knowledge Graph Engine" nằm trong "Đang phát triển" | Chuyển sang "Đang vận hành thử nghiệm tại TopThi" | Đã chạy trên production từ 2026-09-30 |
| Tech Stack | Chỉ ghi "Laravel" | Thay bằng `tech_stack_groups` ở mục 2 | Thiếu nhiều thành phần |
| Toàn trang | Chữ "quiz", "psychology", "check_circle", "shield", "bolt", "monitoring" có thể đang hiện thành chữ | Kiểm tra font Material Symbols đã tải chưa | Khi đọc trang bằng công cụ, tên icon hiện ra như chữ thường |
| Toàn trang | Câu tiếng Anh nằm lẫn trong khối tiếng Việt | Locale `vi` chỉ dùng tiếng Việt | |

---

## 2. Nội dung mới theo trường CMS (locale `vi`)

> Tên icon dùng Material Symbols, giống trang hiện tại. Nếu CMS dùng bộ icon khác thì đổi sang icon tương ứng.

### Cơ bản

- `title`: TopThi — Vòng học tập thích ứng cho luyện thi THPT và ĐGNL
- `excerpt`: TopThi biến mỗi lần làm bài thành dữ liệu chẩn đoán, rồi dựa trên khoa học học tập để quyết định học sinh nên luyện gì tiếp theo. Đây là living lab của XO Edu Lab, đang vận hành thật tại topthi.vn.
- `featured_image`: chờ ảnh thật (mục 5)

### Hero

- `hero_eyebrow`: EdTech · Adaptive Learning · Living Lab
- `hero_cta_label` / `hero_cta_url`: Xem TopThi đang chạy / https://topthi.vn
- `hero_badges`:
  ```json
  [
    {"icon": "psychology", "label": "Adaptive Learning"},
    {"icon": "monitoring", "label": "Learning Analytics"},
    {"icon": "quiz", "label": "Exam & Question Bank Engine"},
    {"icon": "science", "label": "Thiết kế dựa trên khoa học học tập"}
  ]
  ```
- `hero_stats`: **để trống** cho tới khi có số đã xác nhận (mục 4). Cấu trúc dự kiến:
  ```json
  [
    {"value": "<chờ>", "label": "lượt làm bài đã phân tích"},
    {"value": "<chờ>", "label": "học sinh có hồ sơ năng lực theo kỹ năng"},
    {"value": "<chờ>", "label": "kỹ năng trong bản đồ kiến thức"}
  ]
  ```

### Project Snapshot

- `snapshot_items`:
  ```json
  [
    {"icon": "school", "label": "Lĩnh vực", "value": "EdTech · Luyện thi THPT và Đánh giá năng lực"},
    {"icon": "layers", "label": "Loại", "value": "Nền tảng web (API + Admin + Front cho học sinh)"},
    {"icon": "engineering", "label": "Vai trò XO", "value": "Toàn bộ vòng đời: kiến trúc, backend, admin, frontend, mô hình học tập"},
    {"icon": "code", "label": "Công nghệ", "value": "Laravel, Nuxt 3, Vue 3, MySQL, Redis, Elasticsearch"},
    {"icon": "check_circle", "label": "Trạng thái", "value": "Đang vận hành · phiên bản 4 (từ 09/2026)"}
  ]
  ```

### The Challenge

- `challenges_heading`: Bài toán
- `challenges_description`: Luyện đề nhiều chưa chắc đã tiến bộ. Muốn cá nhân hóa thật, hệ thống phải biết học sinh yếu ở kỹ năng nào, cho luyện đúng chỗ, và chứng minh được điểm tăng là thật.
- `challenges`:
  ```json
  [
    {"icon": "query_stats", "color": "primary", "title": "Điểm tổng không nói học sinh yếu ở đâu", "description": "Một con số 6,5 điểm không cho học sinh biết phải luyện gì tiếp theo. Cần đo năng lực theo từng kỹ năng.", "wide": false},
    {"icon": "repeat", "color": "secondary", "title": "Luyện ngẫu nhiên tạo cảm giác tiến bộ", "description": "Làm lại đề quen cho điểm luyện tăng đều nhưng dễ thành học tủ. Tiến bộ phải được đo trên câu hỏi mới.", "wide": false},
    {"icon": "fact_check", "color": "gold", "title": "Kho câu hỏi lớn nhưng chưa có cấu trúc", "description": "Hàng trăm nghìn câu chưa gắn kỹ năng, nhãn độ khó lệch, có câu sai đáp án. Không có dữ liệu sạch thì không cá nhân hóa được.", "wide": false},
    {"icon": "speed", "color": "primary", "title": "Phân tích không được làm chậm việc nộp bài", "description": "Mọi tính toán năng lực chạy nền qua hàng đợi. Học sinh nhận kết quả ngay, hồ sơ năng lực cập nhật ngay sau đó.", "wide": true}
  ]
  ```

### Product Journey (thay hành trình 4 bước hiện tại)

- `journey_heading`: Vòng học tập TopThi
- `journey_steps`:
  ```json
  [
    {"title": "1. Làm bài", "description": "Học sinh làm đề thi thật hoặc đề luyện 15 câu mỗi ngày. Mỗi câu trả lời được ghi thành một sự kiện học tập."},
    {"title": "2. Chẩn đoán", "description": "Hệ thống ước lượng năng lực theo từng kỹ năng bằng mô hình Elo/IRT, có hiệu chỉnh đoán mò. Độ khó câu hỏi được hiệu chỉnh hằng đêm từ dữ liệu làm bài."},
    {"title": "3. Luyện đúng chỗ", "description": "Đề luyện ưu tiên kỹ năng có trọng số cao trong đề thi mà học sinh còn yếu. Mỗi đề trộn 3–4 chủ đề, độ khó chọn để học sinh làm đúng khoảng 70%."},
    {"title": "4. Ôn đúng lúc, sửa đúng lỗi", "description": "Lịch ôn giãn cách theo từng kỹ năng. Khi chọn sai, học sinh thấy ngay lỗi tư duy của phương án mình vừa chọn."},
    {"title": "5. Kiểm chứng tiến bộ thật", "description": "Mỗi 7 ngày có một bài đánh giá bằng câu học sinh chưa từng gặp. Kết quả quay lại bước Chẩn đoán, và đề ngày mai được tính lại từ đó."}
  ]
  ```
- Đề xuất hiển thị: vẽ 5 bước thành vòng tròn, có mũi tên từ bước 5 quay về bước 2. Nếu component chỉ hỗ trợ danh sách thẳng thì thêm một dòng chú thích dưới bước 5.

### Feature Map (nhóm theo các engine Edulab đang giới thiệu)

- `feature_map_heading`: Bản đồ chức năng
- `feature_groups`:
  ```json
  [
    {"title": "Question Bank Engine", "badge_label": "Đang vận hành", "features": ["Bản đồ kỹ năng theo Môn · Lớp, bám chương trình GDPT 2018 và các kỳ thi ĐGNL", "Gắn nhãn kỹ năng bằng AI, có người duyệt", "Hiệu chỉnh độ khó từ dữ liệu làm bài thật", "Tự phát hiện câu nghi sai đáp án, có kênh học sinh báo lỗi và AI kiểm tra lại", "Câu nghi lỗi tự động bị loại khỏi đề tự sinh"]},
    {"title": "Exam Engine", "badge_label": "Đang vận hành", "features": ["Đề cố định và đề sinh tự động theo từng học sinh", "Đủ dạng câu GDPT 2018: trắc nghiệm, đúng/sai nhiều ý, trả lời ngắn, đọc hiểu theo chùm", "Chấm tự động ngay khi nộp", "Thời gian làm bài xác thực phía server, lưu snapshot đáp án"]},
    {"title": "Adaptive Learning Engine", "badge_label": "Đang vận hành", "features": ["Mô hình năng lực Elo/IRT theo từng học sinh và từng kỹ năng", "Test đầu vào phân bổ câu theo trọng số đề thi", "Đề luyện hằng ngày xen kẽ chủ đề, độ khó vừa sức", "Đồ thị kỹ năng tiên quyết: chuyển sang luyện kiến thức nền khi nền còn yếu", "Lịch ôn giãn cách SM-2 tự chốt từ kết quả làm bài"]},
    {"title": "Learning Analytics", "badge_label": "Đang vận hành", "features": ["Điểm dự kiến có khoảng tin cậy và 3 chủ đề đáng đầu tư nhất", "Hồ sơ năng lực theo kỹ năng cho học sinh và cho admin", "Báo cáo hiệu quả học tập: đánh giá định kỳ trước/sau, độ lệch của điểm dự kiến", "Retention D1/D7/D30, nguồn vào đề thi"]}
  ]
  ```

### Solution Modules (bảng `project_solution_modules`, 3 dòng)

Mỗi module cần một ảnh chụp màn hình thật (mục 5).

1. **Luyện tập hằng ngày**
   - `title`: Đề luyện cá nhân mỗi ngày
   - `description`: Mỗi lần học sinh mở trang Luyện tập, hệ thống tính lại đề hôm nay từ toàn bộ lịch sử làm bài. Không có lịch 30 ngày soạn sẵn.
   - `technical_note`: Ưu tiên = trọng số kỹ năng trong đề thi × (1 − mức thành thạo). Độ khó câu đặt cho xác suất đúng khoảng 70%. Không lặp lại câu đã làm trong 14 ngày.
   - `features`: `["15 câu, 3–4 chủ đề xen kẽ", "Điểm dự kiến theo môn", "Ưu tiên kỹ năng đến hạn ôn"]`
2. **Phản hồi theo lỗi tư duy**
   - `title`: Xem lại bài: biết vì sao sai
   - `description`: Mỗi phương án sai gắn với một lỗi tư duy điển hình. Học sinh chọn sai sẽ thấy giải thích ngay dưới phương án mình vừa chọn.
   - `technical_note`: Giải thích do AI viết chỉ được đăng khi AI tự giải ra đúng đáp án trong cơ sở dữ liệu. Admin duyệt và gỡ được. Học sinh đánh giá 👍/👎 cho từng giải thích.
   - `features`: `["Ghi chú lỗi tư duy theo phương án", "Báo lỗi câu hỏi", "Giải thích có kiểm chứng"]`
3. **Hồ sơ năng lực & kiểm chứng**
   - `title`: Đo tiến bộ trên câu hỏi mới
   - `description`: Bài đánh giá định kỳ lấy từ một ngân hàng câu riêng và loại mọi câu học sinh đã gặp, nên điểm tăng không phải do quen đề.
   - `technical_note`: Ngân hàng câu luyện tập và ngân hàng câu đánh giá được tách riêng. Hệ thống có sẵn báo cáo liều–đáp ứng (số đề luyện đã làm so với mức tăng điểm đánh giá) và chế độ A/B tùy chọn.
   - `features`: `["Đánh giá 7 ngày/lần", "Năng lực theo từng kỹ năng", "Báo cáo hiệu quả cho admin"]`

### Technical Architecture

- `architecture_heading`: Kiến trúc hệ thống
- `architecture_layers`:
  ```json
  [
    {"icon": "dns", "title": "Backend API", "subtitle": "Laravel, REST API cho Admin và Front"},
    {"icon": "web", "title": "Front học sinh", "subtitle": "Nuxt 3 SSR"},
    {"icon": "admin_panel_settings", "title": "Admin", "subtitle": "Vue 3 + TypeScript: quản lý nội dung, gắn nhãn, hàng đợi chất lượng câu hỏi"},
    {"icon": "database", "title": "Dữ liệu", "subtitle": "MySQL, Redis, Elasticsearch có bộ phân tích tiếng Việt"},
    {"icon": "psychology", "title": "Learning Engine", "subtitle": "Hàng đợi chạy nền: nhật ký sự kiện học tập chỉ ghi thêm, mô hình năng lực, hiệu chỉnh hằng đêm"},
    {"icon": "smart_toy", "title": "AI", "subtitle": "OpenAI API dùng để gắn nhãn kỹ năng, kiểm tra đáp án và viết giải thích"}
  ]
  ```

### Technology Stack

- `tech_stack_groups`:
  ```json
  [
    {"title": "Backend", "items": ["Laravel", "PHP 8", "Laravel Queue", "Sanctum"]},
    {"title": "Frontend", "items": ["Nuxt 3 (SSR)", "Vue 3", "Pinia", "TailwindCSS"]},
    {"title": "Admin", "items": ["Vue 3", "TypeScript", "TailwindCSS"]},
    {"title": "Dữ liệu & hạ tầng", "items": ["MySQL", "Redis", "Elasticsearch", "Supervisor", "PM2"]},
    {"title": "Tích hợp", "items": ["OpenAI API", "PayOS", "Google Login"]}
  ]
  ```

### Results & Impact

- `results_heading`: Đã đo được và đang kiểm chứng
- `results`: **để trống** cho tới khi có số đã xác nhận (mục 4). Khi điền, chia thành 2 nhóm:
  - *Đã đo được*: quy mô dữ liệu, độ phủ nhãn kỹ năng, số câu lỗi đã phát hiện và sửa.
  - *Đang kiểm chứng*: mức tăng điểm đánh giá định kỳ, độ lệch của điểm dự kiến. Chỉ đăng khi có đủ cỡ mẫu.

### Lessons Learned (thay câu quote hiện tại)

- `lessons_quote`: Mô hình đầu tiên của chúng tôi cộng điểm thành thạo theo số câu đã làm, nên học sinh đoán bừa vẫn được xếp loại "khá". Khi chuyển sang mô hình Elo/IRT, nhiều chủ đề trước đó bị đánh giá quá cao. Bài học: không có bản đồ kỹ năng và dữ liệu sạch thì AI không cá nhân hóa được gì, và phần lớn công sức nằm ở đó.
- `lessons_citation`: — Đội ngũ XO Edu Lab

### SEO

- `meta_title`: TopThi — Case study vòng học tập thích ứng dựa trên khoa học học tập | XO Edu Lab
- `meta_description`: TopThi đo năng lực học sinh theo từng kỹ năng, sinh đề luyện cá nhân mỗi ngày, ôn giãn cách và kiểm chứng tiến bộ bằng đánh giá độc lập. Đây là living lab của XO Edu Lab.
- `og_image`: chờ ảnh thật

---

## 3. Section mới: "Khoa học phía sau"

CMS hiện chưa có trường cho section này. Đề xuất thêm trường JSON `science_cards` vào `project_translations`. Hiển thị dạng lưới 6 thẻ, đặt ngay sau "Vòng học tập TopThi".

- `science_heading`: Khoa học phía sau
- `science_description`: Mỗi quyết định thiết kế của TopThi dựa trên một cơ chế học tập đã được nghiên cứu kiểm chứng. Nguồn chính: Make It Stick (Brown, Roediger, McDaniel, 2014) và How People Learn I & II (National Academies, 2000, 2018).
- `science_cards`:
  ```json
  [
    {"icon": "quiz", "title": "Hiệu ứng kiểm tra", "source": "Roediger & Karpicke, 2006", "idea": "Tự nhớ lại giúp nhớ lâu hơn đọc lại. Trả lời sai rồi được phản hồi cũng là một lần học.", "in_topthi": "Học sinh luyện bằng đề ngắn mỗi ngày, không đọc lại tài liệu. Mỗi câu sai trở thành dữ liệu chẩn đoán."},
    {"icon": "event_repeat", "title": "Luyện giãn cách", "source": "Cepeda et al., 2006", "idea": "Ôn cách quãng, vào lúc sắp quên, giúp nhớ bền hơn học dồn.", "in_topthi": "Lịch ôn SM-2 riêng cho từng kỹ năng của từng học sinh."},
    {"icon": "shuffle", "title": "Luyện xen kẽ", "source": "Rohrer & Taylor, 2007", "idea": "Trộn nhiều dạng bài buộc người học nhận diện cần dùng cách giải nào, giống đề thi thật.", "in_topthi": "Mỗi đề luyện trộn 3–4 chủ đề, mỗi chủ đề ít nhất 2 câu."},
    {"icon": "trending_up", "title": "Khó khăn mong muốn & vùng phát triển gần", "source": "Bjork; Vygotsky", "idea": "Bài phải đủ khó để người học cố gắng, nhưng vẫn vượt qua được.", "in_topthi": "Độ khó câu chọn để học sinh làm đúng khoảng 70%. Khi kiến thức nền còn yếu, hệ thống chuyển sang luyện kỹ năng tiên quyết."},
    {"icon": "feedback", "title": "Đánh giá hình thành & phản hồi", "source": "Black & Wiliam, 1998; Hattie & Timperley, 2007", "idea": "Phản hồi có tác dụng khi cụ thể, đến đúng lúc và chỉ ra bước tiếp theo.", "in_topthi": "Học sinh nhận báo cáo theo kỹ năng, thấy lỗi tư duy dưới phương án chọn sai, và biết 3 chủ đề đáng đầu tư nhất."},
    {"icon": "analytics", "title": "Đo năng lực & kiểm chứng", "source": "IRT/Rasch; Bloom; Slavin", "idea": "Năng lực và độ khó câu hỏi đặt trên cùng một thang đo. Tiến bộ phải được đo trên câu hỏi mới.", "in_topthi": "Hệ thống dùng mô hình Elo/IRT có hiệu chỉnh đoán mò, và đánh giá định kỳ bằng câu học sinh chưa từng gặp."}
  ]
  ```
- Thêm một dòng nguyên tắc dưới lưới: *"TopThi tối ưu cho tiến bộ thật, không tối ưu cho cảm giác tiến bộ. Luyện tập hiệu quả thường thấy khó hơn và chậm hơn."*

Nếu chưa kịp thêm trường mới, có thể tạm đưa 6 thẻ này vào `feature_groups` dưới dạng một nhóm riêng tên "Khoa học phía sau". Cách đó kém hơn vì sẽ mất cột nguồn trích dẫn.

---

## 4. Số liệu: chờ chủ dự án xác nhận

Không điền số nào vào `hero_stats`, `results` hay `project_metrics` khi chưa có xác nhận ở bảng này. Khi điền, ghi kèm ngày chốt số.

| Số liệu | Nguồn | Trạng thái |
|---|---|---|
| Tổng lượt làm bài đã phân tích | Đếm `take_exams` trên prod | Có số nội bộ ngày 30/9; chưa được phép công bố |
| Số câu trả lời đã phân tích | Đếm `learning_events` | Như trên |
| Số học sinh có hồ sơ năng lực | Đếm `user_skill_states` theo user | Như trên |
| Số kỹ năng trong bản đồ, % câu đã gắn nhãn | Bảng `skills`, trang tiến độ gắn nhãn trên Admin | Cần tổng hợp |
| Lượt làm bài trung bình mỗi tháng | Đếm `take_exams` 3 tháng gần nhất | Chưa đo |
| Số câu lỗi đáp án đã phát hiện và sửa | Hàng đợi Chất lượng câu hỏi | Chưa tổng hợp |
| Mức tăng điểm đánh giá định kỳ, độ lệch điểm dự kiến | Báo cáo Hiệu quả học tập | Chưa đủ dữ liệu |

**Không dùng** các câu sau: "AI giúp tăng X điểm", "hiệu ứng 2 sigma", "chống gian lận" theo nghĩa giám sát, "chấm tự luận", "Knowledge Tracing/BKT".

---

## 5. Vị trí cần ảnh

Phía TopThi sẽ chụp và gửi ảnh. Đội Edulab cần xác nhận kích thước và tỷ lệ mà template đang dùng. Kích thước ghi trong bảng chỉ là đề xuất.

**Quy tắc chung**

- Chụp trên topthi.vn (prod) bằng một tài khoản demo có dữ liệu học tập thật, không dùng tài khoản của học sinh thật.
- Che tên, email, ảnh đại diện và số điện thoại nếu còn sót.
- Desktop chụp ở độ rộng 1440 px. Mobile chụp ở 390 px.
- Xuất ảnh WebP hoặc PNG, mỗi ảnh dưới 400 KB. Ảnh nào cũng cần `alt` bằng tiếng Việt (gợi ý ở cột cuối).
- Admin nằm sau Basic Auth, nên ảnh Admin do phía TopThi chụp.

**Ảnh chụp màn hình**

| # | Trường CMS / vị trí trên trang | Màn hình cần chụp | Phải thấy trong ảnh | Kích thước đề xuất | `alt` gợi ý |
|---|---|---|---|---|---|
| 1 | `featured_image` (thẻ dự án ở trang Our Work) và ảnh hero | Front `/luyen-tap`, desktop | Thẻ điểm dự kiến, đề luyện hôm nay, 3 chủ đề đáng đầu tư | 1600×900 (16:9) | Trang Luyện tập TopThi với điểm dự kiến và đề luyện hôm nay |
| 2 | `og_image` (ảnh khi chia sẻ link) | Cắt từ ảnh #1, thêm logo TopThi và tiêu đề ngắn | Logo + "Vòng học tập thích ứng" | 1200×630 | TopThi — vòng học tập thích ứng |
| 3 | Solution Module 1 "Đề luyện cá nhân mỗi ngày" | Front `/luyen-tap`, đang làm đề luyện hằng ngày | Câu hỏi thuộc nhiều chủ đề khác nhau trong cùng một đề | 1440×900 | Đề luyện hằng ngày trộn nhiều chủ đề |
| 4 | Solution Module 2 "Xem lại bài: biết vì sao sai" | Front `/review-exam/...`, xem lại một bài có câu sai | Phương án sai đã chọn, ghi chú lỗi tư duy ngay bên dưới, nút "Báo lỗi câu hỏi", nhãn "Giải thích bởi AI" (nếu có) | 1440×900 | Ghi chú lỗi tư duy hiện ngay dưới phương án học sinh chọn sai |
| 5 | Solution Module 3 "Đo tiến bộ trên câu hỏi mới" | Front `/ho-so-nang-luc` | Năng lực theo từng kỹ năng, mức yếu/khá/vững, xu hướng điểm dự kiến | 1440×900 | Hồ sơ năng lực theo từng kỹ năng |
| 6 | Gallery — nhóm "Học sinh" | Front `/lich-on-tap` | Danh sách kỹ năng đến hạn ôn, chia tab đến hạn/sắp đến hạn | 1440×900 | Lịch ôn giãn cách theo kỹ năng |
| 7 | Gallery — nhóm "Học sinh" | Front, màn làm bài dạng đúng/sai nhiều ý hoặc trả lời ngắn | Dạng câu GDPT 2018 | 1440×900 | Câu hỏi đúng/sai nhiều ý theo GDPT 2018 |
| 8 | Gallery — nhóm "Mobile" | Front `/luyen-tap` và một màn làm bài, mobile | Thanh điều hướng dưới, bộ lọc dạng bottom sheet | 390×844, ghép 2–3 ảnh trong khung điện thoại | TopThi trên điện thoại |
| 9 | Gallery — nhóm "Admin" | Admin `/insight/question-quality` | Hàng đợi câu nghi lỗi, cột AI đồng ý/không đồng ý, các nút xử lý | 1440×900 | Hàng đợi kiểm soát chất lượng câu hỏi |
| 10 | Gallery — nhóm "Admin" | Admin `/insight/label-questions` | Tiến độ gắn nhãn kỹ năng theo môn | 1440×900 | Gắn nhãn kỹ năng cho câu hỏi bằng AI có duyệt |
| 11 | Gallery — nhóm "Admin" | Admin `/insight/learning-outcomes` | Báo cáo hiệu quả học tập. Chỉ chụp khi đã có dữ liệu, nếu không thì bỏ ảnh này | 1440×900 | Báo cáo hiệu quả học tập |
| 12 | Gallery — nhóm "Admin" | Admin `/user_learning/:id` (tài khoản demo) | Tổng quan học tập của một học sinh, biểu đồ theo môn | 1440×900 | Tổng quan học tập của một học sinh |

**Hình vẽ (designer Edulab vẽ, không phải ảnh chụp)**

| # | Vị trí | Nội dung |
|---|---|---|
| 13 | Product Journey "Vòng học tập TopThi" | Vòng 5 bước: Làm bài → Chẩn đoán → Luyện đúng chỗ → Ôn đúng lúc → Kiểm chứng, có mũi tên từ bước 5 quay về bước 2. Mỗi bước kèm tên lý thuyết bên cạnh (xem `journey_steps` và `science_cards`) |
| 14 | Technical Architecture (tuỳ template) | Sơ đồ các tầng trong `architecture_layers`: Front/Admin → API → hàng đợi Learning Engine → MySQL/Redis/ES, OpenAI là dịch vụ ngoài |

Section "Khoa học phía sau" chỉ dùng icon, không cần ảnh.

---

## 6. Checklist trước khi chuyển `published`

- [ ] Đã gỡ các nội dung ở mục 1 (làm được ngay)
- [ ] Chủ dự án đồng ý công bố tên TopThi và các số liệu ở mục 4
- [ ] Đủ ảnh #1–#10 và #12 ở mục 5 (#11 là tuỳ chọn), đã che thông tin cá nhân; đã có hình vẽ #13
- [ ] Đã thêm trường `science_cards` (hoặc chọn phương án tạm ở mục 3)
- [ ] Đã kiểm tra icon hiển thị đúng
- [ ] Có bản `en` (sẽ gửi sau)
- [ ] Đã cập nhật menu "Sản phẩm & công nghệ" (mục 1)
