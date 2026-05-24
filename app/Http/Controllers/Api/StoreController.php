<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\SubscriptionPlan;
use App\Models\StoreSubscription;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $stores = $request->user()
            ->stores()
            ->with([
                'activeSubscription.plan',
            ])
            ->withPivot('role_in_store')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $stores,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
        ]);

        $store = Store::create([
            'owner_user_id' => $request->user()->id,
            'name' => $data['name'],
            'code' => 'STR-' . strtoupper(Str::random(8)),
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'city' => $data['city'] ?? null,
            'province' => $data['province'] ?? null,
            'status' => 'active',
        ]);

        $store->users()->attach($request->user()->id, [
            'role_in_store' => 'owner',
        ]);

        /** @disregard */
        $basicPlan = SubscriptionPlan::where('code', 'basic')->first();

        if ($basicPlan) {
            StoreSubscription::create([
                'store_id' => $store->id,
                'subscription_plan_id' => $basicPlan->id,
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Store berhasil dibuat.',
            'data' => $store->load(['users', 'activeSubscription.plan']),
        ], 201);
    }

    public function show(Request $request, Store $store)
    {
        $hasAccess = $request->user()
            ->stores()
            ->where('stores.id', $store->id)
            ->exists();

        if (!$hasAccess) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke store ini.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $store->load([
                'owner',
                'users',
                'activeSubscription.plan',
            ]),
        ]);
    }
}