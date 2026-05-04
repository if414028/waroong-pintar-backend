<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\Store|null $store
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoreProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoreProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoreProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoreProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoreProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoreProduct whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StoreProduct extends Model
{
    protected $fillable = [
        'store_id',
        'product_id',
        'selling_price',
        'is_active'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
