# MediaMart – Hệ thống bán điện tử & công nghệ

Nền tảng thương mại điện tử bán thiết bị điện tử, xây dựng bằng **Laravel 13** (PHP 8.3).

Dự án bao gồm đầy đủ luồng nghiệp vụ của một website bán hàng: duyệt
sản phẩm, giỏ hàng, đặt hàng, thanh toán (COD/VNPay/Momo mô phỏng QR),
voucher giảm giá, vận chuyển, quản trị (sản phẩm, danh mục, đơn hàng,
tin tức, khách hàng) và thông báo sau khi đặt hàng. Phần lõi
giỏ hàng - thanh toán - đặt hàng được thiết kế theo các **Design
Pattern (GoF)** để dễ mở rộng và bảo trì (xem mục bên dưới).

---

## Yêu cầu hệ thống

| Phần mềm | Phiên bản tối thiểu |
|----------|---------------------|
| PHP | 8.3+ |
| MySQL | 8.0+ / MariaDB 10.4+ |
| Composer | 2.x |
| Node.js | 18+ |
| XAMPP (hoặc tương đương) | 8.x |

---

## Hướng dẫn cài đặt & chạy dự án (từ clone đến lúc chạy được)

### Bước 1 - Clone dự án vào thư mục `htdocs` của XAMPP

```bash
cd C:\xampp\htdocs
git clone <repository-url> mediamart-laravel
cd mediamart-laravel
```

### Bước 2 - Khởi động Apache & MySQL

Mở **XAMPP Control Panel** → Start **Apache** và **MySQL**.

### Bước 3 - Tạo database

