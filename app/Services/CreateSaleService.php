<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateSaleService
{
    public function handle(array $data, $store, $user): Sale
    {
        return DB::transaction(function () use ($data, $store, $user) {
            $subtotal = 0;
            $itemsPayload = [];

            foreach ($data['items'] as $item) {
                $product = Product::query()
                    ->where('products.id', $item['product_id'])
                    ->where('products.status', 'active')
                    ->whereHas('stores', function ($query) use ($store) {
                        $query->where('stores.id', $store->id)
                            ->where('store_products.is_active', true);
                    })
                    ->with([
                        'stores' => function ($query) use ($store) {
                            $query->where('stores.id', $store->id)
                                ->withPivot('selling_price', 'is_active');
                        }
                    ])
                    ->first();

                if (!$product) {
                    throw ValidationException::withMessages([
                        'items' => ['Product tidak tersedia atau tidak aktif di store ini.'],
                    ]);
                }

                $stock = Stock::query()
                    ->where('store_id', $store->id)
                    ->where('product_id', $product->id)
                    ->lockForUpdate()
                    ->first();

                if (!$stock || $stock->qty_on_hand < $item['qty']) {
                    throw ValidationException::withMessages([
                        'items' => ["Stock product {$product->name} tidak mencukupi."],
                    ]);
                }

                $unitPrice = $item['unit_price'] ?? $product->stores->first()->pivot->selling_price;
                $discountAmount = $item['discount_amount'] ?? 0;
                $lineTotal = ($unitPrice * $item['qty']) - $discountAmount;

                if ($lineTotal < 0) {
                    throw ValidationException::withMessages([
                        'items' => ["Line total product {$product->name} tidak valid."],
                    ]);
                }

                $subtotal += $lineTotal;

                $itemsPayload[] = [
                    'product' => $product,
                    'stock' => $stock,
                    'qty' => $item['qty'],
                    'unit_price' => $unitPrice,
                    'discount_amount' => $discountAmount,
                    'line_total' => $lineTotal,
                ];
            }

            $discountAmount = $data['discount_amount'] ?? 0;
            $taxAmount = $data['tax_amount'] ?? 0;
            $grandTotal = $subtotal - $discountAmount + $taxAmount;

            if ($grandTotal < 0) {
                throw ValidationException::withMessages([
                    'grand_total' => ['Grand total tidak valid.'],
                ]);
            }

            $paidAmount = collect($data['payments'])->sum('amount');

            if ($paidAmount < $grandTotal) {
                throw ValidationException::withMessages([
                    'payments' => ['Jumlah pembayaran kurang dari grand total.'],
                ]);
            }

            $sale = Sale::create([
                'store_id' => $store->id,
                'cashier_user_id' => $user->id,
                'invoice_number' => $this->generateInvoiceNumber($store->id),
                'customer_name' => $data['customer_name'] ?? null,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'grand_total' => $grandTotal,
                'paid_amount' => $paidAmount,
                'change_amount' => $paidAmount - $grandTotal,
                'status' => $paidAmount >= $grandTotal
                    ? 'paid'
                    : ($paidAmount > 0 ? 'partially_paid' : 'unpaid'),
                'sold_at' => now(),
            ]);

            foreach ($itemsPayload as $itemPayload) {
                $product = $itemPayload['product'];
                $stock = $itemPayload['stock'];
                $qtyBefore = $stock->qty_on_hand;
                $qtyAfter = $qtyBefore - $itemPayload['qty'];

                $sale->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'unit_price' => $itemPayload['unit_price'],
                    'qty' => $itemPayload['qty'],
                    'discount_amount' => $itemPayload['discount_amount'],
                    'line_total' => $itemPayload['line_total'],
                ]);

                $stock->update([
                    'qty_on_hand' => $qtyAfter,
                ]);

                StockMovement::create([
                    'store_id' => $store->id,
                    'product_id' => $product->id,
                    'type' => 'sale',
                    'reference_type' => 'sale',
                    'reference_id' => $sale->id,
                    'qty' => $itemPayload['qty'],
                    'qty_before' => $qtyBefore,
                    'qty_after' => $qtyAfter,
                    'notes' => 'POS sale: ' . $sale->invoice_number,
                    'created_by' => $user->id,
                ]);
            }

            foreach ($data['payments'] as $payment) {
                $sale->payments()->create([
                    'payment_method' => $payment['payment_method'],
                    'amount' => $payment['amount'],
                    'reference_number' => $payment['reference_number'] ?? null,
                    'paid_at' => now(),
                ]);
            }

            return $sale->load(['store', 'cashier', 'items.product', 'payments']);
        });
    }

    private function generateInvoiceNumber(int $storeId): string
    {
        return 'INV-' . $storeId . '-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);
    }
}