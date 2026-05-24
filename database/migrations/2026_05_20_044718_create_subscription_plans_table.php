<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('code')->unique();

            $table->decimal('price', 15, 2)->default(0);

            $table->string('billing_cycle')
                ->default('monthly');

            $table->unsignedInteger('max_stores')
                ->nullable();

            $table->unsignedInteger('max_users')
                ->nullable();

            $table->unsignedInteger('max_products')
                ->nullable();

            $table->boolean('can_access_advanced_reports')
                ->default(false);

            $table->boolean('can_export_reports')
                ->default(false);

            $table->boolean('can_use_cashier_shift')
                ->default(false);

            $table->boolean('can_use_purchase_order')
                ->default(false);

            $table->boolean('can_use_supplier_management')
                ->default(false);

            $table->boolean('can_access_api')
                ->default(false);

            $table->string('status')
                ->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};