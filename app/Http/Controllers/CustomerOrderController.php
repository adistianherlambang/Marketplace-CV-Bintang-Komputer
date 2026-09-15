<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    public function index()
    {
        $pesanan = Order::where('user_id', Auth::id())
                        ->with(['items.product', 'kecamatan', 'kelurahan'])
                        ->latest()
                        ->get();

        return view('customers.orders.index', compact('pesanan'));
    }

    public function konfirmasiSelesai($id)
    {
        $order = Order::where('id', $id)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();
                      
        $order->update([
            'status' => 'Selesai'
        ]);

        return back()->with('success', 'Pesanan berhasil dikonfirmasi selesai!');
    }

    public function createComplaint($id)
    {
        $order = Order::where('id', $id)
                      ->where('user_id', Auth::id())
                      ->with('items.product')
                      ->firstOrFail();

        return view('customers.orders.complaints', compact('order'));
    }

    public function storeComplaint(Request $request, $id)
    {
        $request->validate([
            'complaint_type' => 'required|string',
            'description' => 'required|string|min:10',
            'nota_bukti' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'product_bukti' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $order = Order::where('id', $id)->firstOrFail();

        $notaPath = $request->file('nota_bukti')->store('complaints/notas', 'public');
        $productBuktiPath = $request->file('product_bukti')->store('complaints/products', 'public');

        Complaint::create([
            'order_id' => $order->id,
            'customer_id' => Auth::id(),
            'customer_name' => $order->customer_name ?? Auth::user()->name,
            'customer_phone' => $order->customer_phone ?? '-',
            'complaint_type' => $request->complaint_type,
            'description' => $request->description,
            'nota_bukti' => $notaPath,
            'product_bukti' => $productBuktiPath,
            'status' => 'Pending',
        ]);

        return redirect()->route('customer.orders.index')->with('success', 'Komplain berhasil dikirim ke admin toko.');
    }
}