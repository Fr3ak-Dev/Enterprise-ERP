import apiClient from './api.service';
import type { DashboardStats, ProductLowStock, InventoryMovementChart } from '../types/dashboard.types';

export const dashboardService = {

    async getStats() {
        const response = await apiClient.get<{ data: DashboardStats }>('/dashboard/stats');
        return response.data.data;
    },

    async getLowStockProducts() {
        const response = await apiClient.get<{ data: ProductLowStock[] }>('/products/low-stock');
        return response.data.data;
    },

    /**
     * Obtener datos para gráfico de movimientos
     */
    async getInventoryMovements(days: number = 7) {
        const response = await apiClient.get<{ data: InventoryMovementChart[] }>(
            `/dashboard/inventory-movements?days=${days}`
        );
        return response.data.data;
    },
};