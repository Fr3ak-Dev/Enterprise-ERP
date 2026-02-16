<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'discount_percentage',
        'active',
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}