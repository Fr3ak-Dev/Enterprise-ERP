<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'customer' => [
                'id' => $this->customer->id,
                'code' => $this->customer->code,
                'name' => $this->customer->name,
            ],
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'order_date' => $this->order_date->format('Y-m-d'),
            'type' => $this->type,
            'subtotal' => (float) $this->subtotal,
            'discount' => (float) $this->discount,
            'tax' => (float) $this->tax,
            'total' => (float) $this->total,
            'status' => $this->status,
            'notes' => $this->notes,
            'items' => OrderItemResource::collection($this->items),
            'items_count' => $this->items->count(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}