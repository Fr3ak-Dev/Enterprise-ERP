<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        'order_number',
        'order_date',
        'type',
        'subtotal',
        'discount',
        'tax',
        'total',
        'status',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Una orden pertenece a un cliente
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Una orden fue creada por un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Calcular el total de la orden
     */
    public function calculateTotal()
    {
        $this->total = $this->subtotal - $this->discount + $this->tax;
        $this->save();
        return $this->total;
    }
}