<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelolaPesananController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'customerUser', 'items.product', 'kecamatan', 'kelurahan'])
                      ->latest();

        // Filter status jika dipilih
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pencarian nomor invoice atau nama pembeli
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $pesanan = $query->paginate(15)->withQueryString();

        // Hitung statistik pesanan
        $counts = [
            'all' => Order::count(),
            'menunggu' => Order::where('status', 'Menunggu Konfirmasi')->count(),
            'diproses' => Order::where('status', 'Diproses')->count(),
            'dikirim' => Order::where('status', 'Dikirim')->count(),
            'selesai' => Order::whereIn('status', ['Selesai', 'Lunas'])->count(),
            'belum_bayar' => Order::where('status', 'Belum Dibayar')->count(),
        ];

        return view('admin.pesanan.index', compact('pesanan', 'counts'));
    }

    public function updateStatus(Request $request, int|string $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $order = Order::findOrFail($id);
        
        $order->status = $request->status;
        
        // Catat admin yang mengonfirmasi/memproses pesanan
        if (Auth::check()) {
            $order->user_id = Auth::id();
        }
        
        $order->save();

        // Jika status dikonfirmasi diproses/selesai dan belum ada data payment, buat data payment
        if (in_array($request->status, ['Diproses', 'Dikirim', 'Selesai', 'Lunas'])) {
            if ($order->payments()->count() === 0) {
                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => $order->payment_method ?? 'Transfer BNI',
                    'amount_paid' => $order->total_amount,
                    'payment_status' => 'Lunas',
                    'payment_date' => now(),
                ]);
            }
        }

        return redirect()->back()->with('success', "Status pesanan {$order->invoice_number} berhasil diubah menjadi {$order->status}!");
    }
}