<?php
// database/seeders/AdminUserSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // ----------------------------
        // 1. Admin User
        // ----------------------------
        $adminData = [
            'first_name' => 'Admin',
            'last_name'  => 'User',
            'email'      => 'admin@ritiyana.com',
            'phone'      => '9876543210',
            'password'   => Hash::make('admin1234'),
            'status'     => 'active',
            'email_verified_at' => now(),
            'last_login_at'     => now(),
        ];

        $this->createUserWithRole($adminData, 'admin', 'admin1234');

        // ----------------------------
        // 2. Shopkeeper User
        // ----------------------------
        $shopkeeperData = [
            'first_name' => 'Shop',
            'last_name'  => 'Keeper',
            'email'      => 'shopkeeper@ritiyana.com',
            'phone'      => '9998887770',
            'password'   => Hash::make('shop1234'),
            'status'     => 'active',
            'email_verified_at' => now(),
            'last_login_at'     => now(),
        ];

        $this->createUserWithRole($shopkeeperData, 'shopkeeper', 'shop1234');
    }

    /**
     * Helper function to create user with a role
     */
    private function createUserWithRole(array $userData, string $roleName, string $plainPassword)
    {
        if (!User::where('email', $userData['email'])->exists()) {
            $user = User::create($userData);

            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $user->roles()->attach($role->id);
                $this->command->info("✅ {$roleName} user created successfully!");
                $this->command->info('📧 Email: ' . $userData['email']);
                $this->command->info('🔑 Password: ' . $plainPassword);
            } else {
                $this->command->error("❌ {$roleName} role not found! Please run RoleSeeder first.");
            }
        } else {
            $this->command->info("ℹ️  {$roleName} user already exists.");
        }
    }
}
