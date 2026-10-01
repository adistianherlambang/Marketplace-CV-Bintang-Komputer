<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Complaint;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;
        $userPhone = $user->phone ?? null;

        $pesanan = Order::where(function($q) use ($userId, $userPhone) {
                            $q->where('customer_user_id', $userId)
                              ->orWhere('user_id', $userId);
                            if (!empty($userPhone)) {
                                $q->orWhere('customer_phone', $userPhone);
                            }
                        })
                        ->with(['items.product.primaryImage', 'items.product.brand', 'kecamatan', 'kelurahan'])
                        ->orderByDesc('created_at')
                        ->orderByDesc('id')
                        ->get();

        return view('customers.orders.index', compact('pesanan'));
    }

    public function downloadNota(int|string $id)
    {
        $userId = Auth::id();
        $order = Order::where('id', $id)
                      ->where(function($q) use ($userId) {
                          $q->where('customer_user_id', $userId)
                            ->orWhere('user_id', $userId);
                      })
                      ->with(['customer', 'user', 'items.product', 'kecamatan', 'kelurahan'])
                      ->firstOrFail();

        $safeInvoiceNumber = str_replace(['/', '\\', ' '], '-', $order->invoice_number);
        $pdf = Pdf::loadView('pdf.nota', compact('order'))->setPaper([0, 0, 226.77, 566.92], 'portrait');
        return $pdf->download("nota-{$safeInvoiceNumber}.pdf");
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

    public function cancel(int|string $id)
    {
        $userId = Auth::id();
        $order = Order::where('id', $id)
                      ->where(function($q) use ($userId) {
                          $q->where('customer_user_id', $userId)
                            ->orWhere('user_id', $userId);
                      })
                      ->with('items')
                      ->firstOrFail();

        $st = strtolower(trim($order->status));
        if (!in_array($st, ['menunggu konfirmasi', 'belum dibayar', 'menunggu', 'pending'])) {
            return back()->with('error', 'Pesanan yang sedang diproses, dikirim, atau telah selesai tidak dapat dibatalkan.');
        }

        $oldStatus = $order->status;
        $order->update([
            'status' => 'Dibatalkan'
        ]);

        // Kembalikan kuantitas stok produk ke inventaris
        if (!in_array($oldStatus, ['Dibatalkan', 'Batal'])) {
            $stockService = app(\App\Services\StockService::class);
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    $product = \App\Models\Product::find($item->product_id);
                    if ($product) {
                        $stockService->adjustStock(
                            $product,
                            $item->quantity,
                            'return',
                            "Restored stock from cancelled online order {$order->invoice_number}"
                        );
                    }
                }
            }
        }

        return back()->with('success', "Pesanan {$order->invoice_number} berhasil dibatalkan. Riwayat transaksi tetap tercatat di sistem.");
    }
}