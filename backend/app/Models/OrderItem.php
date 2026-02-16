<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Un item pertenece a una orden
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Un item pertenece a un producto
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calcular el subtotal del item
     */
    public function calculateSubtotal()
    {
        $this->subtotal = ($this->quantity * $this->unit_price) - $this->discount;
        $this->save();
        return $this->subtotal;
    }
}