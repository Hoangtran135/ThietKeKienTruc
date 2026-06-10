# Design Patterns trong hệ thống Đặt hàng/Thanh toán - Phân theo chức năng

Tài liệu này mô tả các Design Pattern (GoF) đã áp dụng trong module
Giỏ hàng - Thanh toán - Đặt hàng của MediaMart, trình bày theo từng
**chức năng nghiệp vụ** thay vì theo từng pattern riêng lẻ.

---

## 1. Quản lý cấu hình chung của shop

**Chức năng**: Lưu trữ và cung cấp các thông số cấu hình dùng chung
trong toàn hệ thống: ngưỡng miễn phí ship, phí ship mặc định, danh
sách voucher hợp lệ.

**Pattern áp dụng**: `Singleton`

**Định nghĩa**: Đảm bảo một class chỉ có duy nhất một instance trong
suốt vòng đời ứng dụng, và cung cấp một điểm truy cập toàn cục đến
instance đó.

**Lý do chọn**: Cấu hình shop (phí ship, voucher...) là dữ liệu dùng
chung, không cần và không nên khởi tạo nhiều lần ở nhiều nơi. Singleton
giúp mọi thành phần (CheckoutFacade, ShippingFeeCalculator...) đọc
cùng một bộ cấu hình nhất quán.

**Code minh họa** (`app/Support/SiteSettings.php`):

```php
class SiteSettings
{
    private static ?SiteSettings $instance = null;

    private int $freeshipThreshold = 500000;
    private int $standardShippingFee = 30000;
    private int $expressShippingFee = 60000;

    private function __construct() {}

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function freeshipThreshold(): int { return $this->freeshipThreshold; }
    public function findVoucher(?string $code): ?array { /* ... */ }
}
```

**Sơ đồ UML**: [`docs/uml/16_singleton.puml`](uml/16_singleton.puml)

---

## 2. Chọn phương thức thanh toán (COD / VNPay / Momo)

**Chức năng**: Khi khách hàng đặt hàng, hệ thống cần tạo ra một đối
tượng đại diện cho phương thức thanh toán đã chọn (COD, VNPay, Momo),
mỗi phương thức có nhãn hiển thị, badge và cách tạo mã QR khác nhau.

**Pattern áp dụng**: `Factory Method`

**Định nghĩa**: Định nghĩa một interface (hoặc phương thức) để tạo đối
tượng, nhưng để cho lớp con (hoặc factory) quyết định lớp cụ thể nào
sẽ được khởi tạo.

**Lý do chọn**: Số lượng/loại phương thức thanh toán có thể mở rộng
trong tương lai (thêm ZaloPay, ShopeePay...). Factory Method giúp
`CartController` và `PaymentController` không cần biết chi tiết từng
loại, chỉ cần gọi `PaymentMethodFactory::make($code)`.

**Code minh họa** (`app/Services/Payment/PaymentMethodFactory.php`):

```php
interface PaymentMethod
{
    public function code(): string;
    public function label(): string;
    public function requiresQrPayment(): bool;
    public function buildQrUrl(Order $order): ?string;
}

class VnPayPaymentMethod extends AbstractQrPaymentMethod
{
    public function code(): string { return 'vnpay'; }
    public function label(): string { return 'VNPay'; }
}

class PaymentMethodFactory
{
    public static function make(string $code): PaymentMethod
    {
        return match ($code) {
            'cod'   => new CodPaymentMethod(),
            'vnpay' => new VnPayPaymentMethod(),
            'momo'  => new MomoPaymentMethod(),
            default => throw new InvalidArgumentException("Phương thức thanh toán không hợp lệ: {$code}"),
        };
    }
}
```

**Sơ đồ UML**: [`docs/uml/17_factory_method_payment.puml`](uml/17_factory_method_payment.puml)

---

## 3. Tính phí vận chuyển từ các đơn vị vận chuyển (GHN / GHTK)

**Chức năng**: Lấy phí vận chuyển thực tế từ các API/SDK của đơn vị
vận chuyển bên thứ ba (GHN, GHTK) - mỗi bên có cấu trúc tham số/trả về
khác nhau.

**Pattern áp dụng**: `Adapter`

**Định nghĩa**: Chuyển đổi interface của một class thành một interface
khác mà client mong đợi, giúp các class có interface không tương thích
có thể làm việc cùng nhau.

