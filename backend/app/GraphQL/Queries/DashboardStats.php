<?php

namespace App\GraphQL\Queries;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Transaction;

final class DashboardStats
{
    /**
     * Calcula estadísticas para el dashboard
     * 
     * @return array
     */
    public function __invoke()
    {
        $totalProducts = Product::where('active', true)->count();
        $totalCustomers = Customer::where('active', true)->count();
        $totalOrders = Order::count();
        $lowStockProducts = Product::whereRaw('stock <= min_stock')
            ->where('active', true)
            ->count();

        // Ingresos totales (transacciones de ingreso completadas)
        $totalRevenue = Transaction::whereHas('transactionType', function ($query) {
            $query->where('category', 'ingreso');
        })
            ->where('status', 'completada')
            ->sum('amount');

        // Egresos totales (transacciones de egreso completadas)
        $totalExpenses = Transaction::whereHas('transactionType', function ($query) {
            $query->where('category', 'egreso');
        })
            ->where('status', 'completada')
            ->sum('amount');

        // Utilidad neta
        $netProfit = $totalRevenue - $totalExpenses;

        return [
            'total_products' => $totalProducts,
            'total_customers' => $totalCustomers,
            'total_orders' => $totalOrders,
            'low_stock_products' => $lowStockProducts,
            'total_revenue' => (float) $totalRevenue,
            'total_expenses' => (float) $totalExpenses,
            'net_profit' => (float) $netProfit,
        ];
    }
}
