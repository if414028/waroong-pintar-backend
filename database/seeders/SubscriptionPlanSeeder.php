<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        SubscriptionPlan::updateOrCreate(
            ['code' => 'basic'],
            [
                'name' => 'Basic',
                'price' => 99000,
                'billing_cycle' => 'monthly',
                'max_stores' => 1,
                'max_users' => 3,
                'max_products' => 1000,
                'can_access_advanced_reports' => false,
                'can_export_reports' => true,
                'can_use_cashier_shift' => false,
                'can_use_purchase_order' => false,
                'can_use_supplier_management' => false,
                'can_access_api' => false,
                'status' => 'active',
            ]
        );

        SubscriptionPlan::updateOrCreate(
            ['code' => 'pro'],
            [
                'name' => 'Pro',
                'price' => 299000,
                'billing_cycle' => 'monthly',
                'max_stores' => 5,
                'max_users' => 15,
                'max_products' => null,
                'can_access_advanced_reports' => true,
                'can_export_reports' => true,
                'can_use_cashier_shift' => true,
                'can_use_purchase_order' => true,
                'can_use_supplier_management' => true,
                'can_access_api' => true,
                'status' => 'active',
            ]
        );

        SubscriptionPlan::updateOrCreate(
            ['code' => 'enterprise'],
            [
                'name' => 'Enterprise',
                'price' => 0,
                'billing_cycle' => 'custom',
                'max_stores' => null,
                'max_users' => null,
                'max_products' => null,
                'can_access_advanced_reports' => true,
                'can_export_reports' => true,
                'can_use_cashier_shift' => true,
                'can_use_purchase_order' => true,
                'can_use_supplier_management' => true,
                'can_access_api' => true,
                'status' => 'active',
            ]
        );
    }
}