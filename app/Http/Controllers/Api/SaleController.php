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

    public function receipt(Request $request, Sale $sale)
    {
        $store = $request->attributes->get('active_store');

        if ($sale->store_id !== $store->id) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan di store ini.',
            ], 404);
        }

        $sale->load([
            'store',
            'cashier',
            'items.product',
            'payments',
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'store' => [
                    'name' => $sale->store->name,
                    'phone' => $sale->store->phone,
                    'address' => $sale->store->address,
                    'city' => $sale->store->city,
                    'province' => $sale->store->province,
                ],

                'receipt' => [
                    'invoice_number' => $sale->invoice_number,
                    'sold_at' => $sale->sold_at,
                    'cashier' => $sale->cashier?->name,
                    'customer_name' => $sale->customer_name,
                    'status' => $sale->status,
                ],

                'items' => $sale->items->map(function ($item) {
                    return [
                        'product_name' => $item->product_name,
                        'qty' => (float) $item->qty,
                        'unit_price' => (float) $item->unit_price,
                        'discount_amount' => (float) $item->discount_amount,
                        'line_total' => (float) $item->line_total,
                    ];
                }),

                'summary' => [
                    'subtotal' => (float) $sale->subtotal,
                    'discount_amount' => (float) $sale->discount_amount,
                    'tax_amount' => (float) $sale->tax_amount,
                    'grand_total' => (float) $sale->grand_total,
                    'paid_amount' => (float) $sale->paid_amount,
                    'change_amount' => (float) $sale->change_amount,
                ],

                'payments' => $sale->payments->map(function ($payment) {
                    return [
                        'payment_method' => $payment->payment_method,
                        'amount' => (float) $payment->amount,
                        'reference_number' => $payment->reference_number,
                        'paid_at' => $payment->paid_at,
                    ];
                }),
            ],
        ]);
    }

    public function updatePaymentStatus(Request $request, Sale $sale)
    {
        $store = $request->attributes->get('active_store');

        if ($sale->store_id !== $store->id) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan di store ini.',
            ], 404);
        }

        $data = $request->validate([
            'status' => ['required', 'in:unpaid,partially_paid,paid'],
        ]);

        $sale->update([
            'status' => $data['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran berhasil diupdate.',
            'data' => $sale->fresh(),
        ]);
    }
}
