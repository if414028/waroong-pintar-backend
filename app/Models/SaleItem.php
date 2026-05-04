<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $sale_id
 * @property int $product_id
 * @property string $product_name
 * @property string|null $sku
 * @property numeric $unit_price
 * @property numeric $qty
 * @property numeric $discount_amount
 * @property numeric $line_total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Sale $sale
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereLineTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SaleItem extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'product_name',
        'sku',
        'unit_price',
        'qty',
        'discount_amount',
        'line_total'
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
