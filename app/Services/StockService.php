<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockHistory;
use Illuminate\Support\Facades\Auth;

class StockService
{
    /**
     * Adjust stock for a product and log in stock history.
     */
    public function adjustStock(Product $product, int $quantityChange, string $type, ?string $description = null): Product
    {
        // Calculate new stock
        $oldStock = $product->stock;
        $newStock = $oldStock + $quantityChange;

        // Prevent negative stock
        if ($newStock < 0) {
            $newStock = 0;
        }

        // Update product stock
        $product->update([
            'stock' => $newStock
        ]);

        // Log to stock history
        StockHistory::create([
            'product_id' => $product->id,
            'type' => $type,
            'quantity' => abs($quantityChange),
            'user_id' => Auth::id() ?? 1, // fallback to user 1 if not logged in (e.g. CLI/seeders)
            'date' => now(),
            'description' => $description ?? "Stock adjusted from {$oldStock} to {$newStock} (Type: {$type})",
        ]);

        return $product;
    }

    /**
     * Get products that are running low on stock.
     */
    public function getLowStockProducts()
    {
        return Product::where('stock', '<=', \DB::raw('min_stock'))->get();
    }
}
