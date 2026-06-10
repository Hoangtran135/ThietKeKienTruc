<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 500);
            $table->string('description', 4000)->nullable();
            $table->longText('content')->nullable();
            $table->tinyInteger('hot')->default(0);
            $table->string('photo')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->integer('discount')->default(0);
            $table->unsignedBigInteger('category_id')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
