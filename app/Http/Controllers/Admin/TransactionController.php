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
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $query = Order::with(['customer', 'user', 'items']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
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
        $order->load(['customer', 'user', 'items', 'payments']);
        return view('admin.transactions.show', compact('order'));
    }

    public function updatePaymentStatus(Order $order, Request $request)
    {
        if ($order->status !== 'Belum Dibayar') {
            return redirect()->back()->with('error', 'Transaksi ini sudah lunas atau dibatalkan.');
        }

        $order->update([
            'status' => 'Lunas'
        ]);

        Payment::create([
            'order_id' => $order->id,
            'payment_method' => $request->input('payment_method', 'Cash'),
            'amount_paid' => $order->total_amount,
            'payment_status' => 'Lunas',
            'payment_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
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
     * Generate Invoice PDF (A4 Format)
     */
    public function invoicePdf(Order $order)
    {
        $order->load(['customer', 'user', 'items']);
        $pdf = Pdf::loadView('pdf.invoice', compact('order'))->setPaper('a4', 'portrait');
        $safeInvoiceNumber = str_replace('/', '-', $order->invoice_number);
        return $pdf->download("invoice-{$safeInvoiceNumber}.pdf");
    }

    /**
     * Generate Nota PDF (Receipt Roll Format, 80mm width)
     */
    public function notaPdf(Order $order)
    {
        $order->load(['customer', 'user', 'items']);
        // Custom paper size: 80mm width x 200mm height
        $pdf = Pdf::loadView('pdf.nota', compact('order'))->setPaper([0, 0, 226.77, 566.92], 'portrait');
        $safeInvoiceNumber = str_replace('/', '-', $order->invoice_number);
        return $pdf->download("nota-{$safeInvoiceNumber}.pdf");
    }
}
