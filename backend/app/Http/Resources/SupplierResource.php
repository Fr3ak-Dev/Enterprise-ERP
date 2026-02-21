<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'tax_id' => $this->tax_id,
            'notes' => $this->notes,
            'active' => $this->active,
            'products_count' => $this->products->count(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}