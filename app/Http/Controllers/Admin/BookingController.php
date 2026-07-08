<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductBooking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of product bookings.
     */
    public function index(Request $request)
    {
        $query = ProductBooking::with('product')->latest();

        // Search query
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(10)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Update the status and admin notes of a product booking.
     */
    public function updateStatus(Request $request, ProductBooking $booking)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Diproses,Selesai,Dibatalkan',
            'notes' => 'nullable|string|max:1000',
        ]);

        $booking->update([
            'status' => $request->status,
            'notes_internal' => $request->notes,
        ]);

        return redirect()->route('admin.bookings.index')->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
