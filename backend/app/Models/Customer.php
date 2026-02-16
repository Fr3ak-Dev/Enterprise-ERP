<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'email',
        'phone',
        'customer_type_id',
        'address',
        'city',
        'country',
        'tax_id',
        'credit_limit',
        'current_balance',
        'notes',
        'active',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function customerType()
    {
        return $this->belongsTo(CustomerType::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Verificar si el cliente puede comprar a crédito
     */
    public function canBuyOnCredit($amount)
    {
        return ($this->current_balance + $amount) <= $this->credit_limit;
    }

    /**
     * Verificar si el cliente tiene deuda
     */
    public function hasDebt()
    {
        return $this->current_balance < 0;
    }
}