# Đánh giá & Tối ưu Cấu trúc Menu / Chức năng - XO Edu Lab (edulab.xotech.space)

---

## 1. Phân tích Hiện trạng & Các điểm bất cập

Trang web `edulab.xotech.space` hiện đang gặp phải tình trạng **phân mảnh thông tin (Information Fragmentation)** và **chồng chéo khái niệm (Cognitive Overlap)**. Người dùng truy cập (đặc biệt là khách hàng B2B, chủ trung tâm, nhà giáo dục) rất dễ bị rơi vào trạng thái "ngợp" vì cùng một năng lực lõi/sản phẩm nhưng được chia nhỏ và gọi bằng nhiều tên khác nhau ở các vị trí khác nhau.

### A. Sự chồng chéo giữa Mega Menu "Giải pháp" và "Sản phẩm"
* **Hiện trạng trên Web:**
  * **Giải pháp (Solutions):** Chia làm *Xây dựng* (LMS, Online Exam Platform), *Nâng cấp* (AI Solutions, Learning Analytics, Adaptive Learning), *Vận hành* (School Management, Education Technology Consulting).
  * **Sản phẩm (Products):** Chia làm *Sản phẩm đang vận hành* (TopThi), *Core Engines* (Exam Engine, Question Bank Engine, Learning Analytics Platform), *Đang phát triển* (AI Learning Engine, Knowledge Graph Engine).
* **Bất cập:**
  * *"Online Exam Platform"* (trong Solutions) và *"Exam Engine"* / *"Question Bank Engine"* (trong Products) thực chất phục vụ cùng một nhóm nhu cầu.
  * *"Learning Analytics"* (Solutions) và *"Learning Analytics Platform"* (Products) hoàn toàn lặp lại về mặt ý nghĩa.
  * Khách hàng muốn tìm giải pháp tổng thể sẽ bị bối rối: Họ phải tự ghép nối trong đầu xem "Engine" khác gì "Platform" và đâu là thứ họ cần đặt mua/gia công.

### B. Menu "Đối tượng" (Who We Help) bị lặp lại hình thức
* Menu Header liệt kê 5 nhóm: *Chuyên gia & Giáo viên độc lập*, *Trung tâm đào tạo*, *Trường học*, *EdTech Startup*, *Đào tạo nội bộ doanh nghiệp*.
* Tuy nhiên, cấu trúc nội dung chưa phân tách rõ ràng giữa **bài toán/nỗi đau riêng (Pain Points)** của từng nhóm với **tính năng kỹ thuật**, khiến thông tin ở các trang này mang tính trùng lặp cao.

### C. Phân tán tri thức: "Nghiên cứu" (Research) vs "Insights"
* **Nghiên cứu (Research):** Knowledge Graph, Brain-based Learning, Student Behavior,...
* **Insights:** Các bài viết chuyên môn (Kiến trúc Online Exam Platform chống gian lận, So sánh LMS riêng vs Moodle,...).
* **Bất cập:** Ranh giới giữa *Research* và *Insights/Blog* rất mỏng. Việc tách thành 2 mục riêng trên Navigation Bar chính làm lãng phí vị trí "vàng" của Navigation Bar và phân tán sự chú ý của người đọc.

---

## 2. Gợi ý Mô hình Kiến trúc Thông tin (Information Architecture) Tối ưu

Để tối ưu trải nghiệm người dùng (UX), định hình rõ vị thế **EdTech Agency / Partner chuyên nghiệp**, menu nên gộp và thu gọn về **4 - 5 mục chính** trên Navigation Bar:

```text
[ Logo ]  Giải pháp & Dịch vụ  │  Đối tượng  │  Dự án (Our Work)  │  Tài nguyên  │  [ Nút CTA: Đặt lịch tư vấn ]
```

### Chi tiết cấu trúc Megamenu đề xuất:

#### 1. Giải pháp & Dịch vụ (Gộp Solutions + Products + Engines)
Thay vì phân chia theo thuật ngữ kỹ thuật (*Engine* vs *Platform*), hãy gom nhóm theo **Nhu cầu thực tế của khách hàng**:
* **Nền tảng Học & Thi số:**
  * Hệ thống quản lý học tập (LMS)
  * Nền tảng Thi trực tuyến & Ngân hàng đề (Exam & Question Bank Engine)
  * Quản lý Trường học & Trung tâm đào tạo
* **Tích hợp Công nghệ AI & Dữ liệu:**
  * Học tập cá nhân hóa & AI Tutor (Adaptive Learning & AI Engine)
  * Phân tích dữ liệu học tập (Learning Analytics)
  * Biểu đồ tri thức (Knowledge Graph)
* **Sản phẩm tiêu biểu:** TopThi (Trường hợp thực tế / Case study tiêu biểu)

#### 2. Khách hàng (Who We Help)
Giữ nguyên 5 nhóm nhưng đóng gói rõ theo bài toán thực tế:
* Giáo viên & Chuyên gia độc lập
* Trung tâm đào tạo
* Trường học & Viện giáo dục
* EdTech Startup
* Đào tạo nội bộ (Enterprise Learning)

#### 3. Dự án & Năng lực (Our Work)
* Showcase các dự án thực tế (TopThi, các hệ thống LMS/Exam custom cho đối tác).

#### 4. Tài nguyên (Resources - Gộp Research & Insights)
* **Insights / Blog:** Bài viết phân tích xu hướng, so sánh công nghệ, tư vấn giải pháp.
* **Research Lab:** Đóng đóng góp tri thức sâu về AI, Brain-based learning, Knowledge Graph.

---

## 3. Lộ trình Đề xuất Điều chỉnh

1. **Giai đoạn 1 (Thu gọn Header ngay lập tức):**
   * Đổi Menu chính thành: **Giải pháp** | **Đối tượng** | **Dự án** | **Tài nguyên** | **Về XO**.
   * Đưa nút Tìm kiếm (Search) và CTA "Đặt lịch tư vấn" / "Xây dựng cùng XO" về vị trí nổi bật bên phải.
2. **Giai đoạn 2 (Chuẩn hóa Landing Page cấp sản phẩm):**
   * Với mảng *Thi trực tuyến*, gộp trang *Exam Platform*, *Question Bank Engine*, và *Exam Engine* vào chung 1 trang tổng thể. Trong trang đó giải thích rõ cấu trúc: *"Chúng tôi cung cấp Core Engine thi + Giao diện tùy chỉnh theo yêu cầu"*.
3. **Giai đoạn 3 (Chuẩn hóa Thuật ngữ - Tone & Mood):**
   * Giảm tần suất dùng từ khóa mang tính "xưởng kỹ thuật" (Engine, Module, Core) ở các trang điều hướng cấp cao. Focus vào **Giá trị đầu ra** (Chống gian lận, Ra đề tự động, Tiết kiệm 70% thời gian vận hành,...), chỉ đưa thông số Engine sâu hơn ở phần chi tiết kỹ thuật bên trong.
