import apiClient from './api.service';
import type { Category } from '../types/common.types';

export const categoryService = {
    /**
     * Obtener todas las categorías activas (sin paginación)
     */
    async getAll() {
        const response = await apiClient.get<{ data: Category[] }>(
            '/categories?active=1&per_page=100'
        );
        return response.data.data;
    },
};