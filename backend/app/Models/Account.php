<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'account_number',
        'type',
        'balance',
        'currency',
        'description',
        'active',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}