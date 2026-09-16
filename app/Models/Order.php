<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customerUser()
    {
        return $this->belongsTo(User::class, 'customer_user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    /**
     * Get real customer name regardless of online/offline channel
     */
    public function getCustomerDisplayNameAttribute(): string
    {
        if (!empty($this->customer_name)) {
            return $this->customer_name;
        }
        if ($this->customer) {
            return $this->customer->name;
        }
        if ($this->customerUser) {
            return $this->customerUser->name;
        }
        return 'Guest (Walk-in)';
    }

    /**
     * Get real cashier name, ensuring online customer is never shown as cashier
     */
    public function getCashierDisplayNameAttribute(): string
    {
        // If order was created online by customer
        if ($this->customer_user_id) {
            // If an admin has handled/confirmed it (user_id is set and not customer)
            if ($this->user_id && $this->user_id !== $this->customer_user_id && $this->user) {
                return $this->user->name;
            }
            return 'Online';
        }

        // If regular POS sale
        if ($this->user) {
            return $this->user->name;
        }

        return $this->shipping_cost > 0 || !empty($this->shareloc_link) ? 'Online' : 'Kasir Toko';
    }
}