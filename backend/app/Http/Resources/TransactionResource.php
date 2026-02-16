<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'account' => [
                'id' => $this->account->id,
                'name' => $this->account->name,
                'account_number' => $this->account->account_number,
            ],
            'transaction_type' => [
                'id' => $this->transactionType->id,
                'name' => $this->transactionType->name,
                'category' => $this->transactionType->category,
            ],
            'customer' => $this->customer ? [
                'id' => $this->customer->id,
                'code' => $this->customer->code,
                'name' => $this->customer->name,
            ] : null,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'amount' => (float) $this->amount,
            'transaction_date' => $this->transaction_date->format('Y-m-d'),
            'reference' => $this->reference,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}