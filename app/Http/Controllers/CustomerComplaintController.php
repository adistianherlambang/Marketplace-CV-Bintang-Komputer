<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerComplaintController extends Controller
{
    /**
     * Tampilkan formulir pengajuan komplain untuk pesanan tertentu
     */
    public function create($id)
    {
        $userId = Auth::id();

        $order = Order::with('items.product')
            ->where('id', $id)
            ->where(function($q) use ($userId) {
                $q->where('customer_user_id', $userId)
                  ->orWhere('user_id', $userId);
            })
            ->firstOrFail();

        return view('customers.complaints.create', compact('order'));
    }

    /**
     * Simpan pengajuan komplain dari pelanggan
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'complaint_type' => 'required|string',
            'description' => 'required|string|min:5',
            'nota_bukti' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'product_bukti' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ], [
            'complaint_type.required' => 'Silakan pilih jenis kendala komplain.',
            'description.required' => 'Silakan jelaskan detail kendala atau kerusakan barang.',
            'nota_bukti.required' => 'Foto nota pembelian wajib dilampirkan.',
            'product_bukti.required' => 'Foto bukti kerusakan fisik/produk wajib dilampirkan.',
        ]);

        $userId = Auth::id();

        $order = Order::where('id', $id)
            ->where(function($q) use ($userId) {
                $q->where('customer_user_id', $userId)
                  ->orWhere('user_id', $userId);
            })
            ->firstOrFail();

        $notaPath = $request->file('nota_bukti')->store('complaints/notas', 'public');
        $productBuktiPath = $request->file('product_bukti')->store('complaints/products', 'public');

        Complaint::create([
            'order_id' => $order->id,
            'customer_id' => $userId,
            'customer_name' => $order->customer_display_name,
            'customer_phone' => $order->customer_phone ?? '-',
            'contact' => $order->customer_phone ?? Auth::user()->email ?? '-',
            'complaint_type' => $request->complaint_type,
            'description' => $request->description,
            'complaint_text' => "[{$request->complaint_type}] " . $request->description,
            'nota_bukti' => $notaPath,
            'product_bukti' => $productBuktiPath,
            'status' => 'Pending',
            'date' => now(),
        ]);

        return redirect()->route('customer.orders.index')
            ->with('success', 'Komplain berhasil dikirim ke admin toko CV Bintang Jaya Komputer. Tim kami akan segera menindaklanjuti.');
    }
}