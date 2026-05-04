<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $store_id
 * @property int $cashier_user_id
 * @property string $invoice_number
 * @property string|null $customer_name
 * @property numeric $subtotal
 * @property numeric $discount_amount
 * @property numeric $tax_amount
 * @property numeric $grand_total
 * @property numeric $paid_amount
 * @property numeric $change_amount
 * @property string $status
 * @property \Illuminate\Support\Carbon $sold_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $cashier
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SaleItem> $items
 * @property-read int|null $items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\Store $store
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCashierUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereChangeAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereInvoiceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale wherePaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereSoldAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Sale extends Model
{
    protected $fillable = [
        'store_id',
        'cashier_user_id',
        'invoice_number',
        'customer_name',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'grand_total',
        'paid_amount',
        'change_amount',
        'status',
        'sold_at'
    ];

    protected $casts = [
        'sold_at' => 'datetime',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_user_id');
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}