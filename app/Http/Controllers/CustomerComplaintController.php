<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Order;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with(['order.items.product'])->latest()->paginate(10);
        $orders = Order::latest()->limit(50)->get(); // <-- INI YANG KURANG (DIKEMBALIKAN)

        return view('admin.complaints.index', compact('complaints', 'orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'complaint_type' => 'required|string',
            'description' => 'required|string',
            'nota_bukti' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'product_bukti' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $order = Order::findOrFail($request->order_id);

        $notaPath = $request->file('nota_bukti')->store('complaints/notas', 'public');
        $productBuktiPath = $request->file('product_bukti')->store('complaints/products', 'public');

        Complaint::create([
            'order_id' => $order->id,
            'customer_id' => $order->user_id,
            'customer_name' => $order->customer_name ?? 'Pelanggan',
            'customer_phone' => $order->customer_phone ?? '-',
            'complaint_type' => $request->complaint_type,
            'description' => $request->description,
            'nota_bukti' => $notaPath,
            'product_bukti' => $productBuktiPath,
            'status' => 'Pending',
        ]);

        return redirect()->route('admin.complaints.index')->with('success', 'Komplain berhasil dikirim.');
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status' => 'required|in:Pending,Menunggu,Diproses,Selesai,Ditolak',
        ]);

        $complaint->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin.complaints.index')->with('success', 'Status komplain berhasil diperbarui.');
    }
}