<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class GuestCatalogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $brands = Brand::all();

        $query = Product::with(['category', 'brand', 'primaryImage', 'images'])
            ->where('is_active', true);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // Brand filter
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->input('brand'));
        }

        // Pagination
        $products = $query->latest()->paginate(12)->withQueryString();

        return view('catalog.index', compact('products', 'categories', 'brands'));
    }

    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $product->load(['category', 'brand', 'images', 'supplier']);
        return view('catalog.show', compact('product'));
    }
}
