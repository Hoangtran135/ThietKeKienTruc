<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $roots = [
            ['name' => 'Điện thoại',     'displayhomepage' => 1],
            ['name' => 'Laptop',          'displayhomepage' => 1],
            ['name' => 'Tivi',            'displayhomepage' => 1],
            ['name' => 'Máy tính bảng',  'displayhomepage' => 1],
            ['name' => 'Phụ kiện',        'displayhomepage' => 0],
        ];

        foreach ($roots as $root) {
            Category::firstOrCreate(
                ['name' => $root['name'], 'parent_id' => 0],
                ['displayhomepage' => $root['displayhomepage']]
            );
        }

        $phone  = Category::where('name', 'Điện thoại')->first();
        $laptop = Category::where('name', 'Laptop')->first();
        $phukien = Category::where('name', 'Phụ kiện')->first();

        $subs = [
            ['name' => 'Samsung',   'parent_id' => $phone->id],
            ['name' => 'iPhone',    'parent_id' => $phone->id],
            ['name' => 'Xiaomi',    'parent_id' => $phone->id],
            ['name' => 'Dell',      'parent_id' => $laptop->id],
            ['name' => 'Asus',      'parent_id' => $laptop->id],
            ['name' => 'MacBook',   'parent_id' => $laptop->id],
            ['name' => 'Tai nghe',  'parent_id' => $phukien->id],
            ['name' => 'Sạc dự phòng', 'parent_id' => $phukien->id],
        ];

        foreach ($subs as $sub) {
            Category::firstOrCreate(
                ['name' => $sub['name'], 'parent_id' => $sub['parent_id']],
                ['displayhomepage' => 0]
            );
        }
    }
}
