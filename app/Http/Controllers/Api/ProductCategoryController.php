<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::query()
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:product_categories,name'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $category = ProductCategory::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Product category berhasil dibuat.',
            'data' => $category,
        ], 201);
    }

    public function show(ProductCategory $productCategory)
    {
        return response()->json([
            'success' => true,
            'data' => $productCategory,
        ]);
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255', 'unique:product_categories,name,' . $productCategory->id],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $productCategory->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Product category berhasil diupdate.',
            'data' => $productCategory->fresh(),
        ]);
    }

    public function destroy(ProductCategory $productCategory)
    {
        $hasProducts = $productCategory->products()->exists();

        if ($hasProducts) {
            return response()->json([
                'success' => false,
                'message' => 'Category tidak bisa dihapus karena masih digunakan oleh product.',
            ], 422);
        }

        $productCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product category berhasil dihapus.',
        ]);
    }
}