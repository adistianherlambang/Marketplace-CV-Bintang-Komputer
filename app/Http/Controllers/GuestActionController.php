<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBooking;
use App\Models\Complaint;
use App\Models\Order;
use Illuminate\Http\Request;

class GuestActionController extends Controller
{
    /**
     * Store a product booking from guest/customer detail page.
     */
    public function storeBooking(Request $request, Product $product)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'pickup_time' => 'required|date|after_or_equal:now',
            'notes' => 'nullable|string|max:1000',
        ], [
            'customer_name.required' => 'Nama lengkap wajib diisi.',
            'customer_phone.required' => 'Nomor telepon/WhatsApp wajib diisi.',
            'pickup_time.required' => 'Waktu pengambilan wajib ditentukan.',
            'pickup_time.after_or_equal' => 'Waktu pengambilan tidak boleh di masa lalu.',
        ]);

        ProductBooking::create([
            'product_id' => $product->id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'pickup_time' => $request->pickup_time,
            'notes' => $request->notes,
            'status' => 'Menunggu',
        ]);

        return redirect()->back()->with('success', 'Pesanan produk Anda berhasil dikirim. Silakan tunggu konfirmasi dari kami.');
    }

    /**
     * Store a customer complaint via the floating widget (AJAX).
     */
    public function storeComplaint(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'contact' => 'required|string|max:100',
            'complaint_text' => 'required|string',
            'invoice_number' => 'nullable|string|max:100',
        ]);

        $orderId = null;
        $complaintText = $request->complaint_text;

        if ($request->filled('invoice_number')) {
            $invoice = trim($request->invoice_number);
            $order = Order::where('invoice_number', $invoice)->first();
            if ($order) {
                $orderId = $order->id;
            } else {
                $complaintText = "[Nomor Invoice: " . $invoice . " (Tidak ditemukan)]\n" . $complaintText;
            }
        }

        Complaint::create([
            'order_id' => $orderId,
            'customer_name' => $request->customer_name,
            'contact' => $request->contact,
            'complaint_text' => $complaintText,
            'status' => 'Menunggu',
            'date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Komplain Anda berhasil dikirim. Admin kami akan segera menghubungi Anda.'
        ]);
    }
}
