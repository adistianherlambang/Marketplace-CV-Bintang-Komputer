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
        $userId = Auth::id();
        $pesanan = Order::where(function($q) use ($userId) {
                            $q->where('customer_user_id', $userId)
                              ->orWhere(function($sub) use ($userId) {
                                  // Fallback for orders created where customer was stored in user_id and customer_user_id was null
                                  $sub->where('user_id', $userId)
                                      ->whereNull('customer_user_id');
                              });
                        })
                        ->with(['items.product', 'kecamatan', 'kelurahan'])
                        ->latest()
                        ->get();

        return view('customers.orders.index', compact('pesanan'));
    }

    public function konfirmasiSelesai(int|string $id)
    {
        $userId = Auth::id();
        $order = Order::where('id', $id)
                      ->where(function($q) use ($userId) {
                          $q->where('customer_user_id', $userId)
                            ->orWhere('user_id', $userId);
                      })
                      ->firstOrFail();
                      
        $order->update([
            'status' => 'Selesai'
        ]);

        return back()->with('success', 'Pesanan berhasil dikonfirmasi selesai! Terima kasih telah berbelanja di CV Bintang Jaya Komputer.');
    }
}