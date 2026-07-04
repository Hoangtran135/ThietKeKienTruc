# Thông báo trong dự án MediaMart

## 1. Vị trí chính của thông báo UI
- `resources/views/frontend/layouts/app.blade.php`
  - Đây là nơi định nghĩa hệ thống toast thông báo trên giao diện khách.
  - Có container: `#mm-toast-container`.
  - Có hàm JavaScript `mmToast(msg, type)` để tạo thông báo:
    - `success` hiển thị thành công.
    - `error` hiển thị lỗi.
  - Trong cùng file cũng có logic chuyển `session('success')` và `session('error')` thành toast.

## 2. Cách thông báo được kích hoạt
- Nhiều controller backend dùng session flash để gửi thông báo về view:
  - `redirect()->with('success', '...')`
  - `redirect()->with('error', '...')`
  - `back()->with('success', '...')`
  - `back()->with('error', '...')`
- Ví dụ các controller:
  - `app/Http/Controllers/AccountController.php`
  - `app/Http/Controllers/CartController.php`
  - `app/Http/Controllers/ProductController.php`
  - `app/Http/Controllers/WishlistController.php`
  - `app/Http/Controllers/Admin/*` (AdminProductController, AdminCategoryController, AdminVoucherController, ...)

## 3. Thông báo admin
- `resources/views/admin/layouts/app.blade.php`
  - Admin dùng các alert Bootstrap để hiển thị `session('success')` và `session('error')`.
- Nhiều trang admin cũng trực tiếp hiển thị thông báo như:
  - `resources/views/admin/orders/index.blade.php`
  - `resources/views/admin/vouchers/index.blade.php`
  - `resources/views/admin/forgot-password.blade.php`

## 4. Thông báo email / Laravel Notification
- Thư mục `app/Notifications/` chứa các thông báo email:
  - `app/Notifications/CustomerResetPasswordNotification.php`
  - `app/Notifications/AdminResetPasswordNotification.php`
- Chức năng này gửi email khi người dùng hoặc admin yêu cầu đặt lại mật khẩu.

## 5. Event / Listener liên quan đến thông báo
- Có các listener quản lý gửi email/SMS khi đơn hàng được đặt hoặc thay đổi trạng thái:
  - `app/Listeners/SendOrderEmailNotification.php`
  - `app/Listeners/SendOrderSmsNotification.php`
  - `app/Listeners/SendOrderStatusEmailNotification.php`
- Các event tương ứng nằm trong `app/Events/`:
  - `OrderPlaced.php`
  - `OrderStatusChanged.php`

## 6. Tác dụng của hệ thống thông báo
- Thông báo UI: thông báo nhanh cho khách hàng khi thao tác xong (thêm giỏ hàng, đặt hàng, đánh giá, đăng nhập, cập nhật hồ sơ, ...).
- Thông báo admin: báo kết quả thao tác quản trị như thêm/sửa/xóa sản phẩm, voucher, đơn hàng.
- Thông báo email: gửi link đặt lại mật khẩu và cập nhật trạng thái đơn hàng.

## 7. Kết luận
- Nếu cần thay đổi cách hiển thị toast, sửa trong `resources/views/frontend/layouts/app.blade.php`.
- Nếu cần thêm thông báo mới từ controller, dùng `->with('success', '...')` hoặc `->with('error', '...')`.
- Nếu cần thay đổi nội dung email reset mật khẩu, chỉnh `app/Notifications/CustomerResetPasswordNotification.php` hoặc `AdminResetPasswordNotification.php`.
