<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Services\OrderService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $query = Order::with(['customer', 'user', 'items']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('customerUser', function($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $orders = $query->latest()->paginate(10)->withQueryString();
        return view('admin.transactions.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->where('stock', '>', 0)->get();
        $customers = Customer::all();
        return view('admin.transactions.create', compact('products', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'customer_address' => 'nullable|string',
            'status' => 'required|in:Lunas,Belum Dibayar',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.item_name' => 'required_without:items.*.product_id|string|max:255',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $order = $this->orderService->createOrder(
                $request->only(['customer_id', 'status', 'payment_method', 'notes']),
                $request->input('items')
            );

            return redirect()->route('admin.transactions.show', $order->id)
                ->with('success', "Transaksi {$order->invoice_number} berhasil disimpan.");
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'user', 'items.product', 'payments', 'kecamatan', 'kelurahan']);
        return view('admin.transactions.show', compact('order'));
    }

    public function updatePaymentStatus(Order $order, Request $request)
    {
        $order->update([
            'status' => 'Lunas'
        ]);

        Payment::create([
            'order_id' => $order->id,
            'payment_method' => $order->payment_method ?? $request->input('payment_method', 'Transfer Manual'),
            'amount_paid' => $order->total_amount,
            'payment_status' => 'Lunas',
            'payment_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Bukti transfer diverifikasi dan pembayaran berhasil dikonfirmasi.');
    }

    public function cancel(Order $order)
    {
        try {
            $this->orderService->cancelOrder($order);
            return redirect()->back()->with('success', 'Transaksi berhasil dibatalkan dan stok dikembalikan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Generate Invoice PDF (A4 Format) - Dibuka untuk semua status pesanan online
     */
    public function invoicePdf(Order $order)
    {
        $order->load(['customer', 'user', 'items.product', 'kecamatan', 'kelurahan']);
        $pdf = Pdf::loadView('pdf.invoice', compact('order'))->setPaper('a4', 'portrait');
        $safeInvoiceNumber = str_replace('/', '-', $order->invoice_number);
        return $pdf->download("invoice-{$safeInvoiceNumber}.pdf");
    }

    /**
     * Generate Nota PDF (Receipt Roll Format, 80mm width)
     */
    public function notaPdf(Order $order)
    {
        $order->load(['customer', 'user', 'items.product', 'kecamatan', 'kelurahan']);
        $pdf = Pdf::loadView('pdf.nota', compact('order'))->setPaper([0, 0, 226.77, 566.92], 'portrait');
        $safeInvoiceNumber = str_replace('/', '-', $order->invoice_number);
        return $pdf->download("nota-{$safeInvoiceNumber}.pdf");
    }

    /**
     * Generate Nota Online E-commerce PDF (A4 Format khusus pesanan online)
     */
    public function notaOnlinePdf(Order $order)
    {
        $order->load(['customer', 'user', 'items.product', 'kecamatan', 'kelurahan']);
        
        $pdf = Pdf::loadView('pdf.nota_online', compact('order'))->setPaper('a4', 'portrait');
        $safeInvoiceNumber = str_replace('/', '-', $order->invoice_number);
        
        return $pdf->download("Nota-Online-{$safeInvoiceNumber}.pdf");
    }

    /**
     * Clear all transaction history (orders, order items, payments, complaints, returns) for clean testing
     */
    public function clearAllTransactions(Request $request)
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        try {
            \Illuminate\Support\Facades\DB::table('order_items')->truncate();
            \Illuminate\Support\Facades\DB::table('payments')->truncate();
            \Illuminate\Support\Facades\DB::table('complaints')->truncate();
            \Illuminate\Support\Facades\DB::table('returns')->truncate();
            \Illuminate\Support\Facades\DB::table('orders')->truncate();
            if (\Illuminate\Support\Facades\Schema::hasTable('monthly_reports')) {
                \Illuminate\Support\Facades\DB::table('monthly_reports')->truncate();
            }
            if (\Illuminate\Support\Facades\Schema::hasTable('stock_histories')) {
                \Illuminate\Support\Facades\DB::table('stock_histories')->whereIn('type', ['out', 'return'])->delete();
            }
            return redirect()->route('admin.transactions.index')->with('success', 'Semua riwayat transaksi berhasil dikosongkan. Sistem siap untuk pengujian!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal mengosongkan transaksi: ' . $e->getMessage());
        } finally {
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
        }
    }
}