Truy cập [http://localhost/phpmyadmin](http://localhost/phpmyadmin) → tạo
database mới tên `mediamart_laravel` (collation `utf8mb4_unicode_ci`).

### Bước 4 - Cài dependencies PHP & JS

```bash
composer install
npm install
```

### Bước 5 - Tạo file cấu hình `.env`

```bash
copy .env.example .env
php artisan key:generate
```

Mở `.env` và kiểm tra/cập nhật thông tin kết nối database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mediamart_laravel
DB_USERNAME=root
DB_PASSWORD=
```

### Bước 6 - Chạy migration + seed dữ liệu mẫu

```bash
php artisan migrate:fresh --seed
```

Lệnh này sẽ:
- Tạo toàn bộ bảng theo migrations
- Seed dữ liệu mẫu: tài khoản admin/khách hàng, danh mục, sản phẩm, tin tức, đơn hàng mẫu

### Bước 7 - Build giao diện (Vite)

```bash
npm run build
```

> Nếu muốn vừa code vừa xem thay đổi tức thì (hot reload), dùng
> `npm run dev` ở một terminal riêng thay vì `npm run build`.

### Bước 8 - Truy cập website

Mở trình duyệt và vào:

```
http://localhost/mediamart-laravel/public
```

- Trang quản trị: `http://localhost/mediamart-laravel/public/admin`
- Tài khoản đăng nhập mặc định: xem mục [Tài khoản mặc định](#tài-khoản-mặc-định)

---

## Các cách chạy dự án khác

### Dùng Laravel artisan serve (không cần XAMPP/Apache)

```bash
php artisan serve
```

Truy cập: [http://localhost:8000](http://localhost:8000)

### Chế độ development (đầy đủ)

```bash
composer run dev
```

Khởi động đồng thời: Laravel server · Queue worker · Vite (hot reload)

> **Windows:** Lệnh `composer run dev` có thể lỗi do thiếu extension `pcntl` (không hỗ trợ trên Windows). Chạy riêng từng lệnh thay thế:
>
> ```bash
> php artisan serve        # Terminal 1
> npm run dev              # Terminal 2
> php artisan queue:listen # Terminal 3 (nếu cần)
> ```

---

## Tài khoản mặc định

| Loại | Email | Mật khẩu |
|------|-------|-----------|
| Admin | `admin@gmail.com` | `admin` |
| Khách hàng | `user@gmail.com` | `user` |

---

## Cấu trúc dự án

```
mediamart-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Frontend controllers
│   │   │   └── Admin/            # Admin controllers
│   │   └── Middleware/           # customer.auth, admin.auth
│   └── Models/                   # Eloquent models
├── database/
│   ├── migrations/               # Schema definitions
│   └── seeders/                  # Dữ liệu mẫu
├── public/
│   ├── css/mediamart.css         # Custom CSS
│   └── uploads/                  # Ảnh sản phẩm & tin tức
├── resources/views/
│   ├── frontend/                 # Giao diện khách hàng
│   └── admin/                    # Giao diện quản trị
└── routes/web.php                # Toàn bộ routes
```

---

## Các trang chính

### Khách hàng (`/`)
| Đường dẫn | Mô tả |
|-----------|-------|
| `/` | Trang chủ |
| `/products` | Danh sách sản phẩm |
| `/products/{id}` | Chi tiết sản phẩm |
| `/search` | Tìm kiếm |
| `/cart` | Giỏ hàng |
| `/wishlist` | Yêu thích |
| `/news` | Tin tức |
| `/contact` | Liên hệ |
| `/account/login` | Đăng nhập |
| `/account/register` | Đăng ký |

### Admin (`/admin`)
| Đường dẫn | Mô tả |
|-----------|-------|
| `/admin` | Dashboard |
| `/admin/products` | Quản lý sản phẩm |
| `/admin/categories` | Quản lý danh mục |
| `/admin/orders` | Quản lý đơn hàng |
| `/admin/news` | Quản lý tin tức |
| `/admin/customers` | Quản lý khách hàng |

---

## Lệnh hữu ích

```bash
# Reset và seed lại database
php artisan migrate:fresh --seed

# Xóa cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Chạy tests
composer run test

# Format code (Laravel Pint)
./vendor/bin/pint
```

---

## Design Patterns đã áp dụng

Module **Giỏ hàng - Thanh toán - Đặt hàng** được tổ chức lại theo 8
pattern GoF, mỗi pattern gắn với một chức năng nghiệp vụ cụ thể:

| # | Chức năng | Pattern | File chính |
|---|---|---|---|
| 1 | Cấu hình chung của shop (ngưỡng freeship, phí ship, voucher) | Singleton | `app/Support/SiteSettings.php` |
| 2 | Chọn phương thức thanh toán (COD/VNPay/Momo) | Factory Method | `app/Services/Payment/PaymentMethodFactory.php` |
| 3 | Tính phí ship từ đơn vị vận chuyển GHN/GHTK | Adapter | `app/Services/Shipping/ShippingAdapters.php` |
| 4 | Lựa chọn hình thức vận chuyển (tiêu chuẩn/nhanh/miễn phí) | Strategy | `app/Services/Shipping/ShippingFeeStrategy.php` |
| 5 | Áp voucher/giảm giá/freeship vào giỏ hàng | Decorator | `app/Services/Cart/CartPriceDecorator.php` |
| 6 | Tạo đơn hàng & chi tiết đơn hàng | Builder | `app/Services/Order/OrderBuilder.php` |
| 7 | Thông báo (email/SMS) sau khi đặt hàng | Observer | `app/Events/OrderPlaced.php`, `app/Listeners/*` |
| 8 | Quy trình checkout tổng thể | Facade | `app/Services/CheckoutFacade.php` |

Chi tiết định nghĩa, lý do chọn, code minh họa và sơ đồ UML (PlantUML)
cho từng pattern: xem [`docs/checkout-features-patterns.md`](docs/checkout-features-patterns.md)
và thư mục [`docs/uml/`](docs/uml/).

---

## Công nghệ sử dụng

- **Backend:** Laravel 13, PHP 8.3, MySQL
- **Frontend:** Bootstrap 5, Font Awesome 6, Be Vietnam Pro (Google Fonts)
- **Build tool:** Vite 8, Tailwind CSS v4
- **Editor:** CKEditor (admin)
- **Auth:** Laravel custom guards (`customer`, `admin`)
