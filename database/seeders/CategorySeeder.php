<?php
// database/seeders/CategorySeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Puja Essentials',
                'slug' => 'puja-essentials',
                'description' => 'Essential items required for performing pujas and religious ceremonies',
                'icon' => 'flame',
                'sort_order' => 1,
                'is_active' => true,
                'meta_title' => 'Puja Essentials - Sacred Items for Hindu Rituals',
                'meta_description' => 'Shop authentic puja essentials including diyas, incense, and ritual items for Hindu ceremonies and festivals.',
            ],
            [
                'name' => 'Decorative Items',
                'slug' => 'decorative-items',
                'description' => 'Beautiful decorative items to enhance your puja space and create divine ambiance',
                'icon' => 'sparkles',
                'sort_order' => 2,
                'is_active' => true,
                'meta_title' => 'Decorative Puja Items - Divine Home Decor',
                'meta_description' => 'Discover exquisite decorative items for your puja room including rangoli, torans, and spiritual artwork.',
            ],
            [
                'name' => 'Flowers & Garlands',
                'slug' => 'flowers-garlands',
                'description' => 'Fresh flowers and traditional garlands for offering to deities',
                'icon' => 'flower',
                'sort_order' => 3,
                'is_active' => true,
                'meta_title' => 'Puja Flowers & Garlands - Fresh Offerings',
                'meta_description' => 'Premium quality flowers and handcrafted garlands for divine offerings and puja decorations.',
            ],
            [
                'name' => 'Incense & Fragrances',
                'slug' => 'incense-fragrances',
                'description' => 'Aromatic incense sticks, dhoop, and fragrances for spiritual purification',
                'icon' => 'wind',
                'sort_order' => 4,
                'is_active' => true,
                'meta_title' => 'Incense Sticks & Puja Fragrances',
                'meta_description' => 'Authentic incense sticks, dhoop, and aromatic fragrances to create a sacred atmosphere for prayers.',
            ],
            [
                'name' => 'Lamps & Diyas',
                'slug' => 'lamps-diyas',
                'description' => 'Traditional oil lamps and diyas to illuminate your prayers',
                'icon' => 'lightbulb',
                'sort_order' => 5,
                'is_active' => true,
                'meta_title' => 'Traditional Lamps & Diyas for Puja',
                'meta_description' => 'Beautiful collection of brass lamps, clay diyas, and decorative lights for puja and festivals.',
            ],
            [
                'name' => 'Sweets & Prasadam',
                'slug' => 'sweets-prasadam',
                'description' => 'Sacred sweets and prasadam for offering to deities',
                'parent_id' => null,
                'icon' => 'cake',
                'sort_order' => 6,
                'is_active' => true,
                'meta_title' => 'Puja Sweets & Prasadam',
                'meta_description' => 'Traditional sweets and prasadam for religious offerings and festival celebrations.',
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }
    }
}
