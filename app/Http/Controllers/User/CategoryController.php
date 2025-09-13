<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
   

     public function show(Category $category)
    {
        // Check if category is active
        if (!$category->is_active) {
            abort(404, 'Category not found');
        }

        // Redirect to products page with category filter
        return redirect()->route('products.index', ['category' => $category->slug]);
    }

    /**
     * Get all child category IDs recursively
     */
    private function getAllChildCategoryIds(Category $category)
    {
        $childIds = collect();
        
        foreach ($category->children as $child) {
            $childIds->push($child->id);
            $childIds = $childIds->merge($this->getAllChildCategoryIds($child));
        }
        
        return $childIds;
    }

    /**
     * Get breadcrumb path for category
     */
    private function getBreadcrumbs(Category $category)
    {
        $breadcrumbs = collect();
        $current = $category;
        
        while ($current) {
            $breadcrumbs->prepend($current);
            $current = $current->parent;
        }
        
        return $breadcrumbs;
    }
}
