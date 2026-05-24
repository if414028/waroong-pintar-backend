<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'code',
        'price',
        'billing_cycle',
        'max_stores',
        'max_users',
        'max_products',
        'can_access_advanced_reports',
        'can_export_reports',
        'can_use_cashier_shift',
        'can_use_purchase_order',
        'can_use_supplier_management',
        'can_access_api',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'can_access_advanced_reports' => 'boolean',
        'can_export_reports' => 'boolean',
        'can_use_cashier_shift' => 'boolean',
        'can_use_purchase_order' => 'boolean',
        'can_use_supplier_management' => 'boolean',
        'can_access_api' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(StoreSubscription::class);
    }
}