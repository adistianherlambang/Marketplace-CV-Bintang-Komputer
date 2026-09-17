<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Order;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with(['order.items.product']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('complaint_type', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('order', function ($oq) use ($search) {
                      $oq->where('invoice_number', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'Pending') {
                $query->whereIn('status', ['Pending', 'Menunggu']);
            } else {
                $query->where('status', $status);
            }
        }

        $counts = [
            'all' => Complaint::count(),
            'pending' => Complaint::whereIn('status', ['Pending', 'Menunggu'])->count(),
            'diproses' => Complaint::where('status', 'Diproses')->count(),
            'selesai' => Complaint::where('status', 'Selesai')->count(),
            'ditolak' => Complaint::where('status', 'Ditolak')->count(),
        ];

        $complaints = $query->latest()->paginate(10)->withQueryString();

        return view('admin.complaints.index', compact('complaints', 'counts'));
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