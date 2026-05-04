<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $store_id
 * @property int $user_id
 * @property string $role_in_store
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Store $store
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoreUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoreUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoreUser query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoreUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoreUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoreUser whereRoleInStore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoreUser whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoreUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoreUser whereUserId($value)
 * @mixin \Eloquent
 */
class StoreUser extends Model
{
    protected $fillable = ['store_id', 'user_id', 'role_in_store'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
