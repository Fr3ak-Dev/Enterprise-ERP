<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'discount_percentage' => (float) $this->discount_percentage,
            'active' => $this->active,
            'customers_count' => $this->customers->count(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}