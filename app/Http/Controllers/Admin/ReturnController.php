<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ReturnLog;
use App\Services\OrderService;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $returns = ReturnLog::with(['order', 'product'])->latest()->paginate(10);
        $orders = Order::with('items.product')->latest()->limit(50)->get();
        return view('admin.returns.index', compact('returns', 'orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string',
            'approve_immediately' => 'nullable|boolean',
        ]);

        $order = Order::findOrFail($request->order_id);
        $product = Product::findOrFail($request->product_id);
        $qty = (int)$request->quantity;

        // Check if item quantity in order is sufficient
        $orderedQty = $order->items()->where('product_id', $product->id)->sum('quantity');
        if ($qty > $orderedQty) {
            return redirect()->back()->with('error', "Jumlah barang yang diretur ({$qty}) melebihi jumlah pembelian ({$orderedQty}).");
        }

        $approveImmediately = $request->has('approve_immediately');
        $status = $approveImmediately ? 'Disetujui' : 'Menunggu';

        try {
            $this->orderService->processReturn($order, $product, $qty, $request->reason, $status);
            return redirect()->route('admin.returns.index')->with('success', 'Retur berhasil dicatat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mencatat retur: ' . $e->getMessage());
        }
    }

    public function approve(ReturnLog $returnLog)
    {
        try {
            $this->orderService->approveReturn($returnLog);
            return redirect()->route('admin.returns.index')->with('success', 'Retur disetujui dan stok dikembalikan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.returns.index')->with('error', 'Gagal menyetujui retur: ' . $e->getMessage());
        }
    }

    public function reject(ReturnLog $returnLog)
    {
        try {
            $this->orderService->rejectReturn($returnLog);
            return redirect()->route('admin.returns.index')->with('success', 'Retur ditolak.');
        } catch (\Exception $e) {
            return redirect()->route('admin.returns.index')->with('error', 'Gagal menolak retur: ' . $e->getMessage());
        }
    }
}
