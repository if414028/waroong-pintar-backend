<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasStoreAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $storeId = $request->header('X-Store-ID') ?? $request->input('store_id');

        if (!$storeId) {
            return response()->json([
                'success' => false,
                'message' => 'Store ID wajib dikirim.',
            ], 422);
        }

        $store = $request->user()
            ->stores()
            ->where('stores.id', $storeId)
            ->where('stores.status', 'active')
            ->first();

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke store ini.',
            ], 403);
        }

        $request->attributes->set('active_store', $store);

        return $next($request);
    }
}
