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
        $complaints = Complaint::with('order')->latest()->paginate(10);
        $orders = Order::latest()->limit(50)->get();
        return view('admin.complaints.index', compact('complaints', 'orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'customer_name' => 'required|string|max:255',
            'contact' => 'required|string|max:100',
            'complaint_text' => 'required|string',
        ]);

        Complaint::create([
            'order_id' => $request->order_id,
            'customer_name' => $request->customer_name,
            'contact' => $request->contact,
            'complaint_text' => $request->complaint_text,
            'status' => 'Menunggu',
            'date' => now(),
        ]);

        return redirect()->route('admin.complaints.index')->with('success', 'Komplain berhasil dicatat.');
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Diproses,Selesai',
        ]);

        $complaint->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin.complaints.index')->with('success', 'Status komplain berhasil diperbarui.');
    }
}
