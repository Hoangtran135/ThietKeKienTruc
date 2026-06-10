<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'Nguyễn Văn An', 'email' => 'an.nguyen@gmail.com', 'phone' => '0901234567', 'address' => '123 Phố Huế, Hai Bà Trưng, Hà Nội'],
            ['name' => 'Trần Thị Bình', 'email' => 'binh.tran@gmail.com', 'phone' => '0912345678', 'address' => '45 Hàng Bài, Hoàn Kiếm, Hà Nội'],
            ['name' => 'Lê Minh Cường', 'email' => 'cuong.le@gmail.com', 'phone' => '0923456789', 'address' => '78 Trần Phú, Hà Đông, Hà Nội'],
            ['name' => 'Phạm Thị Dung', 'email' => 'dung.pham@gmail.com', 'phone' => '0934567890', 'address' => '12 Hoàng Diệu, Hải Châu, Đà Nẵng'],
            ['name' => 'Hoàng Văn Em', 'email' => 'em.hoang@gmail.com', 'phone' => '0945678901', 'address' => '56 Phan Chu Trinh, Hoàn Kiếm, Hà Nội'],
        ];

        foreach ($customers as $data) {
            Customer::create(array_merge($data, ['password' => Hash::make('password123')]));
        }
    }
}
