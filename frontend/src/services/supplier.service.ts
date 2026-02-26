import apiClient from './api.service';
import type { Supplier } from '../types/common.types';

export const supplierService = {
    /**
     * Obtener todos los proveedores activos (sin paginación)
     */
    async getAll() {
        const response = await apiClient.get<{ data: Supplier[] }>(
            '/suppliers?active=1&per_page=100'
        );
        return response.data.data;
    },
};