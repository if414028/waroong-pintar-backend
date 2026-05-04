<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('store_id')
                ->constrained('stores')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->restrictOnDelete();

            $table->string('type'); // in, out, adjustment, sale, refund
            $table->string('reference_type')->nullable(); // sale, manual_adjustment, etc
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->decimal('qty', 15, 2);
            $table->decimal('qty_before', 15, 2);
            $table->decimal('qty_after', 15, 2);

            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['store_id', 'product_id']);
            $table->index(['reference_type', 'reference_id']);
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};