<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $store = $request->attributes->get('active_store');

        $products = Product::query()
            ->with(['category'])
            ->whereHas('stores', function ($query) use ($store) {
                $query->where('stores.id', $store->id);
            })
            ->with([
                'stores' => function ($query) use ($store) {
                    $query->where('stores.id', $store->id)
                        ->withPivot('selling_price', 'is_active');
                },
                'stocks' => function ($query) use ($store) {
                    $query->where('store_id', $store->id);
                }
            ])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $store = $request->attributes->get('active_store');

        $data = $request->validate([
            'category_id' => ['nullable', 'exists:product_categories,id'],
            'sku' => ['required', 'string', 'max:255', 'unique:products,sku'],
            'barcode' => ['nullable', 'string', 'max:255', 'unique:products,barcode'],
            'name' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:50'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['nullable', 'numeric', 'min:0'],
            'initial_stock' => ['nullable', 'numeric', 'min:0'],
        ]);

        $product = DB::transaction(function () use ($data, $store) {
            $product = Product::create([
                'category_id' => $data['category_id'] ?? null,
                'sku' => $data['sku'],
                'barcode' => $data['barcode'] ?? null,
                'name' => $data['name'],
                'unit' => $data['unit'],
                'purchase_price' => $data['purchase_price'] ?? 0,
                'status' => 'active',
            ]);

            $product->stores()->attach($store->id, [
                'selling_price' => $data['selling_price'],
                'is_active' => true,
            ]);

            Stock::create([
                'store_id' => $store->id,
                'product_id' => $product->id,
                'qty_on_hand' => $data['initial_stock'] ?? 0,
                'minimum_stock' => $data['minimum_stock'] ?? 0,
            ]);

            return $product;
        });

        return response()->json([
            'success' => true,
            'message' => 'Product berhasil dibuat.',
            'data' => $product->load(['category', 'stores', 'stocks']),
        ], 201);
    }

    public function show(Request $request, Product $product)
    {
        $store = $request->attributes->get('active_store');

        $hasProduct = $product->stores()
            ->where('stores.id', $store->id)
            ->exists();

        if (!$hasProduct) {
            return response()->json([
                'success' => false,
                'message' => 'Product tidak ditemukan di store ini.',
            ], 404);
        }

        $product->load([
            'category',
            'stores' => function ($query) use ($store) {
                $query->where('stores.id', $store->id)
                    ->withPivot('selling_price', 'is_active');
            },
            'stocks' => function ($query) use ($store) {
                $query->where('store_id', $store->id);
            }
        ]);

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $store = $request->attributes->get('active_store');

        $hasProduct = $product->stores()
            ->where('stores.id', $store->id)
            ->exists();

        if (!$hasProduct) {
            return response()->json([
                'success' => false,
                'message' => 'Product tidak ditemukan di store ini.',
            ], 404);
        }

        $data = $request->validate([
            'category_id' => ['nullable', 'exists:product_categories,id'],
            'sku' => ['sometimes', 'string', 'max:255', 'unique:products,sku,' . $product->id],
            'barcode' => ['nullable', 'string', 'max:255', 'unique:products,barcode,' . $product->id],
            'name' => ['sometimes', 'string', 'max:255'],
            'unit' => ['sometimes', 'string', 'max:50'],
            'purchase_price' => ['sometimes', 'numeric', 'min:0'],
            'selling_price' => ['sometimes', 'numeric', 'min:0'],
            'minimum_stock' => ['sometimes', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'status' => ['sometimes', 'string'],
        ]);

        DB::transaction(function () use ($data, $store, $product) {
            $product->update([
                'category_id' => $data['category_id'] ?? $product->category_id,
                'sku' => $data['sku'] ?? $product->sku,
                'barcode' => $data['barcode'] ?? $product->barcode,
                'name' => $data['name'] ?? $product->name,
                'unit' => $data['unit'] ?? $product->unit,
                'purchase_price' => $data['purchase_price'] ?? $product->purchase_price,
                'status' => $data['status'] ?? $product->status,
            ]);

            if (array_key_exists('selling_price', $data) || array_key_exists('is_active', $data)) {
                $product->stores()->updateExistingPivot($store->id, [
                    'selling_price' => $data['selling_price'] ?? $product->stores()
                        ->where('stores.id', $store->id)
                        ->first()
                        ->pivot
                        ->selling_price,
                    'is_active' => $data['is_active'] ?? $product->stores()
                        ->where('stores.id', $store->id)
                        ->first()
                        ->pivot
                        ->is_active,
                ]);
            }

            Stock::query()
                ->where('store_id', $store->id)
                ->where('product_id', $product->id)
                ->update([
                    'minimum_stock' => $data['minimum_stock'],
                ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Product berhasil diupdate.',
            'data' => $product->fresh()->load(['category']),
        ]);
    }

    public function destroy(Request $request, Product $product)
    {
        $store = $request->attributes->get('active_store');

        $hasProduct = $product->stores()
            ->where('stores.id', $store->id)
            ->exists();

        if (!$hasProduct) {
            return response()->json([
                'success' => false,
                'message' => 'Product tidak ditemukan di store ini.',
            ], 404);
        }

        DB::transaction(function () use ($store, $product) {
            $product->stores()->updateExistingPivot($store->id, [
                'is_active' => false,
            ]);

            $product->update([
                'status' => 'inactive',
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Product berhasil dinonaktifkan.',
        ]);
    }
}
