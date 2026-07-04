<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function __construct(
        private OrderRepository $orderRepo,
        private OrderService $orderService,
    ) {}

    public function index(Request $request)
    {
        $status = $request->get('status', '');
        $orders = $this->orderRepo->allWithCustomer($status !== '' ? (int) $status : null);

        return view('admin.orders.index', compact('orders', 'status'));
    }

    public function detail(int $id)
    {
        $order = $this->orderRepo->findWithDetails($id);

        return view('admin.orders.detail', compact('order'));
    }

    public function deliver(int $id)
    {
        $this->orderService->updateStatus($id, Order::STATUS_DELIVERED);

        return redirect()->route('admin.orders.index')->with('success', 'Đã xác nhận giao hàng!');
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate(['status' => 'required|integer|in:0,1,2,3,4']);
        $this->orderService->updateStatus($id, (int) $request->status);

        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng.');
    }

    public function exportCsv()
    {
        $orders = Order::with(['customer', 'details.product'])->latest()->get();

        $filename = 'orders_'.now()->format('Ymd_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Mã ĐH', 'Khách hàng', 'Email', 'Trạng thái', 'Thanh toán', 'Tổng tiền', 'Ngày đặt']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    '#'.$order->id,
                    $order->customer->name ?? 'Khách vãng lai',
                    $order->customer->email ?? '',
                    $order->status_label,
                    $order->payment_method_label,
                    number_format($order->total),
                    $order->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
