<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $samsung  = Category::where('name', 'Samsung')->first();
        $iphone   = Category::where('name', 'iPhone')->first();
        $xiaomi   = Category::where('name', 'Xiaomi')->first();
        $dell     = Category::where('name', 'Dell')->first();
        $asus     = Category::where('name', 'Asus')->first();
        $macbook  = Category::where('name', 'MacBook')->first();
        $tivi     = Category::where('name', 'Tivi')->first();
        $tablet   = Category::where('name', 'Máy tính bảng')->first();
        $tainghe  = Category::where('name', 'Tai nghe')->first();
        $sac      = Category::where('name', 'Sạc dự phòng')->first();

        $products = [
            // ==================== SAMSUNG ====================
            ['name' => 'Samsung Galaxy S24 Ultra',    'price' => 29990000, 'discount' => 5,  'hot' => 1, 'category_id' => $samsung->id, 'description' => 'Flagship Samsung mới nhất với camera 200MP, chip Snapdragon 8 Gen 3, bút S Pen tích hợp.'],
            ['name' => 'Samsung Galaxy S24+',         'price' => 24990000, 'discount' => 8,  'hot' => 1, 'category_id' => $samsung->id, 'description' => 'Màn hình Dynamic AMOLED 2X 6.7 inch, chip Snapdragon 8 Gen 3, pin 4900mAh.'],
            ['name' => 'Samsung Galaxy S24',          'price' => 19990000, 'discount' => 10, 'hot' => 1, 'category_id' => $samsung->id, 'description' => 'Chip Snapdragon 8 Gen 3, camera 50MP, màn hình 6.2 inch 120Hz.'],
            ['name' => 'Samsung Galaxy A55',          'price' => 10990000, 'discount' => 10, 'hot' => 1, 'category_id' => $samsung->id, 'description' => 'Tầm trung cao cấp, Super AMOLED 6.6 inch, pin 5000mAh, sạc 45W.'],
            ['name' => 'Samsung Galaxy A35',          'price' =>  8490000, 'discount' => 5,  'hot' => 0, 'category_id' => $samsung->id, 'description' => 'Super AMOLED 6.6 inch, camera 50MP OIS, pin 5000mAh sạc 25W.'],
            ['name' => 'Samsung Galaxy A15',          'price' =>  4990000, 'discount' => 0,  'hot' => 0, 'category_id' => $samsung->id, 'description' => 'Màn Super AMOLED 6.5 inch 90Hz, camera 50MP, pin 5000mAh.'],
            ['name' => 'Samsung Galaxy Z Flip 5',     'price' => 22990000, 'discount' => 8,  'hot' => 1, 'category_id' => $samsung->id, 'description' => 'Gập vỏ sò, màn ngoài Flex Window 3.4 inch, chip Snapdragon 8 Gen 2.'],
            ['name' => 'Samsung Galaxy Z Fold 5',     'price' => 41990000, 'discount' => 5,  'hot' => 0, 'category_id' => $samsung->id, 'description' => 'Gập mở rộng 7.6 inch, multitasking đỉnh cao, chip Snapdragon 8 Gen 2.'],
            ['name' => 'Samsung Galaxy M55',          'price' =>  7990000, 'discount' => 0,  'hot' => 0, 'category_id' => $samsung->id, 'description' => 'Pin khủng 6000mAh sạc 45W, màn Super AMOLED 6.7 inch 120Hz.'],
            ['name' => 'Samsung Galaxy F55',          'price' =>  6490000, 'discount' => 5,  'hot' => 0, 'category_id' => $samsung->id, 'description' => 'AMOLED 6.7 inch 120Hz, Snapdragon 7 Gen 1, camera 50MP OIS.'],

            // ==================== IPHONE ====================
            ['name' => 'iPhone 16 Pro Max',           'price' => 34990000, 'discount' => 0,  'hot' => 1, 'category_id' => $iphone->id, 'description' => 'iPhone mạnh nhất 2024, chip A18 Pro, camera 48MP Tetraprism 5x, titan.'],
            ['name' => 'iPhone 16 Pro',               'price' => 28990000, 'discount' => 0,  'hot' => 1, 'category_id' => $iphone->id, 'description' => 'Chip A18 Pro, camera 48MP, Action Button, USB-C 3.0, vỏ titan.'],
            ['name' => 'iPhone 16 Plus',              'price' => 26990000, 'discount' => 5,  'hot' => 0, 'category_id' => $iphone->id, 'description' => 'Màn hình 6.7 inch, chip A18, pin cả ngày, camera 48MP chính.'],
            ['name' => 'iPhone 16',                   'price' => 22990000, 'discount' => 5,  'hot' => 1, 'category_id' => $iphone->id, 'description' => 'Chip A18, Camera Control, USB-C, hỗ trợ Apple Intelligence.'],
            ['name' => 'iPhone 15 Pro Max',           'price' => 29990000, 'discount' => 12, 'hot' => 1, 'category_id' => $iphone->id, 'description' => 'Chip A17 Pro, camera Tetraprism 5x, khung titan, cổng USB-C 3.0.'],
            ['name' => 'iPhone 15 Pro',               'price' => 25990000, 'discount' => 10, 'hot' => 0, 'category_id' => $iphone->id, 'description' => 'Chip A17 Pro, Action Button, camera 48MP ProRAW, khung titan.'],
            ['name' => 'iPhone 15',                   'price' => 19990000, 'discount' => 15, 'hot' => 1, 'category_id' => $iphone->id, 'description' => 'Dynamic Island, USB-C, camera chính 48MP, chip A16 Bionic.'],
            ['name' => 'iPhone 14',                   'price' => 15990000, 'discount' => 20, 'hot' => 0, 'category_id' => $iphone->id, 'description' => 'Chip A15 Bionic, camera chính 12MP cải tiến, Emergency SOS satellite.'],
            ['name' => 'iPhone 13',                   'price' => 12990000, 'discount' => 18, 'hot' => 0, 'category_id' => $iphone->id, 'description' => 'Chip A15 Bionic, camera kép 12MP, màn Super Retina XDR, pin cải tiến 20%.'],
            ['name' => 'iPhone SE 3rd gen',           'price' =>  9990000, 'discount' => 10, 'hot' => 0, 'category_id' => $iphone->id, 'description' => 'iPhone nhỏ gọn chip A15 Bionic, 5G, Touch ID, giá tốt nhất dòng iPhone.'],

            // ==================== XIAOMI ====================
            ['name' => 'Xiaomi 14 Ultra',             'price' => 26990000, 'discount' => 5,  'hot' => 1, 'category_id' => $xiaomi->id, 'description' => 'Camera Leica Summilux f/1.63, Snapdragon 8 Gen 3, sạc không dây 80W.'],
            ['name' => 'Xiaomi 14',                   'price' => 18990000, 'discount' => 5,  'hot' => 1, 'category_id' => $xiaomi->id, 'description' => 'Camera Leica, Snapdragon 8 Gen 3, màn AMOLED 6.36 inch 120Hz.'],
            ['name' => 'Xiaomi 13T Pro',              'price' => 14990000, 'discount' => 10, 'hot' => 0, 'category_id' => $xiaomi->id, 'description' => 'Camera Leica, Dimensity 9200+, sạc nhanh 144W HyperCharge.'],
            ['name' => 'Redmi Note 13 Pro+',          'price' =>  9990000, 'discount' => 8,  'hot' => 1, 'category_id' => $xiaomi->id, 'description' => 'Camera 200MP OIS, Dimensity 7200 Ultra, sạc 120W, IP68.'],
            ['name' => 'Redmi Note 13 Pro',           'price' =>  7990000, 'discount' => 0,  'hot' => 1, 'category_id' => $xiaomi->id, 'description' => 'Camera 200MP, pin 5100mAh, sạc nhanh 67W, màn AMOLED 120Hz.'],
            ['name' => 'Redmi Note 13',               'price' =>  5990000, 'discount' => 5,  'hot' => 0, 'category_id' => $xiaomi->id, 'description' => 'Camera 108MP, AMOLED 6.67 inch 120Hz, pin 5000mAh sạc 33W.'],
            ['name' => 'Redmi 13C',                   'price' =>  3490000, 'discount' => 0,  'hot' => 0, 'category_id' => $xiaomi->id, 'description' => 'Giá rẻ phổ thông, camera 50MP, pin 5000mAh, Helio G85.'],
            ['name' => 'POCO X6 Pro',                 'price' =>  9490000, 'discount' => 8,  'hot' => 1, 'category_id' => $xiaomi->id, 'description' => 'Gaming phone Dimensity 8300 Ultra, màn 144Hz, sạc 67W.'],
            ['name' => 'POCO F6',                     'price' => 10990000, 'discount' => 5,  'hot' => 0, 'category_id' => $xiaomi->id, 'description' => 'Snapdragon 8s Gen 3, màn AMOLED 120Hz, sạc nhanh 90W.'],
            ['name' => 'Xiaomi Pad 6S Pro',           'price' => 15990000, 'discount' => 5,  'hot' => 0, 'category_id' => $xiaomi->id, 'description' => 'Tablet Snapdragon 8 Gen 2, màn 12.4 inch 144Hz, sạc 120W.'],

            // ==================== DELL ====================
            ['name' => 'Dell XPS 15 9530',            'price' => 42990000, 'discount' => 5,  'hot' => 1, 'category_id' => $dell->id, 'description' => 'Core i7 Gen 13, RAM 16GB, SSD 512GB, màn OLED 3.5K cảm ứng, card RTX 4060.'],
            ['name' => 'Dell XPS 13 9340',            'price' => 32990000, 'discount' => 3,  'hot' => 1, 'category_id' => $dell->id, 'description' => 'Ultra-portable Core Ultra 7, RAM 16GB, SSD 512GB, màn InfinityEdge 13.4 inch.'],
            ['name' => 'Dell Inspiron 15 3530',       'price' => 15990000, 'discount' => 0,  'hot' => 0, 'category_id' => $dell->id, 'description' => 'Core i5 Gen 13, RAM 8GB, SSD 256GB, màn FHD 15.6 inch, pin 54Wh.'],
            ['name' => 'Dell Inspiron 14 5440',       'price' => 19990000, 'discount' => 5,  'hot' => 0, 'category_id' => $dell->id, 'description' => 'Core Ultra 5, RAM 16GB, SSD 512GB, màn 2.2K 120Hz mỏng nhẹ.'],
            ['name' => 'Dell Vostro 15 3530',         'price' => 14490000, 'discount' => 0,  'hot' => 0, 'category_id' => $dell->id, 'description' => 'Laptop doanh nghiệp Core i5 Gen 13, RAM 8GB, SSD 256GB, bảo hành ProSupport.'],
            ['name' => 'Dell Alienware m18',          'price' => 75990000, 'discount' => 3,  'hot' => 0, 'category_id' => $dell->id, 'description' => 'Gaming laptop RTX 4090, Core i9 HX, màn 18 inch QHD+ 165Hz, RAM 32GB.'],
            ['name' => 'Dell G15 5530',               'price' => 27990000, 'discount' => 8,  'hot' => 1, 'category_id' => $dell->id, 'description' => 'Gaming laptop Core i7 Gen 13, RTX 4060, màn FHD 165Hz, RAM 16GB.'],
            ['name' => 'Dell Latitude 5440',          'price' => 24990000, 'discount' => 0,  'hot' => 0, 'category_id' => $dell->id, 'description' => 'Business laptop Core Ultra 5, RAM 16GB, SSD 512GB, bảo mật cao cấp.'],

            // ==================== ASUS ====================
            ['name' => 'Asus ROG Strix G16 2024',     'price' => 45990000, 'discount' => 5,  'hot' => 1, 'category_id' => $asus->id, 'description' => 'Gaming RTX 4070, Core i9, màn 16 inch QHD 240Hz, tản nhiệt Tri-Fan.'],
            ['name' => 'Asus ROG Zephyrus G14',       'price' => 39990000, 'discount' => 8,  'hot' => 1, 'category_id' => $asus->id, 'description' => 'Gaming AMD Ryzen 9, RTX 4060, màn OLED 2.8K 120Hz, pin 73Wh.'],
            ['name' => 'Asus TUF Gaming A15',         'price' => 22990000, 'discount' => 10, 'hot' => 1, 'category_id' => $asus->id, 'description' => 'Gaming Ryzen 7, RTX 4060, màn FHD 144Hz, pin 90Wh, MIL-STD 810H.'],
            ['name' => 'Asus ZenBook 14 OLED',        'price' => 24990000, 'discount' => 5,  'hot' => 0, 'category_id' => $asus->id, 'description' => 'Mỏng nhẹ màn OLED 2.8K, Ryzen 7 8845H, RAM 16GB, pin 75Wh.'],
            ['name' => 'Asus VivoBook 15 X1504',      'price' => 12990000, 'discount' => 5,  'hot' => 0, 'category_id' => $asus->id, 'description' => 'Phổ thông Core i5 Gen 12, RAM 8GB, SSD 512GB, màn FHD IPS 15.6 inch.'],
            ['name' => 'Asus ProArt Studiobook 16',   'price' => 55990000, 'discount' => 3,  'hot' => 0, 'category_id' => $asus->id, 'description' => 'Sáng tạo nội dung RTX 4070, Core i9, màn OLED 4K cảm ứng, RAM 32GB.'],
            ['name' => 'Asus ExpertBook B1400',       'price' => 16990000, 'discount' => 0,  'hot' => 0, 'category_id' => $asus->id, 'description' => 'Business Core i5 Gen 12, RAM 8GB, SSD 256GB, pin 42Wh, MIL-STD 810H.'],
            ['name' => 'Asus ROG Flow X13',           'price' => 34990000, 'discount' => 5,  'hot' => 0, 'category_id' => $asus->id, 'description' => '2-in-1 gaming AMD Ryzen 9, RTX 4060, màn cảm ứng QHD 165Hz.'],

            // ==================== MACBOOK ====================
            ['name' => 'MacBook Air M3 13 inch',      'price' => 28990000, 'discount' => 0,  'hot' => 1, 'category_id' => $macbook->id, 'description' => 'Chip M3, RAM 8GB, SSD 256GB, màn Liquid Retina 13.6 inch, pin 18h.'],
            ['name' => 'MacBook Air M3 15 inch',      'price' => 34990000, 'discount' => 0,  'hot' => 1, 'category_id' => $macbook->id, 'description' => 'Chip M3, RAM 8GB, SSD 256GB, màn 15.3 inch lớn nhất dòng Air.'],
            ['name' => 'MacBook Air M2 13 inch',      'price' => 24990000, 'discount' => 5,  'hot' => 0, 'category_id' => $macbook->id, 'description' => 'Thiết kế không quạt, chip M2, RAM 8GB, SSD 256GB, pin 18h.'],
            ['name' => 'MacBook Pro M3 14 inch',      'price' => 42990000, 'discount' => 0,  'hot' => 1, 'category_id' => $macbook->id, 'description' => 'Chip M3, RAM 8GB, SSD 512GB, màn Liquid Retina XDR 3024x1964.'],
            ['name' => 'MacBook Pro M3 Pro 14 inch',  'price' => 52990000, 'discount' => 0,  'hot' => 1, 'category_id' => $macbook->id, 'description' => 'Chip M3 Pro, RAM 18GB, SSD 512GB, màn Liquid Retina XDR, HDMI 2.1.'],
            ['name' => 'MacBook Pro M3 Pro 16 inch',  'price' => 64990000, 'discount' => 0,  'hot' => 0, 'category_id' => $macbook->id, 'description' => 'Chip M3 Pro, RAM 18GB, SSD 512GB, màn 16.2 inch XDR, pin 22h.'],
            ['name' => 'MacBook Pro M3 Max 16 inch',  'price' => 89990000, 'discount' => 0,  'hot' => 0, 'category_id' => $macbook->id, 'description' => 'Chip M3 Max, RAM 36GB, SSD 1TB, đỉnh cao hiệu năng cho chuyên gia.'],

            // ==================== TIVI ====================
            ['name' => 'Samsung Neo QLED 4K 65 inch', 'price' => 32990000, 'discount' => 10, 'hot' => 1, 'category_id' => $tivi->id, 'description' => 'Mini LED Neo QLED, Tizen OS, 4K 120Hz, Dolby Atmos, HDR 2000 nits.'],
            ['name' => 'Samsung QLED 4K 55 inch',     'price' => 18990000, 'discount' => 10, 'hot' => 1, 'category_id' => $tivi->id, 'description' => 'QLED Quantum Dot, Tizen OS, 4K HDR, loa 60W Dolby Atmos.'],
            ['name' => 'Samsung Crystal 4K 50 inch',  'price' => 10990000, 'discount' => 8,  'hot' => 0, 'category_id' => $tivi->id, 'description' => 'Crystal UHD 4K, Tizen OS, PurColor, HDR, loa 20W.'],
            ['name' => 'LG OLED C3 65 inch',          'price' => 45990000, 'discount' => 5,  'hot' => 1, 'category_id' => $tivi->id, 'description' => 'OLED evo 4K 120Hz, webOS 23, Dolby Vision IQ, Dolby Atmos, G-Sync.'],
            ['name' => 'LG OLED C3 55 inch',          'price' => 29990000, 'discount' => 8,  'hot' => 0, 'category_id' => $tivi->id, 'description' => 'OLED evo 4K 120Hz, webOS 23, 4 cổng HDMI 2.1, hỗ trợ gaming.'],
            ['name' => 'LG QNED 4K 55 inch',          'price' => 15990000, 'discount' => 5,  'hot' => 0, 'category_id' => $tivi->id, 'description' => 'QNED Mini LED, 4K 120Hz, webOS 23, Dolby Vision, ThinQ AI.'],
            ['name' => 'Sony Bravia XR 65 inch',      'price' => 38990000, 'discount' => 5,  'hot' => 0, 'category_id' => $tivi->id, 'description' => 'OLED XR 4K 120Hz, Google TV, bộ xử lý XR Cognitive, Dolby Atmos.'],
            ['name' => 'Sony Bravia 4K 50 inch',      'price' => 16990000, 'discount' => 8,  'hot' => 0, 'category_id' => $tivi->id, 'description' => 'Smart TV 4K Google TV, bộ xử lý X1, Dolby Atmos, HDMI 2.1.'],
            ['name' => 'TCL QLED 4K 55 inch',         'price' =>  9990000, 'discount' => 12, 'hot' => 0, 'category_id' => $tivi->id, 'description' => 'QLED 4K 120Hz, Google TV, Dolby Vision, Dolby Atmos, giá tốt.'],
            ['name' => 'Xiaomi TV A Pro 55 inch',     'price' =>  8490000, 'discount' => 10, 'hot' => 0, 'category_id' => $tivi->id, 'description' => '4K QLED, Google TV, 60Hz, Dolby Audio, giá hợp lý.'],

            // ==================== MÁY TÍNH BẢNG ====================
            ['name' => 'iPad Pro M4 11 inch',         'price' => 27990000, 'discount' => 0,  'hot' => 1, 'category_id' => $tablet->id, 'description' => 'Chip M4, màn OLED Ultra Retina XDR, mỏng nhất 5.1mm, hỗ trợ Apple Pencil Pro.'],
            ['name' => 'iPad Pro M4 13 inch',         'price' => 36990000, 'discount' => 0,  'hot' => 1, 'category_id' => $tablet->id, 'description' => 'Chip M4, màn OLED 13 inch, tandem OLED 1000 nits, Magic Keyboard tương thích.'],
            ['name' => 'iPad Air M2 11 inch',         'price' => 16990000, 'discount' => 0,  'hot' => 1, 'category_id' => $tablet->id, 'description' => 'Chip M2, Liquid Retina 11 inch, USB-C, hỗ trợ Apple Pencil Pro & Magic Keyboard.'],
            ['name' => 'iPad Air M2 13 inch',         'price' => 22990000, 'discount' => 0,  'hot' => 0, 'category_id' => $tablet->id, 'description' => 'iPad Air màn hình lớn 13 inch đầu tiên, chip M2, Liquid Retina.'],
            ['name' => 'iPad Mini 6th gen',           'price' => 13990000, 'discount' => 5,  'hot' => 0, 'category_id' => $tablet->id, 'description' => 'Nhỏ gọn 8.3 inch Liquid Retina, chip A15 Bionic, 5G, USB-C.'],
            ['name' => 'iPad 10th gen',               'price' =>  9990000, 'discount' => 8,  'hot' => 0, 'category_id' => $tablet->id, 'description' => 'Chip A14 Bionic, màn 10.9 inch IPS, 5G, thiết kế mới hoàn toàn.'],
            ['name' => 'Samsung Galaxy Tab S9 Ultra', 'price' => 28990000, 'discount' => 5,  'hot' => 1, 'category_id' => $tablet->id, 'description' => 'Màn Dynamic AMOLED 14.6 inch, Snapdragon 8 Gen 2, RAM 12GB, IP68.'],
            ['name' => 'Samsung Galaxy Tab S9+',      'price' => 19990000, 'discount' => 8,  'hot' => 0, 'category_id' => $tablet->id, 'description' => 'Dynamic AMOLED 12.4 inch, Snapdragon 8 Gen 2, RAM 12GB, kèm S Pen.'],
            ['name' => 'Samsung Galaxy Tab S9 FE',    'price' =>  9990000, 'discount' => 10, 'hot' => 0, 'category_id' => $tablet->id, 'description' => 'TFT 10.9 inch, Exynos 1380, RAM 6GB, pin 10090mAh, IP68.'],
            ['name' => 'Xiaomi Pad 6',                'price' =>  8990000, 'discount' => 5,  'hot' => 0, 'category_id' => $tablet->id, 'description' => 'Snapdragon 870, màn IPS 11 inch 144Hz, RAM 8GB, sạc 33W.'],

            // ==================== TAI NGHE ====================
            ['name' => 'Sony WH-1000XM5',             'price' =>  8490000, 'discount' => 5,  'hot' => 1, 'category_id' => $tainghe->id, 'description' => 'Chống ồn hàng đầu thế giới, 8 mic, pin 30h, Bluetooth 5.2, gập gọn.'],
            ['name' => 'Sony WH-1000XM4',             'price' =>  6490000, 'discount' => 15, 'hot' => 0, 'category_id' => $tainghe->id, 'description' => 'Chống ồn xuất sắc, LDAC, pin 30h, multi-point connection.'],
            ['name' => 'Sony WF-1000XM5',             'price' =>  6990000, 'discount' => 5,  'hot' => 1, 'category_id' => $tainghe->id, 'description' => 'TWS chống ồn flagship, nhỏ gọn nhất, LDAC, pin 8h + case 24h.'],
            ['name' => 'AirPods Pro 2nd gen',         'price' =>  6490000, 'discount' => 0,  'hot' => 1, 'category_id' => $tainghe->id, 'description' => 'Chống ồn H2, Adaptive Audio, Personalized Spatial Audio, USB-C.'],
            ['name' => 'AirPods 4',                   'price' =>  3990000, 'discount' => 0,  'hot' => 1, 'category_id' => $tainghe->id, 'description' => 'TWS không dây, chip H2, ANC (bản cao cấp), Personalized Spatial Audio.'],
            ['name' => 'AirPods Max',                 'price' => 12990000, 'discount' => 5,  'hot' => 0, 'category_id' => $tainghe->id, 'description' => 'Over-ear premium, chống ồn H1, Spatial Audio, cổng USB-C, nhôm cao cấp.'],
            ['name' => 'Bose QuietComfort 45',        'price' =>  7990000, 'discount' => 10, 'hot' => 0, 'category_id' => $tainghe->id, 'description' => 'Chống ồn Bose nổi tiếng, pin 24h, âm thanh cân bằng chuyên nghiệp.'],
            ['name' => 'Bose QuietComfort Ultra TWS', 'price' =>  6990000, 'discount' => 8,  'hot' => 0, 'category_id' => $tainghe->id, 'description' => 'TWS chống ồn, Immersive Audio, pin 6h + 24h case, chống nước IPX4.'],
            ['name' => 'Samsung Galaxy Buds3 Pro',    'price' =>  4990000, 'discount' => 5,  'hot' => 1, 'category_id' => $tainghe->id, 'description' => 'TWS chống ồn AI, 360° audio, pin 6h, chống nước IP57, Blade design.'],
            ['name' => 'JBL Live 770NC',              'price' =>  2990000, 'discount' => 0,  'hot' => 0, 'category_id' => $tainghe->id, 'description' => 'Over-ear True Adaptive NC, pin 65h, MultiPoint, Google Fast Pair.'],

            // ==================== SẠC DỰ PHÒNG ====================
            ['name' => 'Anker 737 Power Bank 24000mAh', 'price' =>  1990000, 'discount' => 5,  'hot' => 1, 'category_id' => $sac->id, 'description' => 'Pin dự phòng 24000mAh, sạc nhanh 140W, 3 cổng USB-C/A, màn hình LED.'],
            ['name' => 'Anker 733 Power Bank 10000mAh', 'price' =>  1290000, 'discount' => 0,  'hot' => 1, 'category_id' => $sac->id, 'description' => '2-in-1 sạc dự phòng tích hợp adapter sạc, 65W, 10000mAh.'],
            ['name' => 'Baseus Power Bank 20000mAh 65W','price' =>   890000, 'discount' => 10, 'hot' => 0, 'category_id' => $sac->id, 'description' => '20000mAh, sạc nhanh 65W PD, 2 cổng USB-C, 1 USB-A, có màn hình.'],
            ['name' => 'Xiaomi Power Bank 3 20000mAh',  'price' =>   650000, 'discount' => 5,  'hot' => 0, 'category_id' => $sac->id, 'description' => '20000mAh, sạc nhanh 18W, 2 USB-A + 1 USB-C, thiết kế mỏng nhẹ.'],
            ['name' => 'Samsung EB-P5300 10000mAh',     'price' =>   590000, 'discount' => 0,  'hot' => 0, 'category_id' => $sac->id, 'description' => '10000mAh, 25W Super Fast Charging, USB-C in/out, mỏng gọn.'],
            ['name' => 'Ugreen Nexode 20000mAh 100W',   'price' =>  1190000, 'discount' => 8,  'hot' => 1, 'category_id' => $sac->id, 'description' => '20000mAh, 100W sạc nhanh, 4 cổng (2C+2A), sạc laptop được.'],
            ['name' => 'ROMOSS Sense 8+ 30000mAh',      'price' =>   790000, 'discount' => 5,  'hot' => 0, 'category_id' => $sac->id, 'description' => '30000mAh dung lượng lớn, 22.5W, 3 cổng output, pin siêu bền.'],
        ];

        foreach ($products as $p) {
            Product::firstOrCreate(
                ['name' => $p['name']],
                array_merge($p, ['content' => $p['description']])
            );
        }
    }
}
