<?php
// database/seeders/RoleSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full system access and management capabilities',
                'is_active' => true,
            ],
            [
                'name' => 'shopkeeper',
                'display_name' => 'Shopkeeper',
                'description' => 'Manage products, orders, inventory and customer service',
                'is_active' => true,
            ],
            [
                'name' => 'user',
                'display_name' => 'Customer',
                'description' => 'Regular customer with shopping and order tracking access',
                'is_active' => true,
            ]
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']], // Search criteria
                $roleData // Data to update or create
            );
        }
    }
}
