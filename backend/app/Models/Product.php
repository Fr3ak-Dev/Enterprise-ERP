<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'category_id',
        'supplier_id',
        'purchase_price',
        'sale_price',
        'stock',
        'min_stock',
        'unit',
        'image',
        'active',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer',
        'active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Un producto tiene muchos movimientos de inventario
     */
    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    /**
     * Un producto aparece en muchos items de órdenes
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Verificar si el stock está bajo
     */
    public function isLowStock()
    {
        return $this->stock <= $this->min_stock;
    }

    /**
     * Calcular margen de ganancia
     */
    public function profitMargin()
    {
        if ($this->purchase_price == 0) {
            return 0;
        }
        return (($this->sale_price - $this->purchase_price) / $this->purchase_price) * 100;
    }
}