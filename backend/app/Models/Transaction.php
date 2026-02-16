<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'transaction_type_id',
        'customer_id',
        'user_id',
        'amount',
        'transaction_date',
        'reference',
        'description',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function transactionType()
    {
        return $this->belongsTo(TransactionType::class);
    }

    /**
     * Una transacción puede estar relacionada a un cliente
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Una transacción fue registrada por un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}