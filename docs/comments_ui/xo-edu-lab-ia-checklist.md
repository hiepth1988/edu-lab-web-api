# Checklist chỉnh sửa Menu & IA — XO Edu Lab

Website: https://edulab.xotech.space/
Tổng hợp từ: Đánh giá Information Architecture (Bản 1), Đánh giá Menu & Chức năng (Bản 2), đối chiếu trực tiếp với site đang chạy (16/09/2026).

Bản tương tác (tick trạng thái, đồng bộ cho cả team): https://claude.ai/artifact/1xpE8aEcUTEvSyvPr2mFDe

---

## Ưu tiên cao

### 1. Đổi thứ tự menu chính — đưa "Dự án" lên vị trí 2

- [ ] **Vị trí:** Header — nav chính
- **Vấn đề:** Thứ tự hiện tại là Giải pháp → Sản phẩm & Công nghệ → Dự án → Đối tượng → Insights. Khách hàng cần thấy bằng chứng (Dự án) ngay sau khi biết XO làm gì, trước khi đi sâu vào công nghệ.
- **Việc cần làm:** Đổi thành Giải pháp → Dự án → Sản phẩm & Công nghệ → Đối tượng → Insights.
- **Nguồn:** Bản 1, mục 7 — funnel "Bạn làm được gì? → Bạn đã từng làm chưa?"

### 2. Cross-link TopThi giữa "Sản phẩm & Công nghệ" và "Dự án"

- [ ] **Vị trí:** Mega menu Sản phẩm & Công nghệ → Sản phẩm đang vận hành → TopThi
- **Vấn đề:** Mục "TopThi" trong dropdown Sản phẩm & Công nghệ hiện không trỏ tới case study TopThi đã có sẵn ở trang Dự án — cùng một TopThi nhưng xuất hiện như hai thứ tách biệt.
- **Việc cần làm:** Đổi link "TopThi" trong dropdown để trỏ thẳng vào case study TopThi ở trang Dự án. Chiều ngược lại, trên trang case study đó thêm dòng "Công nghệ đứng sau: Exam Engine, Learning Analytics" trỏ về Core Engines. Cross-link cả hai chiều.
- **Nguồn:** Comment trực tiếp — anh Hiệp, 16/09/2026

### 3. Nối khái niệm Online Exam Platform ↔ Exam Engine / Question Bank Engine

- [ ] **Vị trí:** Landing `/solutions/online-exam-platform` + mega menu Sản phẩm & Công nghệ
- **Vấn đề:** "Online Exam Platform" (Giải pháp) và "Exam Engine" + "Question Bank Engine" (Sản phẩm & Công nghệ) phục vụ cùng một nhu cầu nhưng đứng ở hai menu riêng, dễ khiến khách B2B nhầm là hai lựa chọn khác nhau.
- **Việc cần làm:** Trên landing Online Exam Platform, thêm đoạn ngắn: "Chúng tôi xây Online Exam Platform bằng cách kết hợp Exam Engine + Question Bank Engine + giao diện tùy chỉnh theo yêu cầu", kèm link chéo sang hai trang Core Engines tương ứng.
- **Nguồn:** Bản 2, mục A + mục 3 (Giai đoạn 2)

### 4. Gộp hai khung năng lực đang trùng nhau trên trang chủ

- [ ] **Vị trí:** Trang chủ — khối mở đầu và khối "Capabilities"
- **Vấn đề:** Khối mở đầu (Nền tảng chiến lược / Kiến trúc học tập / Hệ sinh thái công nghệ) và khối Capabilities (Chiến lược / Thiết kế học tập / Thiết kế trải nghiệm / Công nghệ & Dữ liệu) diễn đạt gần như cùng một ý, cách nhau vài trăm pixel trên cùng một trang.
- **Việc cần làm:** Giữ khung 4 mục "Capabilities" (cụ thể, hành động hơn). Viết lại khối mở đầu theo hướng nêu pain point của khách hàng, không liệt kê lại pillar.
- **Nguồn:** Bản 1, mục 9 — xác nhận qua kiểm tra thực tế trang chủ

---

## Ưu tiên trung bình

### 5. Chốt vị trí chính thức cho "Research"

- [ ] **Vị trí:** Trang chủ — section Research Lab, không có trong nav chính
- **Vấn đề:** Research không còn là menu riêng và cũng chưa được gộp rõ ràng vào Insights — hiện là một section "mồ côi" trên trang chủ, không có đường dẫn chính thức trong navigation.
- **Việc cần làm:** Chọn một trong ba hướng: (a) thêm lại "Research" làm menu riêng ngang Insights, (b) gộp Research làm 1 category/filter trong menu Insights, (c) giữ làm section riêng trên trang chủ nhưng đảm bảo có trang đích đầy đủ nội dung.
- **Nguồn:** Bản 1 mục 6 (giữ riêng) khác Bản 2 mục 2.4 (gộp vào Tài nguyên) — cần XO chốt hướng đi

### 6. QA đường dẫn "Khám phá Research →"

- [ ] **Vị trí:** Trang chủ — section Research Lab
- **Vấn đề:** Chưa xác minh nút này trỏ tới đâu — có khả năng là trang rỗng hoặc chưa build xong.
- **Việc cần làm:** Kiểm tra route đích, đảm bảo có nội dung thật trước khi để link này hiển thị công khai.
- **Nguồn:** Phát hiện khi kiểm tra thực tế site, 16/09/2026

---

## Ưu tiên thấp

### 7. Rà nội dung 5 landing page dưới menu "Đối tượng"

- [ ] **Vị trí:** 5 trang: Giáo viên độc lập, Trung tâm, Trường học, EdTech Startup, Doanh nghiệp
- **Vấn đề:** Các trang này dễ lặp lại cùng một danh sách tính năng kỹ thuật thay vì tập trung vào nỗi đau riêng của từng nhóm khách hàng.
- **Việc cần làm:** Mỗi trang mở đầu bằng pain point riêng của nhóm đó; phần tính năng kỹ thuật dùng chung chỉ nên xuất hiện một lần, ở cuối hoặc dẫn sang trang Giải pháp liên quan.
- **Nguồn:** Bản 2, mục B

### 8. Giảm thuật ngữ kỹ thuật ở tầng điều hướng cấp cao

- [ ] **Vị trí:** Mega menu Giải pháp, Sản phẩm & Công nghệ; các trang landing cấp 1
- **Vấn đề:** Từ "Engine / Module / Core" xuất hiện nhiều ở cấp menu và landing chính, tạo cảm giác "xưởng kỹ thuật" hơn là truyền tải giá trị đầu ra cho khách hàng không rành kỹ thuật.
- **Việc cần làm:** Ở cấp menu/landing chính, ưu tiên diễn đạt theo giá trị (chống gian lận, tiết kiệm X% thời gian vận hành...); giữ từ Engine/Module cho phần chi tiết kỹ thuật sâu hơn trong từng trang.
- **Nguồn:** Bản 2, mục 3 — Giai đoạn 3 (chuẩn hóa thuật ngữ)
