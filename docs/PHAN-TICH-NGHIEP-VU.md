# MediaMart – Phân tích nghiệp vụ chi tiết

> Tài liệu phân tích nghiệp vụ (business analysis) cho từng chức năng
> chính: actor liên quan, luồng xử lý, điều kiện trước/sau, quy tắc
> nghiệp vụ và các trường hợp ngoại lệ. Dùng bổ sung cho
> [`CHUC-NANG-NGHIEP-VU.md`](CHUC-NANG-NGHIEP-VU.md) (bản liệt kê tổng quan).

---

## 1. Actor trong hệ thống

| Actor | Mô tả |
|---|---|
| **Khách vãng lai (Guest)** | Chưa đăng nhập — duyệt sản phẩm, tìm kiếm, thêm giỏ hàng (session) |
| **Khách hàng (Customer)** | Đã đăng ký/đăng nhập — đặt hàng, đánh giá, xem lịch sử đơn |
| **Quản trị viên (Admin)** | Quản lý sản phẩm/danh mục/đơn hàng/tin tức/voucher/khách hàng |
| **Hệ thống (System)** | Tự động trừ/hoàn kho, gửi email, tính phí ship, áp voucher |

---

## 2. Nghiệp vụ Giỏ hàng & Đặt hàng

### 2.1. Thêm sản phẩm vào giỏ

**Actor:** Guest, Customer
**Điều kiện trước:** Sản phẩm tồn tại (`Product::findOrFail`)
**Luồng chính:**
1. Actor chọn sản phẩm → bấm "Thêm vào giỏ"
2. Hệ thống kiểm tra giỏ hiện có sản phẩm đó chưa
   - Guest: đọc/ghi giỏ hàng trong session (`Session::get('cart')`)
   - Customer: đọc/ghi bảng `cart_items` (khóa `customer_id` + `product_id`)
3. Nếu số lượng hiện tại trong giỏ **đã bằng hoặc vượt tồn kho** (`product.stock`) → **không cộng thêm**, giữ nguyên số lượng (âm thầm chặn, không báo lỗi ra UI)
4. Ngược lại: tăng số lượng thêm 1 (sản phẩm mới → số lượng = 1)

**Điều kiện sau:** Giỏ hàng được cập nhật, phản hồi JSON (nếu AJAX) hoặc redirect kèm flash message

**Quy tắc nghiệp vụ:**
- RN-CART-01: Số lượng 1 sản phẩm trong giỏ không được vượt `product.stock`

### 2.2. Đăng nhập → gộp giỏ hàng

**Actor:** Customer
**Trigger:** Đăng nhập thành công (`AuthFacade::login`)
**Luồng chính:**
1. Lấy giỏ hàng đang lưu trong session (giỏ của lúc còn là Guest)
2. Với từng sản phẩm trong giỏ session:
   - Nếu sản phẩm đã có trong `cart_items` của khách hàng này → **cộng dồn** số lượng
   - Số lượng sau khi cộng bị giới hạn tối đa bằng tồn kho hiện tại (`min(tổng, stock)`)
3. Xóa giỏ hàng session sau khi gộp xong

**Quy tắc nghiệp vụ:**
- RN-CART-02: Không mất sản phẩm khách đã thêm trước khi đăng nhập
- RN-CART-03: Gộp giỏ không được vượt tồn kho dù tổng 2 giỏ lớn hơn

### 2.3. Đặt hàng (Checkout)

**Actor:** Customer (bắt buộc đăng nhập — middleware `customer.auth`)
**Điều kiện trước:** Giỏ hàng không rỗng

**Luồng chính:**
1. Kiểm tra giỏ hàng không rỗng → nếu rỗng, chặn và báo lỗi
2. **Validate tồn kho lần cuối** (`CartService::validateStock`): so từng sản phẩm trong giỏ với `product.stock` hiện tại trong DB
   - Nếu bất kỳ sản phẩm nào không đủ hàng (do người khác vừa mua) → chặn đặt hàng, thông báo cụ thể từng sản phẩm thiếu
