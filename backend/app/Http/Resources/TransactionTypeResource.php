<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'description' => $this->description,
            'active' => $this->active,
            'transactions_count' => $this->transactions->count(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}