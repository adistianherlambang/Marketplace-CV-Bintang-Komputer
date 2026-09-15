<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class KelolaPesananController extends Controller
{
    public function index()
    {
        $pesanan = Order::with(['user', 'items.product', 'kecamatan', 'kelurahan'])
                        ->latest()
                        ->get();
                        
        return view('admin.pesanan.index', compact('pesanan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $order = Order::findOrFail($id);
        
        $order->status = $request->status;
        $order->save();

        return redirect()->route('admin.pesanan.index')->with('success', 'Status pesanan berhasil diperbarui!');
    }
}