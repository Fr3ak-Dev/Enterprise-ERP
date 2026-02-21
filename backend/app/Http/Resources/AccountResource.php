<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'account_number' => $this->account_number,
            'type' => $this->type,
            'balance' => (float) $this->balance,
            'currency' => $this->currency,
            'description' => $this->description,
            'active' => $this->active,
            'transactions_count' => $this->transactions->count(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}