<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Services\CreateSaleService;
use App\Services\VoidSaleService;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $store = $request->attributes->get('active_store');

        $sales = Sale::query()
            ->with(['cashier', 'items', 'payments'])
            ->where('store_id', $store->id)
            ->latest('sold_at')
            ->paginate($request->integer('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $sales,
        ]);
    }

    public function store(Request $request, CreateSaleService $service)
    {
        $store = $request->attributes->get('active_store');

        $data = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:255'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price' => ['nullable', 'numeric', 'min:0'],
            'items.*.discount_amount' => ['nullable', 'numeric', 'min:0'],

            'payments' => ['required', 'array', 'min:1'],
            'payments.*.payment_method' => ['required', 'string', 'in:cash,qris,transfer,ewallet'],
            'payments.*.amount' => ['required', 'numeric', 'min:0'],
            'payments.*.reference_number' => ['nullable', 'string', 'max:255'],
        ]);

        $sale = $service->handle($data, $store, $request->user());

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dibuat.',
            'data' => $sale,
        ], 201);
    }

    public function show(Request $request, Sale $sale)
    {
        $store = $request->attributes->get('active_store');

        if ($sale->store_id !== $store->id) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan di store ini.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $sale->load(['store', 'cashier', 'items.product', 'payments']),
        ]);
    }

    public function void(Request $request, Sale $sale, VoidSaleService $service)
    {
        $store = $request->attributes->get('active_store');

        $data = $request->validate([
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $sale = $service->handle(
            sale: $sale,
            store: $store,
            user: $request->user(),
            reason: $data['reason'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dibatalkan dan stok dikembalikan.',
            'data' => $sale,
        ]);
    }
}
