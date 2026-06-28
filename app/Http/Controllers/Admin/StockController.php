<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockHistory;
use App\Services\StockService;
use Illuminate\Http\Request;

class StockController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index()
    {
        $products = Product::where('is_active', true)->get();
        $histories = StockHistory::with(['product', 'user'])->latest()->paginate(15);
        return view('admin.stocks.index', compact('products', 'histories'));
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

        return redirect()->route('admin.stocks.index')->with('success', 'Stok berhasil diperbarui dan dicatat.');
    }
}