**Lý do chọn**: SDK giả lập của GHN (`GhnApiClient::calculateFee()`)
và GHTK (`GhtkApiSdk::estimateShippingCost()`) có chữ ký phương thức và
kiểu dữ liệu trả về khác nhau. Adapter "bọc" chúng lại thành một
interface chung `ShippingProvider` để phần còn lại của hệ thống dùng
thống nhất.

**Code minh họa** (`app/Services/Shipping/ShippingAdapters.php`):

```php
interface ShippingProvider
{
    public function getName(): string;
    public function getFee(int $orderTotal): int;
}

class GhnShippingAdapter implements ShippingProvider
{
    public function __construct(private GhnApiClient $client) {}

    public function getFee(int $orderTotal): int
    {
        $result = $this->client->calculateFee(['order_total' => $orderTotal]);
        return (int) $result['fee'];
    }
}

class GhtkShippingAdapter implements ShippingProvider
{
    public function __construct(private GhtkApiSdk $client) {}

    public function getFee(int $orderTotal): int
    {
        return (int) $this->client->estimateShippingCost((float) $orderTotal);
    }
}
```

**Sơ đồ UML**: [`docs/uml/18_adapter_shipping.puml`](uml/18_adapter_shipping.puml)

---

## 4. Lựa chọn phương thức vận chuyển (Tiêu chuẩn / Nhanh / Miễn phí)

**Chức năng**: Khách hàng chọn một trong các hình thức vận chuyển khi
checkout; mỗi hình thức có cách tính phí khác nhau, và đơn hàng đạt
ngưỡng miễn phí ship sẽ tự động được áp dụng freeship.

**Pattern áp dụng**: `Strategy`

**Định nghĩa**: Định nghĩa một họ các thuật toán, đóng gói từng thuật
toán vào một class riêng và cho phép thay đổi thuật toán sử dụng độc
lập với client.

**Lý do chọn**: Cách tính phí ship (tiêu chuẩn, hỏa tốc, miễn phí) là
các "thuật toán" có thể thay đổi theo lựa chọn của người dùng hoặc theo
điều kiện đơn hàng (vượt ngưỡng freeship). Strategy giúp thêm/sửa cách
tính phí mà không ảnh hưởng `CheckoutFacade`.

**Code minh họa** (`app/Services/Shipping/ShippingFeeStrategy.php`):

```php
interface ShippingFeeStrategy
{
    public function code(): string;
    public function label(): string;
    public function calculate(int $subtotal): int;
}

class ExpressShippingStrategy implements ShippingFeeStrategy
{
    public function calculate(int $subtotal): int
    {
        return (new GhnShippingAdapter(new GhnApiClient()))->getFee($subtotal) + 15000;
    }
}

class ShippingFeeCalculator
{
    public static function resolve(string $code, int $subtotal): ShippingFeeStrategy
    {
        if ($subtotal >= SiteSettings::getInstance()->freeshipThreshold()) {
            return new FreeShippingStrategy();
        }
        return self::make($code);
    }
}
```

**Sơ đồ UML**: [`docs/uml/19_strategy_shipping_fee.puml`](uml/19_strategy_shipping_fee.puml)

---

## 5. Áp dụng voucher / giảm giá / freeship vào giỏ hàng

**Chức năng**: Tính lại tổng tiền giỏ hàng (tạm tính, phí ship, giảm
giá, tổng cộng) khi khách hàng nhập mã voucher (giảm %, giảm số tiền
cố định, hoặc miễn phí ship).

**Pattern áp dụng**: `Decorator`

**Định nghĩa**: Cho phép gắn thêm hành vi/trách nhiệm mới vào một đối
tượng một cách linh hoạt bằng cách "bọc" đối tượng đó trong một hoặc
nhiều lớp decorator, thay vì kế thừa tĩnh.

**Lý do chọn**: Mỗi loại voucher chỉ ảnh hưởng đến MỘT phần của phép
tính giá (giảm % tổng tiền, trừ số tiền cố định, hoặc miễn phí ship).
Decorator cho phép "bọc" thêm từng loại giảm giá lên trên giá gốc mà
không phải tạo ra tổ hợp class cho từng trường hợp.

**Code minh họa** (`app/Services/Cart/CartPriceDecorator.php`):