3. Tính phí vận chuyển theo phương thức đã chọn (Strategy Pattern: `standard`/`express`/`free`)
4. Áp mã voucher (nếu có) — xem mục 2.5
5. Tạo `Order` (status = `PENDING`) + các `OrderDetail` tương ứng từng sản phẩm (Builder Pattern)
6. Phát sự kiện `OrderPlaced` → kích hoạt gửi email xác nhận + log SMS (Observer Pattern)
7. Nếu voucher có nguồn từ DB → tăng `used_count` của voucher đó
8. Xóa giỏ hàng (DB hoặc session tùy loại khách)
9. Nếu phương thức thanh toán là VNPay/Momo → chuyển hướng trang thanh toán QR (demo); nếu COD → về trang chủ kèm thông báo thành công

**Điều kiện sau:** 1 đơn hàng mới ở trạng thái `PENDING`, tồn kho **chưa bị trừ** (chỉ trừ khi admin xác nhận — xem mục 2.6)

**Quy tắc nghiệp vụ:**
- RN-ORDER-01: Không tạo được đơn hàng nếu giỏ trống
- RN-ORDER-02: Không tạo được đơn hàng nếu tồn kho không đủ tại thời điểm đặt (chống oversell)
- RN-ORDER-03: Đơn hàng mới luôn ở trạng thái `PENDING` (chưa trừ kho ngay)

### 2.4. Tính phí vận chuyển (Strategy Pattern)

**Actor:** System
**Luồng chính:**
1. Actor chọn 1 trong các phương thức: tiêu chuẩn / nhanh / miễn phí
2. Hệ thống tính phí theo strategy tương ứng, dựa trên tổng tiền đơn hàng (subtotal)
3. Nếu tổng đơn hàng ≥ ngưỡng freeship (mặc định 500.000₫) → tự động miễn phí ship bất kể phương thức chọn

**Quy tắc nghiệp vụ:**
- RN-SHIP-01: Ngưỡng freeship áp dụng toàn cục, cấu hình tại `SiteSettings` (Singleton)

### 2.5. Áp dụng Voucher (Decorator Pattern)

**Actor:** Customer, System
**Điều kiện trước:** Voucher tồn tại và còn hiệu lực

**Luồng chính:**
1. Customer nhập mã voucher lúc checkout
2. Hệ thống tìm voucher theo mã (`Voucher::findValid`), kiểm tra:
   - `is_active = true`
   - Chưa hết hạn (`expires_at` chưa qua, hoặc null)
   - Chưa vượt giới hạn lượt dùng (`used_count < usage_limit`, nếu `usage_limit` được set)
3. Nếu hợp lệ, áp giảm giá theo loại:
   - `percent`: giảm % trên tổng tiền hàng
   - `fixed`/`amount`: giảm số tiền cố định
   - `freeship`: miễn phí hoàn toàn phí vận chuyển
4. Nếu voucher không hợp lệ/không tồn tại → bỏ qua, không áp giảm giá (không chặn đặt hàng)

**Quy tắc nghiệp vụ:**
- RN-VOUCHER-01: 1 đơn hàng chỉ áp được 1 voucher
- RN-VOUCHER-02: Voucher hết lượt dùng/hết hạn/inactive thì không còn hiệu lực dù mã đúng
- RN-VOUCHER-03: `used_count` chỉ tăng khi đơn hàng tạo thành công (không tăng nếu checkout thất bại)

### 2.6. Quản lý trạng thái đơn hàng (Admin)

**Actor:** Admin
**Sơ đồ trạng thái:**

```
PENDING(0) → CONFIRMED(1) → SHIPPING(2) → DELIVERED(3)
   │
   └──────────→ CANCELLED(4)   (chỉ hủy được khi đang ở PENDING)
```

