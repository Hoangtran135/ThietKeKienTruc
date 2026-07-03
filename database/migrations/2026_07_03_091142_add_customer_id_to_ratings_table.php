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
        Schema::table('ratings', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable()->after('product_id');
            $table->string('review', 500)->nullable()->after('star');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->unique(['product_id', 'customer_id']);
        });
    }

    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropUnique(['product_id', 'customer_id']);
            $table->dropColumn(['customer_id', 'review']);
        });
    }
};
