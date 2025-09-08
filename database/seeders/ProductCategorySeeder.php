<?php
// database/seeders/ProductCategorySeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductCategorySeeder extends Seeder
{
    public function run()
    {
        // Get categories
        $pujaEssentials = Category::where('slug', 'puja-essentials')->first();
        $decorative = Category::where('slug', 'decorative-items')->first();
        $flowers = Category::where('slug', 'flowers-garlands')->first();
        $incense = Category::where('slug', 'incense-fragrances')->first();
        $lamps = Category::where('slug', 'lamps-diyas')->first();
        $sweets = Category::where('slug', 'sweets-prasadam')->first();

        // Product-Category mappings
        $productCategoryMappings = [
            'brass-diya-set-12' => [$lamps->id, $pujaEssentials->id],
            'sandalwood-incense-sticks' => [$incense->id, $pujaEssentials->id],
            'marigold-flower-garland' => [$flowers->id, $decorative->id],
            'pure-camphor-tablets' => [$pujaEssentials->id],
            'whole-coconut-puja-special' => [$pujaEssentials->id, $sweets->id],
            'sacred-kumkum-powder' => [$pujaEssentials->id],
            'panchamrit-complete-kit' => [$pujaEssentials->id, $sweets->id],
        ];

        foreach ($productCategoryMappings as $productSlug => $categoryIds) {
            $product = Product::where('slug', $productSlug)->first();
            if ($product) {
                $product->categories()->sync($categoryIds);
            }
        }
    }
}
