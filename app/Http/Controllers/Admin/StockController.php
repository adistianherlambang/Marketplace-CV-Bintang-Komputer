<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockHistory;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Product::with(['category', 'brand', 'primaryImage'])
            ->where('is_active', true);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($status === 'low') {
            $query->where('stock', '>', 0)->where('stock', '<=', DB::raw('min_stock'));
        } elseif ($status === 'empty') {
            $query->where('stock', '<=', 0);
        } elseif ($status === 'safe') {
            $query->where('stock', '>', DB::raw('min_stock'));
        }

        // Order low and empty stocks first, then by name
        $products = $query->orderByRaw("CASE WHEN stock <= 0 THEN 1 WHEN stock <= min_stock THEN 2 ELSE 3 END")
            ->orderBy('stock', 'asc')
            ->paginate(12)
            ->withQueryString();

        // Metrics & low-stock warning counters
        $counts = [
            'total' => Product::where('is_active', true)->count(),
            'safe'  => Product::where('is_active', true)->where('stock', '>', DB::raw('min_stock'))->count(),
            'low'   => Product::where('is_active', true)->where('stock', '>', 0)->where('stock', '<=', DB::raw('min_stock'))->count(),
            'empty' => Product::where('is_active', true)->where('stock', '<=', 0)->count(),
        ];

        // All active products for quick modal adjust dropdown
        $allProducts = Product::where('is_active', true)
            ->select('id', 'name', 'sku', 'stock', 'min_stock')
            ->orderBy('name')
            ->get();

        return view('admin.stocks.index', compact('products', 'counts', 'allProducts'));
    }

    public function history(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');

        $query = StockHistory::with(['product', 'user']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('product', function ($pq) use ($search) {
                    $pq->where('name', 'like', "%{$search}%")
                       ->orWhere('sku', 'like', "%{$search}%");
                })->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($type && in_array($type, ['in', 'out', 'return'])) {
            $query->where('type', $type);
        }

        $histories = $query->latest('date')->paginate(15)->withQueryString();

        $counts = [
            'all'    => StockHistory::count(),
            'in'     => StockHistory::where('type', 'in')->count(),
            'out'    => StockHistory::where('type', 'out')->count(),
            'return' => StockHistory::where('type', 'return')->count(),
        ];

        return view('admin.stocks.history', compact('histories', 'counts'));
    }

    public function adjust(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);
        $qty = (int)$request->quantity;

        // If reducing stock, adjust is negative
        $qtyChange = $request->type === 'in' ? $qty : -$qty;

        $this->stockService->adjustStock($product, $qtyChange, $request->type, $request->description);

        return redirect()->back()->with('success', "Stok untuk produk '{$product->name}' berhasil diperbarui.");
    }
}
