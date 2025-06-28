<?php


namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user if it doesn't exist
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'email_verified_at' => now(),
            ]
        );

        // Create seller user if it doesn't exist
        $seller = \App\Models\User::firstOrCreate(
            ['email' => 'seller@gmail.com'],
            [
                'name' => 'Seller',
                'username' => 'seller',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'email_verified_at' => now(),
            ]
        );

        // Create permissions (only if they don't exist)
        $permissions = [
            ['name' => 'pos.menu', 'group_name' => 'pos'],
            ['name' => 'employee.menu', 'group_name' => 'employee'],
            ['name' => 'customer.menu', 'group_name' => 'customer'],
            ['name' => 'supplier.menu', 'group_name' => 'supplier'],
            ['name' => 'salary.menu', 'group_name' => 'salary'],
            ['name' => 'attendence.menu', 'group_name' => 'attendence'],
            ['name' => 'category.menu', 'group_name' => 'category'],
            ['name' => 'product.menu', 'group_name' => 'product'],
            ['name' => 'orders.menu', 'group_name' => 'orders'],
            ['name' => 'stock.menu', 'group_name' => 'stock'],
            ['name' => 'roles.menu', 'group_name' => 'roles'],
            ['name' => 'user.menu', 'group_name' => 'user'],
            ['name' => 'database.menu', 'group_name' => 'database'],
            ['name' => 'price_logs.menu', 'group_name' => 'price_log'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }

        // Create roles if they don't exist
        $superAdminRole = Role::firstOrCreate(['name' => 'SuperAdmin']);
        $sellerRole = Role::firstOrCreate(['name' => 'Seller']);

        // Sync permissions to roles (this will not duplicate)
        $superAdminRole->syncPermissions(Permission::all());
        $sellerRole->syncPermissions([
            'pos.menu',
            'customer.menu', 
            'product.menu', 
            'orders.menu',
            'price_logs.menu' // Added price_logs.menu permission for Seller
        ]);

        // Sync roles to users (this will not duplicate)
        $admin->syncRoles('SuperAdmin');
        $seller->syncRoles('Seller');
        
        // Only create sample data if tables are empty
        if (\App\Models\Category::count() === 0) {
            \App\Models\Category::factory(5)->create();
        }
        
        if (\App\Models\Supplier::count() === 0) {
            \App\Models\Supplier::factory(5)->create();
        }
        
        if (\App\Models\Employee::count() === 0) {
            \App\Models\Employee::factory(5)->create();
        }
        
        if (\App\Models\Customer::count() === 0) {
            // Create 5 unique customers
            \App\Models\Customer::factory(5)->create();
        }
        
        if (\App\Models\Product::count() === 0) {
            \App\Models\Product::factory(50)->create();
        }
    }
}