**Luồng chính (tiến trạng thái tuần tự):**
1. Admin bấm nút "tiến trạng thái tiếp theo" trên danh sách/chi tiết đơn hàng
2. Hệ thống tăng `status` lên 1 bước kế tiếp (0→1→2→3)
3. **Khi chuyển từ `PENDING` sang `CONFIRMED`**: tự động **trừ tồn kho** từng sản phẩm trong đơn (`stock -= number`)
4. Phát sự kiện `OrderStatusChanged` → gửi email thông báo cho khách hàng

**Luồng hủy đơn:**
1. Chỉ hiển thị nút "Hủy" khi đơn đang ở trạng thái `PENDING` (`isCancellable()`)
2. Khi hủy đơn đã từng ở trạng thái `CONFIRMED` hoặc `SHIPPING` → **hoàn lại tồn kho** đã trừ trước đó

**Điều kiện sau:** Trạng thái đơn cập nhật, kho được điều chỉnh tương ứng, khách hàng nhận email thông báo

**Quy tắc nghiệp vụ:**
- RN-ORDER-04: Tồn kho chỉ bị trừ khi đơn được xác nhận (không trừ ngay lúc đặt hàng)
- RN-ORDER-05: Trạng thái `DELIVERED` (3) là trạng thái kết thúc thành công — **không có bước nào sau đó**, không được nhầm với `CANCELLED` (4)
- RN-ORDER-06: Chỉ hủy được đơn khi còn ở `PENDING`, không hủy được đơn đã `CONFIRMED`/`SHIPPING`/`DELIVERED`

> **Lưu ý sửa lỗi:** trước đây UI danh sách đơn hàng có bug tính sai
> "trạng thái kế tiếp" khi đơn đang ở `DELIVERED` (3), khiến bấm nhầm
> sang `CANCELLED` (4). Đã sửa: nút tiến trạng thái chỉ hiện khi
> `status < DELIVERED`.

---

## 3. Nghiệp vụ Tài khoản & Xác thực

### 3.1. Đăng ký tài khoản khách hàng

**Actor:** Guest → trở thành Customer
**Luồng chính:**
1. Nhập thông tin (tên, email, số điện thoại, địa chỉ, mật khẩu)
2. Validate qua `RegisterRequest` (email unique, mật khẩu đủ mạnh...)
3. Tạo bản ghi `Customer`, mật khẩu băm bằng `Hash::make()` (bcrypt)
4. Chuyển hướng về trang đăng nhập kèm thông báo thành công

**Lưu ý nghiệp vụ:** Hệ thống **hiện chưa gửi email chào mừng/xác thực** sau khi đăng ký — chỉ tạo tài khoản. Việc gửi email chỉ xảy ra ở luồng đặt hàng (xác nhận đơn) và quên mật khẩu.

### 3.2. Đăng nhập & chống brute-force

**Actor:** Customer, Admin (2 guard riêng biệt: `customer`, `admin`)
**Quy tắc nghiệp vụ:**
- RN-AUTH-01: Giới hạn 5 lần thử đăng nhập/phút cho mỗi IP (middleware `throttle:5,1`), áp dụng riêng cho cả login khách hàng và login admin
- RN-AUTH-02: Mật khẩu **luôn phải được băm (bcrypt)** trước khi lưu DB — không được cập nhật trực tiếp qua query builder thô (mass update) vì sẽ bỏ qua cơ chế tự băm của Eloquent, gây lỗi *"password does not use the Bcrypt algorithm"* khi xác thực

### 3.3. Quên mật khẩu

**Actor:** Customer, Admin
**Luồng chính:**
1. Nhập email → hệ thống tạo token reset (Password Broker chuẩn Laravel), lưu bảng `password_reset_tokens`
2. Gửi email chứa link reset (token hết hạn sau 60 phút)
3. Actor bấm link → nhập mật khẩu mới → hệ thống xác minh token còn hạn & khớp email → cập nhật mật khẩu (băm lại)

**Quy tắc nghiệp vụ:**
- RN-AUTH-03: Token reset dùng 1 lần, hết hạn sau 60 phút
- RN-AUTH-04: Broker `customers` và `admins` tách biệt nhưng dùng chung bảng token (khóa theo email)

