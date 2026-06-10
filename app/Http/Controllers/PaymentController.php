<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Payment\PaymentMethodFactory;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function show(int $id)
    {
        $order = Order::with('details.product')
            ->where('customer_id', Auth::guard('customer')->id())
            ->findOrFail($id);

        if ($order->payment_status === 1) {
            return redirect()->route('home')->with('success', 'Đơn hàng đã được thanh toán!');
        }

        $paymentMethod = PaymentMethodFactory::make($order->payment_method);

        if (!$paymentMethod->requiresQrPayment()) {
            return redirect()->route('home');
        }

        $qrUrl = $paymentMethod->buildQrUrl($order);

        return view('frontend.payment', compact('order', 'qrUrl'));
    }

    public function confirm(int $id)
    {
        $order = Order::where('customer_id', Auth::guard('customer')->id())->findOrFail($id);

        $order->update(['payment_status' => 1]);

        return redirect()->route('home')->with('success', 'Thanh toán thành công! Cảm ơn bạn đã mua hàng.');
    }
}
