<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('store_id')
                ->constrained('stores')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->decimal('selling_price', 15, 2);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['store_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_products');
    }
};