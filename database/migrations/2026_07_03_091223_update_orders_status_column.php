<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 0=pending, 1=confirmed, 2=shipping, 3=delivered, 4=cancelled
        Schema::table('orders', function (Blueprint $table) {
            $table->string('note', 500)->nullable()->after('discount_amount');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('note');
        });
    }
};
