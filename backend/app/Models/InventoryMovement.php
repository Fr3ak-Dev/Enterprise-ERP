<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'unit_price',
        'reference',
        'notes',
        'stock_after',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'stock_after' => 'integer',
    ];

    /**
     * Un movimiento pertenece a un producto
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Un movimiento fue registrado por un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calcular el valor total del movimiento
     */
    public function totalValue()
    {
        return $this->quantity * ($this->unit_price ?? 0);
    }
}