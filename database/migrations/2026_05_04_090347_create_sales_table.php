<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->foreignId('store_id')
                ->constrained('stores')
                ->cascadeOnDelete();

            $table->foreignId('cashier_user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('invoice_number')->unique();
            $table->string('customer_name')->nullable();

            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('change_amount', 15, 2)->default(0);

            $table->string('status')->default('completed'); // completed, voided
            $table->timestamp('sold_at');

            $table->timestamps();

            $table->index(['store_id', 'sold_at']);
            $table->index('cashier_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};