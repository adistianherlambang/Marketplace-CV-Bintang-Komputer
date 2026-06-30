<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Supplier;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'supplier', 'primaryImage']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();
        $brands = Brand::all();
        $suppliers = Supplier::all();

        return view('admin.products.index', compact('products', 'categories', 'brands', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku',
            'barcode' => 'nullable|string|max:50|unique:products,barcode',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'price_modal' => 'required|numeric|min:0',
            'price_jual' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'specs' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $product = Product::create($request->only([
            'name', 'sku', 'barcode', 'category_id', 'brand_id', 'supplier_id',
            'price_modal', 'price_jual', 'stock', 'min_stock', 'description', 'specs'
        ]));

        // Log initial stock
        if ($product->stock > 0) {
            $this->stockService->adjustStock($product, $product->stock, 'in', 'Initial stock on product creation');
        }

        // Upload images
        if ($request->hasFile('images')) {
            $first = true;
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_primary' => $first, // first image is primary by default
                ]);
                $first = false;
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|max:50|unique:products,barcode,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'price_modal' => 'required|numeric|min:0',
            'price_jual' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_reason' => 'nullable|string|max:255',
            'min_stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'specs' => 'nullable|string',
            'is_active' => 'required|boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $oldStock = $product->stock;
        $newStock = (int)$request->input('stock');

        $product->update($request->only([
            'name', 'sku', 'barcode', 'category_id', 'brand_id', 'supplier_id',
            'price_modal', 'price_jual', 'min_stock', 'description', 'specs', 'is_active'
        ]));

        if ($newStock !== $oldStock) {
            $diff = $newStock - $oldStock;
            $type = $diff > 0 ? 'in' : 'out';
            $reason = $request->input('stock_reason') ?: 'Stock adjusted during product edit';
            // ponytail: handle stock audit log on direct adjustment
            $this->stockService->adjustStock($product, $diff, $type, $reason);
        }

        // Upload new images
        if ($request->hasFile('images')) {
            $hasPrimary = ProductImage::where('product_id', $product->id)->where('is_primary', true)->exists();
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_primary' => !$hasPrimary,
                ]);
                $hasPrimary = true;
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus (Soft Delete).');
    }
}
