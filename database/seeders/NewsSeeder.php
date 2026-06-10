<?php

namespace Database\Seeders;

use App\Models\NewsArticle;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'name'        => 'iPhone 16 Pro Max - Đánh giá chi tiết sau 1 tháng sử dụng',
                'description' => 'Sau hơn 1 tháng sử dụng iPhone 16 Pro Max, chúng tôi có những nhận xét thực tế nhất về hiệu năng, camera và thời lượng pin.',
                'content'     => 'iPhone 16 Pro Max trang bị chip A18 Pro được sản xuất trên tiến trình 3nm thế hệ 2, mang lại hiệu năng vượt trội so với thế hệ trước. Camera chính 48MP với khẩu độ f/1.78 cho ảnh chụp ban đêm cực kỳ ấn tượng. Màn hình ProMotion 120Hz LTPO với độ sáng đỉnh 2000 nits hiển thị sắc nét trong mọi điều kiện ánh sáng. Pin 4685mAh dùng thoải mái cả ngày dài.',
                'hot'         => 1,
            ],
            [
                'name'        => 'Top 5 Laptop gaming đáng mua nhất năm 2024',
                'description' => 'Tổng hợp 5 laptop gaming hiệu năng cao, tản nhiệt tốt và mức giá hợp lý nhất trong năm 2024.',
                'content'     => 'Thị trường laptop gaming 2024 bùng nổ với nhiều lựa chọn hấp dẫn. Asus ROG Strix G16 nổi bật với RTX 4070 và màn hình 165Hz. Dell Alienware m16 mang lại trải nghiệm premium với thiết kế Cherry MX keyboard. MSI Titan GT77 là lựa chọn cho game thủ không ngại chi tiền với RTX 4090. Lenovo Legion Pro 7i cân bằng tốt giữa hiệu năng và giá tiền. Acer Predator Helios 16 là lựa chọn tầm trung đáng cân nhắc.',
                'hot'         => 1,
            ],
            [
                'name'        => 'Samsung Galaxy S24 Ultra vs iPhone 16 Pro Max: Đâu là vua flagship?',
                'description' => 'So sánh toàn diện hai flagship đình đám nhất năm 2024 từ Samsung và Apple để giúp bạn đưa ra lựa chọn phù hợp.',
                'content'     => 'Cả Samsung Galaxy S24 Ultra và iPhone 16 Pro Max đều là những flagship đỉnh cao của năm 2024. S24 Ultra vượt trội về zoom quang học 10x và bút S-Pen tích hợp. iPhone 16 Pro Max mạnh hơn về video 4K120fps ProRes và hệ sinh thái Apple. Về camera tổng thể, hai máy không có quá nhiều khác biệt. Giá bán S24 Ultra thấp hơn khoảng 5 triệu đồng so với iPhone 16 Pro Max tại Việt Nam.',
                'hot'         => 1,
            ],
            [
                'name'        => 'MacBook Air M3 - Chiếc laptop mỏng nhẹ hoàn hảo cho sinh viên',
                'description' => 'MacBook Air M3 tiếp tục giữ vững danh hiệu laptop mỏng nhẹ tốt nhất với hiệu năng chip M3 đột phá và pin trâu bền.',
                'content'     => 'MacBook Air M3 13 inch nặng chỉ 1.24kg, mỏng 11.3mm nhưng trang bị chip Apple M3 8 nhân CPU và 10 nhân GPU. Hiệu năng tăng 60% so với M1 trong các tác vụ đa nhân. Pin 52.6Wh cho thời lượng lên đến 18 giờ phát video. Màn hình Liquid Retina 13.6 inch độ sáng 500 nits sắc nét. Đây là lựa chọn lý tưởng cho sinh viên, dân văn phòng cần máy nhẹ hiệu năng cao.',
                'hot'         => 0,
            ],
            [
                'name'        => 'Hướng dẫn chọn mua Tivi 4K phù hợp với không gian phòng khách',
                'description' => 'Những tiêu chí quan trọng cần cân nhắc khi chọn mua Tivi 4K: kích thước, công nghệ tấm nền, hệ điều hành và ngân sách.',
                'content'     => 'Chọn kích thước tivi phụ thuộc vào diện tích phòng: phòng dưới 20m² nên chọn 43-50 inch, từ 20-30m² chọn 55-65 inch, trên 30m² có thể chọn 75 inch trở lên. Công nghệ tấm nền OLED cho màu đen tuyệt đối, độ tương phản vô hạn nhưng giá cao. QLED cho độ sáng cao hơn, phù hợp phòng nhiều ánh sáng. Về hệ điều hành, Google TV và Tizen OS đều có kho ứng dụng phong phú. Ngân sách từ 10-20 triệu có thể chọn QLED 4K 55 inch chất lượng tốt.',
                'hot'         => 0,
            ],
            [
                'name'        => 'Tai nghe chống ồn Sony WH-1000XM5 - Vua của phân khúc premium',
                'description' => 'Sony WH-1000XM5 tiếp tục khẳng định vị thế số 1 trong phân khúc tai nghe chống ồn cao cấp với nhiều cải tiến đáng giá.',
                'content'     => 'Sony WH-1000XM5 sử dụng 8 micro và 2 chip xử lý âm thanh QN1e + HD Noise Cancelling Processor QN1 để loại bỏ tiếng ồn hiệu quả hơn 40% so với XM4. Thời lượng pin 30 giờ với ANC bật, sạc nhanh 3 phút cho 3 giờ nghe. Thiết kế mới hoàn toàn không có bản lề gập, gọn nhẹ hơn. Âm thanh LDAC 360 Reality Audio mang lại trải nghiệm âm nhạc như nghe live.',
                'hot'         => 0,
            ],
        ];

        foreach ($articles as $article) {
            NewsArticle::firstOrCreate(['name' => $article['name']], $article);
        }
    }
}
