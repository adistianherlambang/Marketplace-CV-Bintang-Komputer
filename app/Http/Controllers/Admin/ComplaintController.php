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
        return view('admin.complaints.index', compact('complaints'));
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
            'customer_id' => $order->customer_id,
            'customer_name' => $order->customer_name ?? 'Pelanggan',
            'customer_phone' => $order->customer_phone ?? '-',
            'contact' => $order->customer_phone ?? '-',
            'complaint_type' => $request->complaint_type,
            'description' => $request->description,
            'complaint_text' => "[{$request->complaint_type}] " . $request->description,
            'nota_bukti' => $notaPath,
            'product_bukti' => $productBuktiPath,
            'status' => 'Pending',
            'date' => now(),
        ]);

        return redirect()->route('admin.complaints.index')->with('success', 'Komplain berhasil dicatat.');
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