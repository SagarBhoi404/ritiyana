<?php
// database/seeders/ProductSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Brass Diya Set (Pack of 12)',
                'slug' => 'brass-diya-set-12', // Ensure this matches PujaKitSeeder
                'short_description' => 'Traditional brass diyas for festivals',
                'sku' => 'BD-DIYA-12',
                'type' => 'simple',
                'price' => 299.00,
                'stock_quantity' => 150,
                'is_active' => true,
            ],
            [
                'name' => 'Sandalwood Incense Sticks',
                'slug' => 'sandalwood-incense-sticks', // Ensure this matches PujaKitSeeder
                'short_description' => 'Premium sandalwood agarbatti',
                'sku' => 'INC-SAND-100',
                'type' => 'simple',
                'price' => 150.00,
                'stock_quantity' => 200,
                'is_active' => true,
            ],
            [
                'name' => 'Marigold Flower Garland',
                'slug' => 'marigold-flower-garland', // Ensure this matches PujaKitSeeder
                'short_description' => 'Fresh marigold garlands',
                'sku' => 'FLR-MAR-GAR',
                'type' => 'simple',
                'price' => 80.00,
                'stock_quantity' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'Pure Camphor Tablets',
                'slug' => 'pure-camphor-tablets', // Ensure this matches PujaKitSeeder
                'sku' => 'CAM-PUR-500G',
                'type' => 'simple',
                'price' => 120.00,
                'stock_quantity' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Coconut (Whole) - Puja Special',
                'slug' => 'whole-coconut-puja-special', // Ensure this matches PujaKitSeeder
                'sku' => 'COC-WHO-PUJ',
                'type' => 'simple',
                'price' => 50.00,
                'stock_quantity' => 75,
                'is_active' => true,
            ],
            [
                'name' => 'Sacred Kumkum Powder',
                'slug' => 'sacred-kumkum-powder', // Ensure this matches PujaKitSeeder
                'sku' => 'KUM-SAC-100G',
                'type' => 'simple',
                'price' => 45.00,
                'stock_quantity' => 120,
                'is_active' => true,
            ],
            [
                'name' => 'Panchamrit Complete Kit',
                'slug' => 'panchamrit-complete-kit', // Ensure this matches PujaKitSeeder
                'sku' => 'PAN-KIT-COMP',
                'type' => 'kit',
                'price' => 200.00,
                'stock_quantity' => 80,
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            Product::updateOrCreate(
                ['slug' => $productData['slug']], // Match by slug
                $productData
            );
        }

        $this->command->info('Products seeded successfully!');
    }
}
