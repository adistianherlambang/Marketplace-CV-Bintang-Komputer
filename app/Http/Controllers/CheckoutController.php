<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\StockService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index(Request $request)
    {
        $productId = $request->query('product_id');
        
        if (!$productId) {
            return redirect()->route('catalog.index')->with('error', 'Silakan pilih produk terlebih dahulu.');
        }

        $product = Product::findOrFail($productId);
        if ($product->stock <= 0) {
            return redirect()->route('catalog.index')->with('error', 'Maaf, stok produk ini sedang habis.');
        }

        $kecamatans = Kecamatan::with('kelurahans')->get();
        
        return view('checkout', compact('product', 'kecamatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'product_id' => 'required|exists:products,id',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'kelurahan_id' => 'required|exists:kelurahans,id',
            'shareloc_link' => 'required|url',
            'payment_method' => 'required|string',
            'bukti_transfer' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $product = Product::findOrFail($request->product_id);
        if ($product->stock < 1) {
            return redirect()->back()->withInput()->with('error', 'Maaf, stok produk ini tidak mencukupi atau sudah habis.');
        }

        $kelurahan = Kelurahan::findOrFail($request->kelurahan_id);

        $buktiTransferPath = null;
        if ($request->hasFile('bukti_transfer')) {
            $buktiTransferPath = $request->file('bukti_transfer')->store('bukti_transfer', 'public');
        }

        // Tentukan status berdasarkan ada tidaknya bukti transfer
        $statusPesanan = $buktiTransferPath ? 'Menunggu Konfirmasi' : 'Belum Dibayar';

        $order = DB::transaction(function () use ($request, $product, $kelurahan, $buktiTransferPath, $statusPesanan) {
            // 1. Buat Header Order
            // user_id dikosongkan (null) untuk order online dari customer, 
            // agar tidak keliru tercatat sebagai kasir/admin.
            // customer_user_id mencatat akun pelanggan yang sedang login.
            $newOrder = Order::create([
                'invoice_number' => 'INV/' . date('Ymd') . '/' . sprintf('%04d', rand(1, 9999)),
                'user_id' => null, 
                'customer_user_id' => Auth::id(),
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'status' => $statusPesanan,
                'payment_method' => $request->payment_method,
                'bukti_transfer' => $buktiTransferPath,
                'total_amount' => $product->price_jual + $kelurahan->tarif_grab,
                'kecamatan_id' => $request->kecamatan_id,
                'kelurahan_id' => $request->kelurahan_id,
                'shipping_cost' => $kelurahan->tarif_grab,
                'shareloc_link' => $request->shareloc_link,
            ]);

            // 2. Simpan Item Produk ke OrderItem
            OrderItem::create([
                'order_id' => $newOrder->id,
                'product_id' => $product->id,
                'item_name' => $product->name,
                'price' => $product->price_jual,
                'quantity' => 1,
                'subtotal' => $product->price_jual,
            ]);

            // 3. Kurangi stok produk secara otomatis dan catat histori stok
            $this->stockService->adjustStock(
                $product,
                -1,
                'out',
                "Pembelian online pesanan {$newOrder->invoice_number}"
            );

            return $newOrder;
        });

        // 3. Kirim Notifikasi WhatsApp Otomatis ke Admin
        $this->sendWhatsAppNotificationToAdmin($order, $product);

        return redirect()->route('customer.orders.index')->with('success', 'Pesanan berhasil dibuat! Silakan cek status dan riwayat pesanan Anda.');
    }

    /**
     * Kirim Pesan WhatsApp Otomatis ke Admin
     */
    private function sendWhatsAppNotificationToAdmin(Order $order, Product $product): void
    {
        $adminPhone = '6281234567890'; 
        
        $message = "🔔 *PESANAN ONLINE BARU!*\n\n" .
                   "No. Invoice: *{$order->invoice_number}*\n" .
                   "Nama Pembeli: {$order->customer_name}\n" .
                   "No. HP: {$order->customer_phone}\n" .
                   "Produk: {$product->name}\n" .
                   "Total Bayar: Rp " . number_format($order->total_amount, 0, ',', '.') . "\n" .
                   "Metode Bayar: {$order->payment_method}\n\n" .
                   "Silakan buka panel Admin Bintang Jaya Komputer untuk memproses pesanan ini.";

        try {
            // Http::withHeaders(['Authorization' => 'TOKEN_WHATSAPP_ANDA'])
            //     ->post('https://api.fonnte.com/send', [
            //         'target' => $adminPhone,
            //         'message' => $message,
            //     ]);
        } catch (\Exception $e) {
            // Abaikan jika gagal koneksi API WA
        }
    }
}