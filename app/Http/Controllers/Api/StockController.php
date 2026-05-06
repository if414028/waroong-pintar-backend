<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $store = $request->attributes->get('active_store');

        $stocks = Stock::query()
            ->with(['product.category'])
            ->where('store_id', $store->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $stocks,
        ]);
    }

    public function show(Request $request, Product $product)
    {
        $store = $request->attributes->get('active_store');

        $stock = Stock::query()
            ->with(['product.category'])
            ->where('store_id', $store->id)
            ->where('product_id', $product->id)
            ->first();

        if (!$stock) {
            return response()->json([
                'success' => false,
                'message' => 'Stock product tidak ditemukan di store ini.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $stock,
        ]);
    }

    public function adjustment(Request $request)
    {
        $store = $request->attributes->get('active_store');

        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type' => ['required', 'in:in,out,adjustment'],
            'qty' => ['required', 'numeric', 'min:0.01'],
            'notes' => ['nullable', 'string'],
        ]);

        $result = DB::transaction(function () use ($request, $store, $data) {
            $product = Product::query()
                ->whereHas('stores', function ($query) use ($store) {
                    $query->where('stores.id', $store->id);
                })
                ->where('id', $data['product_id'])
                ->first();

            if (!$product) {
                abort(response()->json([
                    'success' => false,
                    'message' => 'Product tidak tersedia di store ini.',
                ], 404));
            }

            $stock = Stock::query()
                ->where('store_id', $store->id)
                ->where('product_id', $product->id)
                ->lockForUpdate()
                ->first();

            if (!$stock) {
                $stock = Stock::create([
                    'store_id' => $store->id,
                    'product_id' => $product->id,
                    'qty_on_hand' => 0,
                    'minimum_stock' => 0,
                ]);
            }

            $qtyBefore = (float) $stock->qty_on_hand;
            $qty = (float) $data['qty'];

            if ($data['type'] === 'in') {
                $qtyAfter = $qtyBefore + $qty;
            } elseif ($data['type'] === 'out') {
                if ($qtyBefore < $qty) {
                    abort(response()->json([
                        'success' => false,
                        'message' => 'Stock tidak mencukupi.',
                    ], 422));
                }

                $qtyAfter = $qtyBefore - $qty;
            } else {
                // adjustment = set stok final sesuai qty yang dikirim
                $qtyAfter = $qty;
                $qty = abs($qtyAfter - $qtyBefore);
            }

            $stock->update([
                'qty_on_hand' => $qtyAfter,
            ]);

            $movement = StockMovement::create([
                'store_id' => $store->id,
                'product_id' => $product->id,
                'type' => $data['type'],
                'reference_type' => 'manual_adjustment',
                'reference_id' => null,
                'qty' => $qty,
                'qty_before' => $qtyBefore,
                'qty_after' => $qtyAfter,
                'notes' => $data['notes'] ?? null,
                'created_by' => $request->user()->id,
            ]);

            return [
                'stock' => $stock->fresh()->load('product'),
                'movement' => $movement->load(['product', 'user']),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Stock berhasil disesuaikan.',
            'data' => $result,
        ]);
    }
}