<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use App\Services\OrderService;

class AdminOrderController extends Controller
{
    public function __construct(
        private OrderRepository $orderRepo,
        private OrderService $orderService,
    ) {}

    public function index()
    {
        $orders = $this->orderRepo->allWithCustomer();

        return view('admin.orders.index', compact('orders'));
    }

    public function detail(int $id)
    {
        $order = $this->orderRepo->findWithDetails($id);

        return view('admin.orders.detail', compact('order'));
    }

    public function deliver(int $id)
    {
        $this->orderService->deliver($id);

        return redirect()->route('admin.orders.index')->with('success', 'Đã xác nhận giao hàng!');
    }
}
