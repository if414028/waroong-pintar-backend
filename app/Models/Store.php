<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $owner_user_id
 * @property string $name
 * @property string $code
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $city
 * @property string|null $province
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sale> $sales
 * @property-read int|null $sales_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Stock> $stocks
 * @property-read int|null $stocks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereOwnerUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Store extends Model
{
    protected $fillable = [
        'name',
        'code',
        'owner_user_id',
        'phone',
        'address',
        'city',
        'province',
        'status'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'store_users')
            ->withPivot('role_in_store')
            ->withTimestamps();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'store_products')
            ->withPivot('selling_price', 'is_active')
            ->withTimestamps();
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}