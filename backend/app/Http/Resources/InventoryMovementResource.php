<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryMovementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product' => [
                'id' => $this->product->id,
                'code' => $this->product->code,
                'name' => $this->product->name,
            ],
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'type' => $this->type,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price ? (float) $this->unit_price : null,
            'total_value' => (float) $this->totalValue(),
            'reference' => $this->reference,
            'notes' => $this->notes,
            'stock_after' => $this->stock_after,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}