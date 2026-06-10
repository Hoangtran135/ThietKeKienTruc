<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        $orders = [
            ['customer' => 0, 'status' => 1, 'items' => [[0, 1], [2, 2]]],
            ['customer' => 1, 'status' => 0, 'items' => [[1, 1]]],
            ['customer' => 2, 'status' => 1, 'items' => [[3, 1], [4, 1], [5, 2]]],
            ['customer' => 0, 'status' => 0, 'items' => [[6, 3]]],
            ['customer' => 3, 'status' => 1, 'items' => [[2, 1], [7, 1]]],
            ['customer' => 4, 'status' => 0, 'items' => [[8, 2], [9, 1]]],
            ['customer' => 1, 'status' => 1, 'items' => [[10, 1]]],
        ];

        foreach ($orders as $orderData) {
            $customer = $customers[$orderData['customer'] % $customers->count()];
            $order = Order::create([
                'customer_id' => $customer->id,
                'status' => $orderData['status'],
            ]);

            foreach ($orderData['items'] as [$productIndex, $qty]) {
                $product = $products[$productIndex % $products->count()];
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'number' => $qty,
                    'price' => $product->final_price,
                ]);
            }
        }
    }
}
