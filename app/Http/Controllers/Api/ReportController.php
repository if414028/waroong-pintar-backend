<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function salesSummary(Request $request)
    {
        $store = $request->attributes->get('active_store');

        $data = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
        ]);

        $dateFrom = $data['date_from'] ?? now()->toDateString();
        $dateTo = $data['date_to'] ?? now()->toDateString();

        $query = Sale::query()
            ->where('store_id', $store->id)
            ->where('status', 'completed')
            ->whereDate('sold_at', '>=', $dateFrom)
            ->whereDate('sold_at', '<=', $dateTo);

        $totalTransactions = (clone $query)->count();
        $totalSales = (clone $query)->sum('grand_total');
        $totalDiscount = (clone $query)->sum('discount_amount');
        $totalTax = (clone $query)->sum('tax_amount');
        $totalPaid = (clone $query)->sum('paid_amount');

        $averageTransaction = $totalTransactions > 0
            ? $totalSales / $totalTransactions
            : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'store_id' => $store->id,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'total_transactions' => $totalTransactions,
                'total_sales' => (float) $totalSales,
                'total_discount' => (float) $totalDiscount,
                'total_tax' => (float) $totalTax,
                'total_paid' => (float) $totalPaid,
                'average_transaction' => (float) $averageTransaction,
            ],
        ]);
    }

    public function topProducts(Request $request)
    {
        $store = $request->attributes->get('active_store');

        $data = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $dateFrom = $data['date_from'] ?? now()->startOfMonth()->toDateString();
        $dateTo = $data['date_to'] ?? now()->toDateString();
        $limit = $data['limit'] ?? 10;

        $products = SaleItem::query()
            ->select([
                'sale_items.product_id',
                'sale_items.product_name',
                'sale_items.sku',
                DB::raw('SUM(sale_items.qty) as total_qty'),
                DB::raw('SUM(sale_items.line_total) as total_sales'),
            ])
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->where('sales.store_id', $store->id)
            ->where('sales.status', 'completed')
            ->whereDate('sales.sold_at', '>=', $dateFrom)
            ->whereDate('sales.sold_at', '<=', $dateTo)
            ->groupBy('sale_items.product_id', 'sale_items.product_name', 'sale_items.sku')
            ->orderByDesc('total_qty')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'store_id' => $store->id,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'items' => $products,
            ],
        ]);
    }

    public function lowStocks(Request $request)
    {
        $store = $request->attributes->get('active_store');

        $stocks = Stock::query()
            ->with(['product.category'])
            ->where('store_id', $store->id)
            ->whereColumn('qty_on_hand', '<=', 'minimum_stock')
            ->orderBy('qty_on_hand')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'store_id' => $store->id,
                'items' => $stocks,
            ],
        ]);
    }
}