---

## 4. Nghiệp vụ Đánh giá sản phẩm (Rating)

**Actor:** Customer
**Điều kiện trước:**
- Đã đăng nhập
- Đã **mua và nhận** sản phẩm đó thành công (đơn có trạng thái `CONFIRMED`/`SHIPPING`/`DELIVERED` chứa sản phẩm này)
- Chưa từng đánh giá sản phẩm này trước đó

**Luồng chính:**
1. Hệ thống kiểm tra 2 điều kiện trên (`hasPurchased()`, `hasRated()`)
2. Nếu đủ điều kiện → hiện form đánh giá (số sao + nhận xét)
3. Lưu `Rating` gắn với `customer_id` + `product_id`

**Quy tắc nghiệp vụ:**
- RN-RATING-01: Không cho đánh giá nếu chưa mua hàng (chặn đánh giá ảo)
- RN-RATING-02: Mỗi khách chỉ đánh giá 1 lần / 1 sản phẩm

---

## 5. Nghiệp vụ Tìm kiếm & Lọc sản phẩm

**Actor:** Guest, Customer
**Luồng chính:**
1. Lọc theo danh mục (`category_id`), khoảng giá (`from_price`/`to_price`)
2. Sắp xếp theo 1 trong 5 chiến lược (Strategy Pattern): mới nhất, giá tăng, giá giảm, tên A→Z, tên Z→A
3. Phân trang 12 sản phẩm/trang, giữ nguyên query string khi chuyển trang

**Quy tắc nghiệp vụ:**
- RN-SEARCH-01: Có thể kết hợp đồng thời lọc danh mục + khoảng giá + sắp xếp

---

## 6. Nghiệp vụ Email/Thông báo (Observer Pattern)

| Sự kiện | Trigger | Người nhận | Nội dung |
|---|---|---|---|
| `OrderPlaced` | Đặt hàng thành công | Customer | Xác nhận đơn hàng + chi tiết sản phẩm/tổng tiền |
| `OrderStatusChanged` | Admin đổi trạng thái đơn | Customer | Trạng thái mới của đơn hàng |
| Reset password | Yêu cầu quên mật khẩu | Customer/Admin | Link đặt lại mật khẩu |

**Quy tắc nghiệp vụ:**
- RN-MAIL-01: Nếu gửi email thất bại (lỗi SMTP), **không làm rollback đơn hàng** — chỉ ghi log lỗi, đơn hàng vẫn được tạo bình thường (email là nghiệp vụ phụ, không phải điều kiện bắt buộc để đặt hàng thành công)
- RN-MAIL-02: Khi `MAIL_MAILER=log`, hệ thống vẫn báo "gửi thành công" trong log nhưng **thực chất không gửi email thật** — cần `MAIL_MAILER=smtp` với thông tin SMTP hợp lệ để email thực sự rời khỏi server

---

## 7. Bảng tổng hợp Use Case chính

| Use Case | Actor | Pre-condition | Post-condition |
|---|---|---|---|
| Thêm vào giỏ | Guest/Customer | Sản phẩm tồn tại | Giỏ hàng cập nhật, không vượt tồn kho |
| Đặt hàng | Customer | Giỏ không rỗng, đủ tồn kho | Đơn hàng PENDING được tạo, email xác nhận gửi đi |
| Xác nhận đơn | Admin | Đơn đang PENDING | Đơn CONFIRMED, tồn kho bị trừ |
| Hủy đơn | Admin | Đơn đang PENDING | Đơn CANCELLED |
| Đánh giá SP | Customer | Đã mua & nhận hàng, chưa đánh giá | Rating được lưu |
| Áp voucher | Customer | Voucher hợp lệ | Giảm giá áp dụng, used_count +1 (nếu đặt hàng thành công) |
| Quên mật khẩu | Customer/Admin | Email tồn tại trong hệ thống | Token reset gửi qua email, hết hạn sau 60 phút |
