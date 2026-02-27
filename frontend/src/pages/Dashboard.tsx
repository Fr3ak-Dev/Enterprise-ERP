import { useState, useEffect } from 'react';
import { Package, Users, ShoppingCart, AlertTriangle, TrendingUp, TrendingDown } from 'lucide-react';
import StatCard from '../components/dashboard/StatCard';
import { dashboardService } from '../services/dashboard.service';
import type { DashboardStats, ProductLowStock } from '../types/dashboard.types';

const Dashboard = () => {
    const [stats, setStats] = useState<DashboardStats | null>(null);
    const [lowStockProducts, setLowStockProducts] = useState<ProductLowStock[]>([]);
    const [isLoading, setIsLoading] = useState(true);

    useEffect(() => {
        loadDashboardData();
    }, []);

    const loadDashboardData = async () => {
        try {
            setIsLoading(true);

            // Datos mock hasta crear los endpoints en el backend
            const mockStats: DashboardStats = {
                total_products: 160,
                total_customers: 45,
                total_orders: 230,
                low_stock_products: 8,
                total_inventory_value: 125000,
            };

            const mockLowStock: ProductLowStock[] = [
                { id: 1, code: 'PROD-001', name: 'Fierro Corrugado 12mm', stock: 5, min_stock: 10 },
                { id: 2, code: 'PROD-015', name: 'Cemento Portland', stock: 8, min_stock: 15 },
                { id: 3, code: 'PROD-023', name: 'Arena Fina', stock: 12, min_stock: 20 },
            ];

            setStats(mockStats);
            setLowStockProducts(mockLowStock);
        } catch (error) {
            console.error('Error al cargar dashboard:', error);
        } finally {
            setIsLoading(false);
        }
    };

    if (isLoading) {
        return (
            <div className="flex items-center justify-center h-96">
                <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
            </div>
        );
    }

    return (
        <div className="space-y-6">
            <div>
                <h1 className="text-3xl font-bold text-gray-800">Dashboard</h1>
                <p className="text-gray-600 mt-1">
                    Resumen general del sistema
                </p>
            </div>

            {/* Stats Cards */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <StatCard
                    title="Total Productos"
                    value={stats?.total_products || 0}
                    icon={Package}
                    iconColor="text-blue-600"
                    iconBgColor="bg-blue-100"
                    trend={{ value: 12, isPositive: true }}
                />

                <StatCard
                    title="Clientes"
                    value={stats?.total_customers || 0}
                    icon={Users}
                    iconColor="text-green-600"
                    iconBgColor="bg-green-100"
                    trend={{ value: 8, isPositive: true }}
                />

                <StatCard
                    title="Órdenes"
                    value={stats?.total_orders || 0}
                    icon={ShoppingCart}
                    iconColor="text-purple-600"
                    iconBgColor="bg-purple-100"
                    trend={{ value: 3, isPositive: false }}
                />

                <StatCard
                    title="Stock Bajo"
                    value={stats?.low_stock_products || 0}
                    icon={AlertTriangle}
                    iconColor="text-red-600"
                    iconBgColor="bg-red-100"
                />
            </div>

            {/* Productos con Stock Bajo */}
            <div className="card">
                <div className="flex items-center justify-between mb-4">
                    <h2 className="text-xl font-semibold text-gray-800">
                        Productos con Stock Bajo
                    </h2>
                    <AlertTriangle className="w-5 h-5 text-red-500" />
                </div>

                {lowStockProducts.length === 0 ? (
                    <p className="text-gray-500 text-center py-8">
                        No hay productos con stock bajo
                    </p>
                ) : (
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead className="bg-gray-50">
                                <tr>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Código
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Producto
                                    </th>
                                    <th className="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                        Stock Actual
                                    </th>
                                    <th className="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                        Stock Mínimo
                                    </th>
                                    <th className="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                        Estado
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200">
                                {lowStockProducts.map((product) => (
                                    <tr key={product.id} className="hover:bg-gray-50">
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <span className="font-mono text-sm text-gray-900">
                                                {product.code}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4">
                                            <span className="text-sm text-gray-900">
                                                {product.name}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 text-center">
                                            <span className="text-sm font-semibold text-red-600">
                                                {product.stock}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 text-center">
                                            <span className="text-sm text-gray-500">
                                                {product.min_stock}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 text-center">
                                            <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Crítico
                                            </span>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                )}
            </div>

            {/* Valor Total del Inventario */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="card">
                    <h3 className="text-lg font-semibold text-gray-800 mb-2">
                        Valor Total del Inventario
                    </h3>
                    <p className="text-4xl font-bold text-primary-600">
                        Bs. {stats?.total_inventory_value.toLocaleString()}
                    </p>
                    <div className="flex items-center mt-3">
                        <TrendingUp className="w-4 h-4 text-green-500 mr-1" />
                        <span className="text-sm text-green-600 font-medium">+15.3%</span>
                        <span className="text-xs text-gray-500 ml-2">vs mes anterior</span>
                    </div>
                </div>

                <div className="card">
                    <h3 className="text-lg font-semibold text-gray-800 mb-2">
                        Productos Activos
                    </h3>
                    <p className="text-4xl font-bold text-gray-800">
                        {stats?.total_products || 0}
                    </p>
                    <div className="mt-3">
                        <div className="flex justify-between text-sm mb-1">
                            <span className="text-gray-600">En Stock</span>
                            <span className="font-medium">
                                {((stats?.total_products || 0) - (stats?.low_stock_products || 0))}
                            </span>
                        </div>
                        <div className="w-full bg-gray-200 rounded-full h-2">
                            <div
                                className="bg-green-500 h-2 rounded-full"
                                style={{
                                    width: `${((((stats?.total_products || 0) - (stats?.low_stock_products || 0)) / (stats?.total_products || 1)) * 100)}%`
                                }}
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Dashboard;