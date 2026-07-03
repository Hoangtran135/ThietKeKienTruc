<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $vouchers = [
            ['code' => 'GIAM10',   'type' => 'percent',  'value' => 10,    'min_order' => 0,      'max_discount' => 100000, 'is_active' => true],
            ['code' => 'GIAM50K',  'type' => 'fixed',    'value' => 50000, 'min_order' => 200000, 'max_discount' => null,   'is_active' => true],
            ['code' => 'FREESHIP', 'type' => 'freeship', 'value' => 0,     'min_order' => 0,      'max_discount' => null,   'is_active' => true],
            ['code' => 'SALE20',   'type' => 'percent',  'value' => 20,    'min_order' => 500000, 'max_discount' => 200000, 'is_active' => true, 'usage_limit' => 50],
        ];

        foreach ($vouchers as $v) {
            Voucher::firstOrCreate(['code' => $v['code']], $v);
        }
    }
}
