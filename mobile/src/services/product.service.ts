import apiClient from './api.service';
import type { Product, PaginatedResponse } from '../types';

export const productService = {

    async getProducts(page: number = 1, search: string = ''): Promise<PaginatedResponse<Product>> {
        const response = await apiClient.get<PaginatedResponse<Product>>('/products', {
            params: {
                page,
                per_page: 20,
                search,
            },
        });
        return response.data;
    },

    async getProduct(id: number): Promise<Product> {
        const response = await apiClient.get<{ data: Product }>(`/products/${id}`);
        return response.data.data;
    },

    async searchProducts(query: string, page: number = 1): Promise<PaginatedResponse<Product>> {
        return this.getProducts(page, query);
    },
};