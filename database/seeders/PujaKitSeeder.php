<?php
// database/seeders/PujaKitSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Puja;
use App\Models\Product;
use App\Models\PujaKit;

class PujaKitSeeder extends Seeder
{
    public function run()
    {
        // Get pujas
        $ganeshPuja = Puja::where('slug', 'ganesh-puja')->first();
        $lakshmiPuja = Puja::where('slug', 'lakshmi-puja')->first();

        if (!$ganeshPuja || !$lakshmiPuja) {
            $this->command->error('Required Puja entries not found. Please run PujaSeeder first.');
            return;
        }

        // Get products with EXACT slugs from your database
        $products = [
            'diyas' => Product::where('slug', 'brass-diya-set-pack-of-12')->first(), // Fixed slug
            'incense' => Product::where('slug', 'sandalwood-incense-sticks')->first(),
            'garland' => Product::where('slug', 'marigold-flower-garland')->first(),
            'camphor' => Product::where('slug', 'pure-camphor-tablets')->first(),
            'coconut' => Product::where('slug', 'coconut-whole-puja-special')->first(), // Fixed slug
            'kumkum' => Product::where('slug', 'sacred-kumkum-powder')->first(),
            'panchamrit' => Product::where('slug', 'panchamrit-complete-kit')->first(),
        ];

        // Check if all required products exist
        $missingProducts = [];
        foreach ($products as $key => $product) {
            if (!$product) {
                $missingProducts[] = $key;
            }
        }

        if (!empty($missingProducts)) {
            $this->command->error('Missing products: ' . implode(', ', $missingProducts));
            return;
        }

        $this->command->info('All dependencies found. Creating Puja Kits...');

        $pujaKits = [
            [
                'kit_description' => 'Complete Ganesh Puja Kit with essential items for Lord Ganesha worship. Perfect for Ganesh Chaturthi and regular prayers.',
                'included_items' => json_encode([
                    'Prayer Instructions Card',
                    'Ganesh Mantra Book',
                    'Sacred Red Thread (Kalava)',
                    'Turmeric Powder',
                    'Rice Grains for Offering',
                ]),
                'discount_percentage' => 15.00,
                'is_active' => true,
                'pujas' => [$ganeshPuja->id],
                'products' => [
                    ['product_id' => $products['diyas']->id, 'quantity' => 1, 'price' => null],
                    ['product_id' => $products['incense']->id, 'quantity' => 1, 'price' => null],
                    ['product_id' => $products['garland']->id, 'quantity' => 2, 'price' => null],
                    ['product_id' => $products['camphor']->id, 'quantity' => 1, 'price' => null],
                    ['product_id' => $products['coconut']->id, 'quantity' => 1, 'price' => null],
                    ['product_id' => $products['kumkum']->id, 'quantity' => 1, 'price' => null],
                ],
            ],
            [
                'kit_description' => 'Premium Lakshmi Puja Kit for Diwali and prosperity prayers. Includes all traditional items for Goddess Lakshmi worship.',
                'included_items' => json_encode([
                    'Lakshmi Yantra',
                    'Gold Plated Coins (5 pieces)',
                    'Red Silk Cloth',
                    'Lotus Seeds',
                    'Prayer Instructions',
                ]),
                'discount_percentage' => 20.00,
                'is_active' => true,
                'pujas' => [$lakshmiPuja->id],
                'products' => [
                    ['product_id' => $products['diyas']->id, 'quantity' => 1, 'price' => null],
                    ['product_id' => $products['incense']->id, 'quantity' => 2, 'price' => null],
                    ['product_id' => $products['garland']->id, 'quantity' => 1, 'price' => null],
                    ['product_id' => $products['camphor']->id, 'quantity' => 1, 'price' => null],
                    ['product_id' => $products['kumkum']->id, 'quantity' => 1, 'price' => null],
                ],
            ],
            [
                'kit_description' => 'Universal Puja Kit suitable for multiple Hindu festivals and daily prayers. Essential items for any puja ceremony.',
                'included_items' => json_encode([
                    'Universal Prayer Book',
                    'Aarti Plate (Brass)',
                    'Cotton Wicks',
                    'Sesame Oil',
                    'Multi-purpose Sacred Thread',
                ]),
                'discount_percentage' => 10.00,
                'is_active' => true,
                'pujas' => [$ganeshPuja->id, $lakshmiPuja->id], // Multiple pujas
                'products' => [
                    ['product_id' => $products['diyas']->id, 'quantity' => 1, 'price' => null],
                    ['product_id' => $products['incense']->id, 'quantity' => 1, 'price' => null],
                    ['product_id' => $products['camphor']->id, 'quantity' => 1, 'price' => null],
                    ['product_id' => $products['kumkum']->id, 'quantity' => 1, 'price' => null],
                ],
            ],
        ];

        foreach ($pujaKits as $kitData) {
            // Create the puja kit
            $pujaKit = PujaKit::create([
                'kit_description' => $kitData['kit_description'],
                'included_items' => $kitData['included_items'],
                'discount_percentage' => $kitData['discount_percentage'],
                'is_active' => $kitData['is_active'],
            ]);

            // Attach pujas
            $pujaKit->pujas()->sync($kitData['pujas']);

            // Attach products with quantities and prices
            $productData = [];
            foreach ($kitData['products'] as $product) {
                $productData[$product['product_id']] = [
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                ];
            }
            $pujaKit->products()->sync($productData);

            $this->command->info("Created: {$kitData['kit_description']}");
        }

        $this->command->info('All Puja Kits seeded successfully!');
    }
}
