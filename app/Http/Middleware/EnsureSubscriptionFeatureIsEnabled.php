<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionFeatureIsEnabled
{
    private const FEATURES = [
        'advanced_reports' => 'can_access_advanced_reports',
        'export_reports' => 'can_export_reports',
        'cashier_shift' => 'can_use_cashier_shift',
        'purchase_order' => 'can_use_purchase_order',
        'supplier_management' => 'can_use_supplier_management',
        'api_access' => 'can_access_api',
    ];

    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $plan = $request->attributes->get('active_plan');

        if (!$plan) {
            return response()->json([
                'success' => false,
                'message' => 'Active plan tidak ditemukan.',
            ], 422);
        }

        $field = self::FEATURES[$feature] ?? $feature;

        if (!array_key_exists($field, $plan->getAttributes()) || !$plan->{$field}) {
            return response()->json([
                'success' => false,
                'code' => 'FEATURE_NOT_AVAILABLE',
                'message' => 'Fitur ini tidak tersedia untuk plan subscription saat ini.',
                'data' => [
                    'feature' => $feature,
                    'plan' => $plan->code,
                ],
            ], 403);
        }

        return $next($request);
    }
}
