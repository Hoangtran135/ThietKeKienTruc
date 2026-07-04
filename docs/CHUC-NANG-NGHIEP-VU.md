# MediaMart – Chức năng & Nghiệp vụ

> Tài liệu tổng hợp toàn bộ chức năng hệ thống, dùng cho báo cáo/thuyết trình đồ án.

---

## 1. Phía khách hàng (Frontend)

### 1.1. Duyệt & tìm sản phẩm

- Trang chủ: hiển thị sản phẩm/tin tức nổi bật (`hot`), danh mục theo cây cha-con
- Danh sách sản phẩm: lọc theo danh mục, lọc khoảng giá, sắp xếp (mới nhất / giá tăng-giảm / tên A→Z, Z→A)
- Tìm kiếm theo tên + lọc giá
- Chi tiết sản phẩm: mô tả, sản phẩm liên quan cùng danh mục, đánh giá trung bình

### 1.2. Giỏ hàng & đặt hàng

- Thêm / sửa số lượng / xóa sản phẩm trong giỏ
- Khách vãng lai: giỏ lưu session; khách đã đăng nhập: giỏ lưu DB (bền vững qua thiết bị), tự động gộp giỏ session vào DB khi đăng nhập
- Giới hạn số lượng thêm vào giỏ theo tồn kho thực tế
- Chọn phương thức vận chuyển (nhiều mức phí theo strategy, miễn phí ship khi đơn ≥ 500.000₫)
- Áp mã giảm giá (voucher: theo %, theo số tiền cố định, hoặc miễn phí ship)
- Chọn phương thức thanh toán: COD / VNPay / Momo (**2 cái sau hiện là demo QR code, chưa tích hợp cổng thanh toán thật**)
- Validate tồn kho lần cuối trước khi tạo đơn (chặn nếu hàng đã hết giữa lúc thêm giỏ và lúc đặt)
- Sau khi đặt hàng: gửi email xác nhận + log giả lập SMS (Observer pattern)

### 1.3. Tài khoản khách hàng

- Đăng ký / đăng nhập / đăng xuất / đổi mật khẩu / cập nhật hồ sơ
- Quên mật khẩu (gửi link reset qua email, hết hạn sau 60 phút)
- Giới hạn 5 lần thử đăng nhập/phút (chống brute-force)
- Xem lịch sử đơn hàng, chi tiết từng đơn

### 1.4. Tương tác khác

- Đánh giá sản phẩm (chỉ cho phép nếu **đã mua** sản phẩm đó và **chưa đánh giá** trước đó)
- Danh sách yêu thích (wishlist)
- Tin tức: danh sách + chi tiết bài viết
- Trang tĩnh: giới thiệu, chính sách, liên hệ

---

## 2. Phía quản trị (Admin)

- Đăng nhập riêng (guard `admin`), có quên mật khẩu, throttle riêng
- Dashboard tổng quan
- CRUD: sản phẩm, danh mục, tin tức, khách hàng, voucher
- Quản lý đơn hàng: xem danh sách/chi tiết, đổi trạng thái đơn (pending → confirmed → shipping → delivered, hoặc cancelled)
- Xuất file danh sách đơn hàng ra CSV (UTF-8 BOM, đọc được tiếng Việt trên Excel)

---

## 3. Nghiệp vụ lõi (business rules)

| Quy tắc | Mô tả |
|---|---|
| Trừ/hoàn kho | Trừ kho khi admin xác nhận đơn (pending→confirmed); hoàn kho khi hủy đơn đã xác nhận |
| Đánh giá sản phẩm | Chỉ khách đã mua và nhận hàng thành công (status confirmed/shipping/delivered) mới được đánh giá, mỗi khách 1 lần/sản phẩm |
| Voucher | Kiểm tra còn hiệu lực (`is_active`), chưa hết hạn (`expires_at`), chưa vượt giới hạn lượt dùng (`usage_limit`/`used_count`), tự tăng `used_count` sau khi đặt hàng thành công |
| Thông báo trạng thái đơn | Mỗi lần đổi trạng thái đơn → tự động gửi email thông báo cho khách (Observer pattern) |
| Miễn phí ship | Tự động áp dụng khi tổng đơn ≥ ngưỡng cấu hình (mặc định 500.000₫) |

---

## 4. Kiến trúc & Design Pattern áp dụng

Module giỏ hàng – thanh toán – đặt hàng được tổ chức theo các pattern GoF:

| # | Chức năng | Pattern | File chính |
|---|---|---|---|
| 1 | Cấu hình chung của shop (ngưỡng freeship, phí ship, voucher) | Singleton | `app/Support/SiteSettings.php` |
| 2 | Chọn phương thức thanh toán (COD/VNPay/Momo) | Factory Method | `app/Services/Payment/PaymentMethod.php` |
| 3 | Tính phí ship từ đơn vị vận chuyển | Strategy | `app/Services/Shipping/ShippingStrategy.php` |
| 4 | Sắp xếp danh sách sản phẩm | Strategy | `app/Services/ProductService.php` |
| 5 | Áp voucher/giảm giá/freeship vào giỏ hàng | Decorator | `app/Services/Cart/CartPrice.php` |
| 6 | Tạo đơn hàng & chi tiết đơn hàng | Builder | `app/Services/Order/OrderDirector.php` |
| 7 | Thông báo (email/SMS) sau khi đặt hàng & khi đổi trạng thái đơn | Observer | `app/Events/OrderPlaced.php`, `app/Events/OrderStatusChanged.php`, `app/Listeners/*` |
| 8 | Quy trình checkout tổng thể | Facade | `app/Services/CheckoutFacade.php` |
| 9 | Giỏ hàng dùng chung 1 instance trong request | Singleton | `app/Services/CartService.php` |
| 10 | Xác thực khách hàng (login/register/logout) | Facade | `app/Services/AuthFacade.php` |

---

## 5. Bảo mật & vận hành

- **Throttle đăng nhập:** giới hạn 5 lần/phút cho login/register (customer + admin)
- **Quên mật khẩu:** Password Broker chuẩn Laravel, token hết hạn sau 60 phút
- **Giỏ hàng bền vững:** bảng `cart_items` cho khách đã đăng nhập
- **Kiểm tra tồn kho:** chặn vượt tồn kho khi thêm giỏ/đặt hàng; tự trừ/hoàn kho theo trạng thái đơn
- **Queue:** giữ `sync` (môi trường XAMPP không có worker nền); có thể đổi `database`/`redis` khi deploy server thật
- **Thanh toán VNPay/Momo:** bản demo, chưa tích hợp API thật — không dùng cho production
