<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $sale_id
 * @property string $payment_method
 * @property numeric $amount
 * @property string|null $reference_number
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Sale $sale
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 */
	class Payment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $category_id
 * @property string $sku
 * @property string|null $barcode
 * @property string $name
 * @property string $unit
 * @property numeric $purchase_price
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ProductCategory|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SaleItem> $saleItems
 * @property-read int|null $sale_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Stock> $stocks
 * @property-read int|null $stocks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Store> $stores
 * @property-read int|null $stores_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePurchasePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereUpdatedAt($value)
 */
	class ProductCategory extends \Eloquent {}
}

namespace App\Models{
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
 */
	class Sale extends \Eloquent {}
}

namespace App\Models{
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
 */
	class SaleItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $store_id
 * @property int $product_id
 * @property numeric $qty_on_hand
 * @property numeric $minimum_stock
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Store $store
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereMinimumStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereQtyOnHand($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereUpdatedAt($value)
 */
	class Stock extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $store_id
 * @property int $product_id
 * @property string $type
 * @property string|null $reference_type
 * @property int|null $reference_id
 * @property numeric $qty
 * @property numeric $qty_before
 * @property numeric $qty_after
 * @property string|null $notes
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Store $store
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereQtyAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereQtyBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereReferenceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereUpdatedAt($value)
 */
	class StockMovement extends \Eloquent {}
}

namespace App\Models{
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
 */
	class Store extends \Eloquent {}
}

namespace App\Models{
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
 */
	class StoreProduct extends \Eloquent {}
}

namespace App\Models{
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
 */
	class StoreUser extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $last_login_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Store> $ownedStores
 * @property-read int|null $owned_stores_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sale> $sales
 * @property-read int|null $sales_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Store> $stores
 * @property-read int|null $stores_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, ?string $guard = null, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, ?string $guard = null)
 */
	class User extends \Eloquent {}
}

