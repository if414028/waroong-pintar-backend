<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreProductController extends Controller
{
    public function attach(Request $request)
    {
        $store = $request->attributes->get('active_store');

        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'initial_stock' => ['nullable', 'numeric', 'min:0'],
            'minimum_stock' => ['nullable', 'numeric', 'min:0'],
        ]);

        $product = Product::query()
            ->where('id', $data['product_id'])
            ->where('status', 'active')
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product tidak ditemukan atau tidak aktif.',
            ], 404);
        }

        $alreadyAttached = $product->stores()
            ->where('stores.id', $store->id)
            ->exists();

        if ($alreadyAttached) {
            return response()->json([
                'success' => false,
                'message' => 'Product sudah terdaftar di store ini.',
            ], 422);
        }

        DB::transaction(function () use ($store, $product, $data) {
            $product->stores()->attach($store->id, [
                'selling_price' => $data['selling_price'],
                'is_active' => true,
            ]);

            Stock::query()->updateOrCreate(
                [
                    'store_id' => $store->id,
                    'product_id' => $product->id,
                ],
                [
                    'qty_on_hand' => $data['initial_stock'] ?? 0,
                    'minimum_stock' => $data['minimum_stock'] ?? 0,
                ]
            );
        });

        return response()->json([
            'success' => true,
            'message' => 'Product berhasil di-attach ke store.',
            'data' => $product->load([
                'category',
                'stores' => function ($query) use ($store) {
                    $query->where('stores.id', $store->id)
                        ->withPivot('selling_price', 'is_active');
                },
                'stocks' => function ($query) use ($store) {
                    $query->where('store_id', $store->id);
                },
            ]),
        ]);
    }

    public function detach(Request $request, Product $product)
    {
        $store = $request->attributes->get('active_store');

        $attached = $product->stores()
            ->where('stores.id', $store->id)
            ->exists();

        if (!$attached) {
            return response()->json([
                'success' => false,
                'message' => 'Product tidak terdaftar di store ini.',
            ], 404);
        }

        $product->stores()->updateExistingPivot($store->id, [
            'is_active' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product berhasil dinonaktifkan dari store ini.',
        ]);
    }

    public function activate(Request $request, Product $product)
    {
        $store = $request->attributes->get('active_store');

        $attached = $product->stores()
            ->where('stores.id', $store->id)
            ->exists();

        if (!$attached) {
            return response()->json([
                'success' => false,
                'message' => 'Product belum terdaftar di store ini.',
            ], 404);
        }

        $product->stores()->updateExistingPivot($store->id, [
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product berhasil diaktifkan kembali di store ini.',
        ]);
    }
}