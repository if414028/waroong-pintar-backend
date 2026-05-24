<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStoreSubscriptionIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $store = $request->attributes->get('active_store');

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Active store tidak ditemukan.',
            ], 422);
        }

        $subscription = $store->activeSubscription()
            ->with('plan')
            ->first();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'code' => 'SUBSCRIPTION_INACTIVE',
                'message' => 'Subscription store tidak aktif atau sudah expired.',
            ], 402);
        }

        $request->attributes->set('active_subscription', $subscription);
        $request->attributes->set('active_plan', $subscription->plan);

        return $next($request);
    }
}