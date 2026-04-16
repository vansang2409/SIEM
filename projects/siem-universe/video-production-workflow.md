# Video Production Workflow

Workflow này giúp biến một tập truyện SIEM thành video ngắn bằng AI theo quy trình rõ ràng, dễ lặp và giữ nhân vật ổn định.

---

## Mục tiêu
Từ một kịch bản ngắn, tạo ra:
- ảnh scene nhất quán
- voice phù hợp
- video ngắn 30-60 giây
- phụ đề dễ đọc

---

## Bước 1 - Chọn tập và chia scene
Lấy một tập từ file kịch bản, sau đó chia thành các scene nhỏ.

### Ví dụ với Tập 01
- Scene 1: SIEM nhìn thấy chiếc bánh
- Scene 2: Các bạn nhìn chiếc bánh
- Scene 3: SIEM bối rối
- Scene 4: SIEM chia bánh
- Scene 5: Cả nhóm cùng ăn
- Scene 6: Cảnh kết + bài học

### Mục tiêu
Mỗi scene nên có:
- 1 hành động chính
- 1 cảm xúc rõ
- 1 khung hình dễ mô tả

---

## Bước 2 - Viết prompt ảnh cho từng scene
Dùng prompt gốc của nhân vật, sau đó thêm:
- hành động
- biểu cảm
- bối cảnh

### Công thức prompt scene
[nhân vật chuẩn] + [hành động] + [biểu cảm] + [bối cảnh] + [style cố định]

### Ví dụ
SIEM is a chubby orange cat with a round face, big dark brown eyes, a small pink nose, cream white round belly, fluffy orange tail, soft plush fur, warm and gentle expression, child-friendly 3D cartoon style, soft lighting, bright warm colors, highly consistent character design, no clothes, looking excited at a small sweet cake on a wooden table in a cozy garden.

### Lưu ý
- luôn giữ mô tả nhân vật ở đầu prompt
- không đổi style giữa các scene
- scene đầu nên tạo rõ nhân vật nhất

---

## Bước 3 - Tạo ảnh nhân vật và ảnh scene
### Ưu tiên thứ tự
1. tạo ảnh chuẩn từng nhân vật
2. tạo ảnh nhóm lineup
3. tạo ảnh scene riêng lẻ
4. tạo ảnh có nhiều nhân vật hơn khi đã ổn định

### Mẹo
- nếu tool hỗ trợ image reference, dùng ảnh chuẩn của SIEM và từng bạn
- nếu bị trôi nhân vật, giảm số lượng nhân vật trong một scene
- ưu tiên góc máy đơn giản trước

---

## Bước 4 - Viết voice script ngắn
Từ lời thoại trong kịch bản, rút ra phần đọc voice ngắn gọn hơn.

### Ví dụ Tập 01
- "SIEM nhìn thấy một chiếc bánh ngọt rất thơm."
- "Cậu muốn ăn hết một mình."
- "Nhưng các bạn cũng đang đói."
- "SIEM nghĩ một lúc rồi cắt bánh ra thành nhiều phần nhỏ."
- "Cả nhóm cùng ăn và ai cũng vui vẻ."
- "Niềm vui sẽ lớn hơn khi mình biết chia sẻ."

### Mẹo
- voice nên ngắn, câu đơn giản
- tránh quá nhiều chữ trong 1 cảnh
- nếu làm cho trẻ nhỏ, giọng nên ấm, chậm vừa phải

---

## Bước 5 - Tạo voice
Có thể dùng:
- TTS AI
- voice clone
- hoặc giọng đọc nhẹ, ấm, chậm vừa

### Nên giữ ổn định
- cùng 1 giọng đọc cho cả series đầu
- tốc độ đọc không quá nhanh
- cảm xúc nhẹ nhàng, tích cực

---

## Bước 6 - Ghép video
Ghép theo thứ tự:
1. ảnh scene
2. voice
3. nhạc nền nhẹ
4. subtitle
5. hiệu ứng chuyển cảnh đơn giản

### Cấu trúc thời lượng
- Hook mở đầu: 2-3 giây
- Diễn biến: 20-35 giây
- Kết + bài học: 5-10 giây

### Không nên
- nhồi quá nhiều hiệu ứng
- quá nhiều text trên màn hình
- chuyển cảnh quá nhanh gây mệt mắt

---

## Bước 7 - Subtitle
Subtitle nên:
- ngắn
- font to
- rõ ràng
- mỗi câu không quá dài
- màu dễ đọc trên nền sáng

### Gợi ý
- 1 câu ngắn / 1 cảnh
- highlight từ khóa như: chia sẻ, xin lỗi, dũng cảm

---

## Bước 8 - Xuất bản và test
Khi đăng video, cần test:
- 3 giây đầu có giữ mắt không
- nhân vật có nhất quán không
- voice có dễ nghe không
- nhịp video có bị chậm quá không
- subtitle có dễ đọc không

---

## Quy trình tối ưu nhất cho người mới
1. Chọn 1 tập
2. Chia 5-6 scene
3. Tạo 5-6 ảnh
4. Làm 1 voice đọc ngắn
5. Ghép thành video 30-45 giây
6. Đăng test
7. Xem phản hồi rồi mới tối ưu tiếp

---

## Bộ output tối thiểu cho 1 tập
- 1 script
- 5 đến 6 prompt scene
- 5 đến 6 ảnh
- 1 file voice
- 1 video final
- 1 caption ngắn

---

## Gợi ý pipeline làm nhanh
- Script: lấy từ `scripts-episode-01-03.md`
- Prompt nền: lấy từ `prompt-library.md`
- Prompt nhóm: lấy từ `group-scene-prompts.md`
- Character consistency: xem `character-sheets.md`
- Story format: xem `story-framework.md`

---

## Lời khuyên thực tế
- đừng làm 10 tập cùng lúc ngay từ đầu
- hãy làm 1 tập thật xong
- chốt style và workflow
- sau đó mới nhân rộng

Lúc đầu, mục tiêu không phải hoàn hảo.
Mục tiêu là làm ra một quy trình có thể lặp lại.
