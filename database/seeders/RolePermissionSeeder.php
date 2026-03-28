<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;


class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view-dashboard',
            'manage-users',
            'manage-stores',
            'manage-products',
            'manage-stocks',
            'create-sales',
            'view-sales',
            'view-reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $owner = Role::firstOrCreate(['name' => 'owner']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $cashier = Role::firstOrCreate(['name' => 'cashier']);

        $owner->syncPermissions($permissions);

        $admin->syncPermissions([
            'view-dashboard',
            'manage-products',
            'manage-stocks',
            'create-sales',
            'view-sales',
            'view-reports',
        ]);

        $cashier->syncPermissions([
            'view-dashboard',
            'create-sales',
            'view-sales',
        ]);
    }
}
