<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalProducts  = Product::count();
        $totalOrders    = Order::count();
        $pendingOrders  = Order::where('status', 0)->count();
        $totalCustomers = Customer::count();
        $recentOrders   = Order::with('customer')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalProducts', 'totalOrders', 'pendingOrders', 'totalCustomers', 'recentOrders'
        ));
    }
}
