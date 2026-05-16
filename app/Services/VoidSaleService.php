<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VoidSaleService
{
    public function handle(Sale $sale, $store, $user, ?string $reason = null): Sale
    {
        return DB::transaction(function () use ($sale, $store, $user, $reason) {
            $sale = Sale::query()
                ->where('id', $sale->id)
                ->where('store_id', $store->id)
                ->lockForUpdate()
                ->first();

            if (!$sale) {
                throw ValidationException::withMessages([
                    'sale' => ['Transaksi tidak ditemukan di store ini.'],
                ]);
            }

            if ($sale->status === 'voided') {
                throw ValidationException::withMessages([
                    'sale' => ['Transaksi sudah pernah dibatalkan.'],
                ]);
            }

            $sale->load('items');

            foreach ($sale->items as $item) {
                $stock = Stock::query()
                    ->where('store_id', $store->id)
                    ->where('product_id', $item->product_id)
                    ->lockForUpdate()
                    ->first();

                if (!$stock) {
                    $stock = Stock::create([
                        'store_id' => $store->id,
                        'product_id' => $item->product_id,
                        'qty_on_hand' => 0,
                        'minimum_stock' => 0,
                    ]);
                }

                $qtyBefore = (float) $stock->qty_on_hand;
                $qtyAfter = $qtyBefore + (float) $item->qty;

                $stock->update([
                    'qty_on_hand' => $qtyAfter,
                ]);

                StockMovement::create([
                    'store_id' => $store->id,
                    'product_id' => $item->product_id,
                    'type' => 'refund',
                    'reference_type' => 'sale_void',
                    'reference_id' => $sale->id,
                    'qty' => $item->qty,
                    'qty_before' => $qtyBefore,
                    'qty_after' => $qtyAfter,
                    'notes' => $reason ?: 'Void sale: ' . $sale->invoice_number,
                    'created_by' => $user->id,
                ]);
            }

            $sale->update([
                'status' => 'voided',
            ]);

            return $sale->fresh()->load(['store', 'cashier', 'items.product', 'payments']);
        });
    }
}