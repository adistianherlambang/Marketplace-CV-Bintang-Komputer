<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ReturnLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Create an order with items.
     * Items can be db products (Method 1) or manual items (Method 2).
     */
    public function createOrder(array $data, array $items): Order
    {
        return DB::transaction(function () use ($data, $items) {
            // 1. Generate unique invoice number
            $datePrefix = now()->format('Ymd');
            $countToday = Order::whereDate('created_at', now()->toDateString())->count() + 1;
            $invoiceNumber = 'INV/' . $datePrefix . '/' . str_pad($countToday, 4, '0', STR_PAD_LEFT);

            // 2. Create Order
            $order = Order::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $data['customer_id'] ?? null,
                'user_id' => Auth::id() ?? 1,
                'status' => $data['status'] ?? 'Belum Dibayar',
                'total_amount' => 0, // will calculate below
                'notes' => $data['notes'] ?? null,
            ]);

            $totalAmount = 0;

            // 3. Create Order Items and Adjust Stock
            foreach ($items as $item) {
                $productId = $item['product_id'] ?? null;
                $price = (float)$item['price'];
                $qty = (int)$item['quantity'];
                $subtotal = $price * $qty;
                $totalAmount += $subtotal;

                $productName = $item['item_name'] ?? '';

                if ($productId) {
                    $product = Product::findOrFail($productId);
                    $productName = $product->name; // ensure name matches database product
                    
                    // Deduct stock for database products
                    $this->stockService->adjustStock(
                        $product,
                        -$qty,
                        'out',
                        "Purchased in transaction {$invoiceNumber}"
                    );
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'item_name' => $productName,
                    'price' => $price,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                ]);
            }

            // 4. Update Order Total Amount
            $order->update([
                'total_amount' => $totalAmount
            ]);

            // 5. Create Payment record if fully paid (Lunas)
            if ($order->status === 'Lunas') {
                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => $data['payment_method'] ?? 'Cash',
                    'amount_paid' => $totalAmount,
                    'payment_status' => 'Lunas',
                    'payment_date' => now(),
                ]);
            }

            return $order;
        });
    }

    /**
     * Cancel an order and restore stock.
     */
    public function cancelOrder(Order $order): Order
    {
        if ($order->status === 'Dibatalkan') {
            throw new \Exception("Invoice ini sudah dibatalkan.");
        }

        return DB::transaction(function () use ($order) {
            // Restore stock for database products
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $this->stockService->adjustStock(
                            $product,
                            $item->quantity,
                            'return',
                            "Restored stock from cancelled transaction {$order->invoice_number}"
                        );
                    }
                }
            }

            // Update order status
            $order->update([
                'status' => 'Dibatalkan'
            ]);

            return $order;
        });
    }

    /**
     * Process return and restore stock.
     */
    public function processReturn(Order $order, Product $product, int $qty, string $reason, string $status = 'Menunggu'): ReturnLog
    {
        return DB::transaction(function () use ($order, $product, $qty, $reason, $status) {
            $returnLog = ReturnLog::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $qty,
                'reason' => $reason,
                'status' => $status,
                'date' => now(),
            ]);

            if ($status === 'Disetujui') {
                $this->stockService->adjustStock(
                    $product,
                    $qty,
                    'return',
                    "Restored stock from approved return of order {$order->invoice_number}"
                );
            }

            return $returnLog;
        });
    }

    /**
     * Approve an existing pending return.
     */
    public function approveReturn(ReturnLog $returnLog): ReturnLog
    {
        if ($returnLog->status !== 'Menunggu') {
            throw new \Exception("Retur ini sudah diproses.");
        }

        return DB::transaction(function () use ($returnLog) {
            $returnLog->update([
                'status' => 'Disetujui'
            ]);

            $this->stockService->adjustStock(
                $returnLog->product,
                $returnLog->quantity,
                'return',
                "Restored stock from approved return of order {$returnLog->order->invoice_number}"
            );

            return $returnLog;
        });
    }

    /**
     * Reject an existing pending return.
     */
    public function rejectReturn(ReturnLog $returnLog): ReturnLog
    {
        if ($returnLog->status !== 'Menunggu') {
            throw new \Exception("Retur ini sudah diproses.");
        }

        $returnLog->update([
            'status' => 'Ditolak'
        ]);

        return $returnLog;
    }
}