```php
interface CartPriceComponent
{
    public function getSubtotal(): int;
    public function getShippingFee(): int;
    public function getDiscount(): int;
    public function getTotal(): int;
}

class BaseCartPrice implements CartPriceComponent
{
    public function __construct(private int $subtotal, private int $shippingFee) {}

    public function getDiscount(): int { return 0; }
    public function getTotal(): int
    {
        return max(0, $this->getSubtotal() + $this->getShippingFee() - $this->getDiscount());
    }
}

class PercentDiscountDecorator extends CartPriceDecorator
{
    public function __construct(CartPriceComponent $inner, private int $percent, private string $voucherCode)
    {
        parent::__construct($inner);
    }

    public function getDiscount(): int
    {
        return (int) round($this->inner->getSubtotal() * $this->percent / 100);
    }
}
```

**Sơ đồ UML**: [`docs/uml/20_decorator_cart_price.puml`](uml/20_decorator_cart_price.puml)

---

## 6. Tạo đơn hàng và chi tiết đơn hàng

**Chức năng**: Khi khách hàng xác nhận đặt hàng, hệ thống cần tạo bản
ghi `Order` (kèm thông tin khách hàng, phương thức thanh toán, vận
chuyển, voucher) và các bản ghi `OrderDetail` tương ứng với từng sản
phẩm trong giỏ hàng.

**Pattern áp dụng**: `Builder`

**Định nghĩa**: Tách việc xây dựng một đối tượng phức tạp khỏi biểu
diễn của nó, cho phép cùng một quá trình xây dựng tạo ra các biểu diễn
khác nhau, thông qua một chuỗi gọi hàm (fluent interface).

**Lý do chọn**: Một đơn hàng có nhiều thông tin cần thiết lập dần
(khách hàng, thanh toán, vận chuyển, voucher, danh sách sản phẩm).
Builder với cú pháp "fluent" (`->forCustomer()->withPaymentMethod()->...->build()`)
giúp code dễ đọc, dễ mở rộng thêm trường mới mà không phá vỡ các nơi
gọi cũ.

**Code minh họa** (`app/Services/Order/OrderBuilder.php`):

```php
class OrderBuilder
{
    public static function new(): self { return new self(); }

    public function forCustomer(?int $customerId): self
    {
        $this->customerId = $customerId;
        return $this;
    }

    public function withPaymentMethod(string $method): self
    {
        $this->paymentMethod = $method;
        return $this;
    }

    public function withShipping(string $method, int $fee): self { /* ... */ return $this; }
    public function withVoucher(?string $code, int $discount): self { /* ... */ return $this; }
    public function addItemsFromCart(array $cart): self { /* ... */ return $this; }

    public function build(): Order
    {
        $order = Order::create([/* ... */]);
        foreach ($this->items as $item) {
            OrderDetail::create([/* order_id, product_id, number, price */]);
        }
        return $order;
    }
}
```

**Sơ đồ UML**: [`docs/uml/21_builder_order.puml`](uml/21_builder_order.puml)

---

## 7. Thông báo cho khách hàng sau khi đặt hàng

**Chức năng**: Sau khi đơn hàng được tạo thành công, hệ thống cần gửi
thông báo (email, SMS) xác nhận cho khách hàng. Có thể bổ sung thêm
kênh thông báo khác trong tương lai.

**Pattern áp dụng**: `Observer`

**Định nghĩa**: Định nghĩa quan hệ một-nhiều giữa các đối tượng, sao
cho khi một đối tượng (subject) thay đổi trạng thái, tất cả các đối
tượng phụ thuộc (observer) đều được thông báo và cập nhật tự động.

**Lý do chọn**: Việc gửi email/SMS là các tác vụ "phụ" độc lập với việc
tạo đơn hàng, không nên làm `CheckoutFacade` phình to hoặc phụ thuộc
trực tiếp vào logic gửi thông báo. Dùng Event/Listener của Laravel
(triển khai Observer) cho phép thêm/bớt kênh thông báo chỉ bằng cách
đăng ký thêm listener, không sửa code đặt hàng.

**Code minh họa**:

```php
// app/Events/OrderPlaced.php
class OrderPlaced
{
    public function __construct(public Order $order) {}
}

// app/Listeners/SendOrderEmailNotification.php
class SendOrderEmailNotification
{
    public function handle(OrderPlaced $event): void
    {
        Log::info("[Email] Gửi email xác nhận đơn hàng #{$event->order->id}");
    }
}

// app/Providers/AppServiceProvider.php (boot())
Event::listen(OrderPlaced::class, SendOrderEmailNotification::class);
Event::listen(OrderPlaced::class, SendOrderSmsNotification::class);

// app/Services/CheckoutFacade.php
Event::dispatch(new OrderPlaced($order));
```

