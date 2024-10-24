<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        
       
        $permissions = [
            'view documents', 'create documents', 'edit documents', 'delete documents', 
            'view categories', 'create categories', 'edit categories', 'delete categories', 
            'view members', 'create members', 'edit members', 'delete members', 
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'api']);
        }

        // Define roles and their permissions
        $roles = [
            'admin' => [
                'view documents', 'create documents', 'edit documents', 'delete documents', 
                'view categories', 'create categories', 'edit categories', 'delete categories', 
                'view members', 'create members', 'edit members', 'delete members', 
            ],
            'member' => [
                'view documents', 'create documents', 'edit documents', 'delete documents', 
                'view categories', 'create categories','edit categories', 'delete categories',
                'view members','edit members'
            ]
        ];

        // Create roles and assign permissions
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'api']);
            $role->givePermissionTo($rolePermissions);
        }

        // Create admin user if it doesn't exist
        $admin = User::firstOrCreate([
            'email' => 'admin@mail.com'
        ], [
            'name' => 'admin',
            'password' => bcrypt('admin1@admin1')
        ]);

        // Assign role to admin user
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin'); // You can omit the guard name here
        }
    }
}
