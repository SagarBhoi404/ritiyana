<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
            // CategorySeeder::class,      // First - no dependencies
            // PujaSeeder::class,          // Second - no dependencies  
            // ProductSeeder::class,       // Third - may depend on categories
            // ProductCategorySeeder::class, // Fourth - depends on products and categories
            // PujaKitSeeder::class,       // Last - depends on pujas and products
        ]);
    }
}
