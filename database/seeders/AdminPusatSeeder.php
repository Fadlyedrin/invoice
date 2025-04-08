<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class AdminPusatSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat atau ambil role Admin Pusat
        $adminPusatRole = Role::firstOrCreate(['name' => 'admin pusat']);

        // 2. List resource dan action yang diizinkan
        $resources = ['users', 'roles', 'permissions'];
        $actions = ['index', 'create', 'read', 'update', 'delete'];

        $permissions = [];

        // 3. Buat permission untuk setiap kombinasi
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $permissionName = "{$action} {$resource}";
                $permissions[] = Permission::firstOrCreate(['name' => $permissionName]);
            }
        }

        // 4. Assign semua permission ke role Admin Pusat
        $adminPusatRole->syncPermissions($permissions);

        // 5. Buat user admin pusat
        $user = User::firstOrCreate(
            ['email' => 'adminpusat@example.com'],
            [
                'username' => 'Admin Pusat',
                'password' => Hash::make('password123'), // Ganti password sebelum deploy!
            ]
        );

        // 6. Assign role ke user
        $user->assignRole($adminPusatRole);
    }
}
