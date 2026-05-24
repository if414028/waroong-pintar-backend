<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSubscription extends Model
{
    protected $fillable = [
        'store_id',
        'subscription_plan_id',
        'status',
        'starts_at',
        'ends_at',
        'cancelled_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active'
            && (!$this->ends_at || $this->ends_at->isFuture());
    }
}