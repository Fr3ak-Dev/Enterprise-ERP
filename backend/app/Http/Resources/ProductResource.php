<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ],
            'supplier' => $this->supplier ? [
                'id' => $this->supplier->id,
                'name' => $this->supplier->name,
            ] : null,
            'purchase_price' => (float) $this->purchase_price,
            'sale_price' => (float) $this->sale_price,
            'profit_margin' => round($this->profitMargin(), 2),
            'stock' => $this->stock,
            'min_stock' => $this->min_stock,
            'is_low_stock' => $this->isLowStock(),
            'unit' => $this->unit,
            'image' => $this->image,
            'active' => $this->active,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}