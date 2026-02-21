<?php

namespace App\GraphQL\Queries;

use App\Models\Product;

final class ProductsLowStock
{
    /**
     * Retorna productos con stock bajo o igual al mínimo
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function __invoke()
    {
        return Product::with(['category', 'supplier'])
            ->whereRaw('stock <= min_stock')
            ->where('active', true)
            ->orderBy('stock', 'asc')
            ->get();
    }
}
