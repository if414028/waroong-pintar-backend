<?php

namespace App\Http\Middleware;

use App\Models\Product;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionResourceLimit
{
    private const LIMIT_FIELDS = [
        'products' => 'max_products',
        'users' => 'max_users',
        'stores' => 'max_stores',
    ];

    public function handle(Request $request, Closure $next, string $resource): Response
    {
        $store = $request->attributes->get('active_store');
        $plan = $request->attributes->get('active_plan');

        if (!$store || !$plan) {
            return response()->json([
                'success' => false,
                'message' => 'Active store atau active plan tidak ditemukan.',
            ], 422);
        }

        $limitField = self::LIMIT_FIELDS[$resource] ?? null;

        if (!$limitField) {
            return response()->json([
                'success' => false,
                'message' => 'Resource limit tidak valid.',
            ], 422);
        }

        $limit = $plan->{$limitField};

        if ($limit === null) {
            return $next($request);
        }

        $currentUsage = $this->currentUsage($request, $resource, $store);

        if ($currentUsage >= $limit) {
            return response()->json([
                'success' => false,
                'code' => 'SUBSCRIPTION_LIMIT_REACHED',
                'message' => 'Limit resource untuk plan subscription saat ini sudah tercapai.',
                'data' => [
                    'resource' => $resource,
                    'plan' => $plan->code,
                    'limit' => $limit,
                    'current_usage' => $currentUsage,
                ],
            ], 403);
        }

        return $next($request);
    }

    private function currentUsage(Request $request, string $resource, $store): int
    {
        return match ($resource) {
            'products' => $this->currentProductUsage($request, $store),
            'users' => $store->users()->count(),
            'stores' => $request->user()->stores()->count(),
            default => 0,
        };
    }

    private function currentProductUsage(Request $request, $store): int
    {
        $product = $request->route('product');

        if ($product instanceof Product) {
            $alreadyActive = $product->stores()
                ->where('stores.id', $store->id)
                ->where('store_products.is_active', true)
                ->exists();

            if ($alreadyActive) {
                return 0;
            }
        }

        return $store->products()
            ->where('store_products.is_active', true)
            ->count();
    }
}
