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
        
        // 2. List semua permission 
        $permissionNames = [
            'users', 
            'create users', 
            'read users',
            'update users', 
            'delete users', 
            'roles',
            'create roles', 
            'read roles', 
            'update roles',
            'delete roles', 
            'permissions', 
            'create permissions',
            'read permissions', 
            'update permissions', 
            'delete permissions',
            'invoices', 
            'create invoices', 
            'update invoices',
            'delete invoices', 
            'approve invoices', 
            'receipt',
            'create receipt', 
            'update receipt', 
            'delete receipt',
            'approve receipt',
            'approval invoices'
        ];
        
        // 3. Buat permission jika belum ada
        $permissions = [];
        foreach ($permissionNames as $permissionName) {
            $permissions[] = Permission::firstOrCreate(['name' => $permissionName]);
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