**Sơ đồ UML**: [`docs/uml/22_observer_order_placed.puml`](uml/22_observer_order_placed.puml)

---

## 8. Quy trình đặt hàng tổng thể (Checkout)

**Chức năng**: Gộp toàn bộ quy trình thanh toán (tính phí ship, áp
voucher, chọn phương thức thanh toán, tạo đơn hàng, gửi thông báo)
thành một thao tác duy nhất khi khách hàng bấm "Đặt hàng".

**Pattern áp dụng**: `Facade`

**Định nghĩa**: Cung cấp một interface đơn giản, thống nhất cho một tập
hợp các interface phức tạp hơn trong một subsystem, giúp client dễ sử
dụng hơn mà không cần biết chi tiết bên trong.

**Lý do chọn**: Quy trình checkout liên quan đến nhiều pattern/subsystem
khác (Strategy + Adapter để tính ship, Decorator để áp voucher, Factory
Method để tạo payment method, Builder để dựng đơn hàng, Observer để
thông báo). Facade `CheckoutFacade::placeOrder()` gói gọn tất cả, giúp
`CartController::checkout()` chỉ cần gọi một hàm duy nhất.

**Code minh họa** (`app/Services/CheckoutFacade.php`):

```php
class CheckoutFacade
{
    public static function placeOrder(
        array $cart,
        ?int $customerId,
        string $paymentMethodCode,
        string $shippingMethodCode,
        ?string $voucherCode,
    ): array {
        $subtotal = (int) array_sum(array_map(fn ($i) => $i['price'] * $i['number'], $cart));

        // 1. Strategy + Adapter
        $shippingStrategy = ShippingFeeCalculator::resolve($shippingMethodCode, $subtotal);
        $shippingFee      = $shippingStrategy->calculate($subtotal);

        // 2. Decorator
        $priceBreakdown = self::applyVoucher(new BaseCartPrice($subtotal, $shippingFee), $voucherCode);

        // 3. Factory Method
        $paymentMethod = PaymentMethodFactory::make($paymentMethodCode);

        // 4. Builder
        $order = OrderBuilder::new()
            ->forCustomer($customerId)
            ->withPaymentMethod($paymentMethod->code())
            ->withShipping($shippingStrategy->code(), $priceBreakdown->getShippingFee())
            ->withVoucher($voucherCode, $priceBreakdown->getDiscount())
            ->addItemsFromCart($cart)
            ->build();

        // 5. Observer
        Event::dispatch(new OrderPlaced($order));

        return ['order' => $order, 'paymentMethod' => $paymentMethod, 'priceBreakdown' => $priceBreakdown];
    }
}

// app/Http/Controllers/CartController.php
public function checkout(Request $request)
{
    $result = CheckoutFacade::placeOrder(
        cart: $this->cartService->get(),
        customerId: Auth::guard('customer')->id(),
        paymentMethodCode: $request->input('payment_method', 'cod'),
        shippingMethodCode: $request->input('shipping_method', 'standard'),
        voucherCode: $request->input('voucher_code'),
    );
    // ...
}
```

**Sơ đồ UML**: [`docs/uml/23_facade_checkout.puml`](uml/23_facade_checkout.puml)

---

## Tổng kết - bảng tổng hợp

| # | Chức năng | Pattern | File chính |
|---|---|---|---|
| 1 | Cấu hình chung của shop | Singleton | `app/Support/SiteSettings.php` |
| 2 | Chọn phương thức thanh toán | Factory Method | `app/Services/Payment/PaymentMethodFactory.php` |
| 3 | Tính phí ship từ GHN/GHTK | Adapter | `app/Services/Shipping/ShippingAdapters.php` |
| 4 | Lựa chọn hình thức vận chuyển | Strategy | `app/Services/Shipping/ShippingFeeStrategy.php` |
| 5 | Áp voucher/giảm giá vào giỏ hàng | Decorator | `app/Services/Cart/CartPriceDecorator.php` |
| 6 | Tạo đơn hàng & chi tiết đơn hàng | Builder | `app/Services/Order/OrderBuilder.php` |
| 7 | Thông báo sau khi đặt hàng | Observer | `app/Events/OrderPlaced.php`, `app/Listeners/*` |
| 8 | Quy trình checkout tổng thể | Facade | `app/Services/CheckoutFacade.php` |
