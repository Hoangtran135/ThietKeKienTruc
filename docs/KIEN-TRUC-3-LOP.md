# MediaMart – Kiến trúc 3 lớp (3-Tier Architecture)

## 1. Sơ đồ tổng quan

```
┌─────────────────────┐      HTTP Request       ┌──────────────────────────────┐      SQL Query       ┌──────────────┐
│   CLIENT (Trình      │ ───────────────────────▶ │   SERVER (Laravel 12 + PHP)  │ ─────────────────────▶ │  DATABASE     │
│   duyệt / Browser)   │ ◀─────────────────────── │   chạy trên Apache (XAMPP)   │ ◀───────────────────── │  (MySQL)      │
└─────────────────────┘      HTML / JSON         └──────────────────────────────┘      Result Set       └──────────────┘
```

## 2. Chi tiết từng lớp

### 2.1. Lớp Client (Presentation Layer)

**Vai trò:** Hiển thị giao diện, thu thập input người dùng, gửi yêu cầu lên Server.

**Thành phần:**
- **HTML/CSS:** Bootstrap 5 + CSS tùy chỉnh (`public/css/mediamart.css`)
- **Blade Template** (`resources/views/`): Server render sẵn HTML gửi về Client (không phải SPA) — chia 2 nhóm:
  - `frontend/*`: giao diện khách hàng
  - `admin/*`: giao diện quản trị
- **JavaScript (Vanilla JS + Fetch API):** xử lý một số tương tác động không cần tải lại trang:
  - Thêm sản phẩm vào giỏ (AJAX, hiện toast thông báo)
  - Áp dụng voucher & xem trước tổng tiền (`cart.preview` endpoint)
  - Toast notification (góc phải màn hình) cho mọi thông báo thành công/lỗi

**Đặc điểm quan trọng:** Client **không truy cập trực tiếp Database** — mọi yêu cầu đều phải đi qua Server. Đây chính là ranh giới phân tách bảo mật quan trọng nhất của kiến trúc 3 lớp.

### 2.2. Lớp Server (Business Logic / Application Layer)

**Vai trò:** Tiếp nhận request, xử lý nghiệp vụ, gọi Database, trả kết quả về Client.

**Công nghệ:** Laravel Framework 12, PHP 8.2+, chạy trên Apache (module `php8apache2_4.dll` của XAMPP).

**Kiến trúc nội bộ theo mô hình MVC mở rộng:**

```
Request
   │
   ▼
routes/web.php  ──────────▶  Middleware (customer.auth, admin.auth, throttle...)
   │
   ▼
Controller (app/Http/Controllers/)   ← chỉ điều phối request/response
   │
   ▼
Service Layer (app/Services/)        ← xử lý nghiệp vụ thật sự (Design Patterns GoF)
   │
   ▼
Model / Eloquent ORM (app/Models/)   ← ánh xạ đối tượng ↔ bảng dữ liệu
   │
   ▼
Database (MySQL)
```

**Các thành phần chính trong lớp Server:**

| Thành phần | Vai trò |
|---|---|
| **Routes** (`routes/web.php`) | Định tuyến URL → Controller |
| **Middleware** | Xác thực (`customer.auth`, `admin.auth`), chống brute-force (`throttle:5,1`) |
| **Controllers** | Nhận request, gọi Service, trả view/JSON — không chứa logic nghiệp vụ |
| **Services** (`CheckoutFacade`, `CartService`, `OrderService`...) | Chứa toàn bộ logic nghiệp vụ, áp dụng Design Pattern GoF |
| **Models** (Eloquent) | Ánh xạ bảng DB thành object PHP, quan hệ (hasMany/belongsTo), validation cấp model |
| **Events/Listeners** | Xử lý bất đồng bộ theo nghiệp vụ (gửi email khi đặt hàng — Observer Pattern) |
| **Requests** (Form Request) | Validate dữ liệu đầu vào trước khi vào Controller |

**Tại sao tách Controller khỏi Service?**
Controller trong dự án được giữ **rất mỏng** (thin controller) — chỉ nhận request và gọi 1 dòng tới Service (VD: `CheckoutFacade::placeOrder(...)`). Toàn bộ độ phức tạp nghiệp vụ (tính phí ship, áp voucher, tạo đơn, gửi email) nằm trong Service Layer. Cách tách này giúp:
- Dễ viết unit test cho logic nghiệp vụ mà không cần giả lập HTTP request
- Tái sử dụng logic nghiệp vụ ở nhiều nơi (VD: cả `CartController::checkout()` và có thể dùng lại cho API sau này)
- Đúng nguyên tắc Single Responsibility (SRP)

### 2.3. Lớp Database (Data Layer)

**Vai trò:** Lưu trữ và truy xuất dữ liệu bền vững.

**Công nghệ:** MySQL 8.0 (qua XAMPP), truy cập thông qua **Eloquent ORM** của Laravel (không viết SQL thô).

**Thiết kế:**
- **Migration** (`database/migrations/`): định nghĩa schema bằng code, version-control được cấu trúc bảng
- **Seeder** (`database/seeders/`): sinh dữ liệu mẫu (admin, danh mục, sản phẩm, khách hàng, đơn hàng mẫu)
- **Quan hệ chính:**
  ```
  categories (1) ──< products (N)
  customers  (1) ──< orders (N) ──< order_details (N) >── products
  customers  (1) ──< ratings (N) >── products
  customers  (1) ──< cart_items (N) >── products
  ```

**Bảng dữ liệu chính:** `categories`, `products`, `customers`, `admins`, `orders`, `order_details`, `ratings`, `vouchers`, `news`, `cart_items`, `password_reset_tokens`.

## 3. Luồng xử lý minh họa (Ví dụ: Đặt hàng)

```
1. Client (JS)         → fetch POST /cart/checkout (JSON: payment_method, shipping_method, voucher_code)
2. Server – Middleware → customer.auth kiểm tra đăng nhập
3. Server – Controller → CartController::checkout() nhận request
4. Server – Service     → CheckoutFacade::placeOrder() xử lý:
                            - ShippingFeeCalculator (Strategy) tính phí ship
                            - PercentDiscountDecorator (Decorator) áp voucher
                            - OrderBuilder (Builder) dựng Order + OrderDetail
                            - Event OrderPlaced (Observer) → gửi email
5. Server – Model       → Order::create(), OrderDetail::create() ghi xuống DB
6. Database             → MySQL lưu bản ghi orders + order_details
7. Server → Client      → Response redirect kèm thông báo thành công
```

## 4. Vì sao đây vẫn là kiến trúc 3 lớp dù không phải SPA + REST API

Một số người nhầm lẫn "kiến trúc 3 lớp" phải luôn có Client tách biệt hoàn toàn (SPA) gọi REST API riêng. Thực tế, kiến trúc 3 lớp chỉ yêu cầu:

1. **Presentation** (Client) không chứa logic nghiệp vụ, không truy cập DB trực tiếp ✅
2. **Business Logic** (Server) xử lý toàn bộ nghiệp vụ, độc lập với cách hiển thị ✅
3. **Data** (Database) chỉ lo lưu trữ, không biết gì về nghiệp vụ hay giao diện ✅

Dự án MediaMart thỏa cả 3 điều kiện trên — chỉ khác là Client chủ yếu nhận **HTML đã render sẵn** (Server-Side Rendering qua Blade) thay vì luôn nhận JSON thuần, và chỉ dùng JSON/AJAX cho một số chức năng cần cập nhật động (giỏ hàng, voucher). Đây là mô hình phổ biến của các ứng dụng Laravel truyền thống (không phải kiến trúc sai).
