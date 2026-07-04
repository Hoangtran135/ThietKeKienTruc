<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_method', 20)->default('standard')->after('payment_status');
            $table->unsignedInteger('shipping_fee')->default(0)->after('shipping_method');
            $table->string('voucher_code', 30)->nullable()->after('shipping_fee');
            $table->unsignedInteger('discount_amount')->default(0)->after('voucher_code');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_method', 'shipping_fee', 'voucher_code', 'discount_amount']);
        });
    }
};
