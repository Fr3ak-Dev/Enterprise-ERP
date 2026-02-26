import apiClient from './api.service';

/**
 * Todos los endpoints requieren autenticación (token JWT).
 */

export interface Product {
    id: number;
    code: string;
    name: string;
    description: string | null;
    category_id: number;
    supplier_id: number | null;
    purchase_price: number;
    sale_price: number;
    stock: number;
    min_stock: number;
    unit: string;
    image: string | null;
    active: boolean;
    created_at: string;
    updated_at: string;
    category?: {
        id: number;
        name: string;
    };
    supplier?: {
        id: number;
        name: string;
    };
}

export interface ProductFormData {
    code: string;
    name: string;
    description?: string;
    category_id: number;
    supplier_id?: number;
    purchase_price: number;
    sale_price: number;
    stock: number;
    min_stock: number;
    unit: string;
    active?: boolean;
}

export interface PaginatedResponse<T> {
    data: T[];
    links: {
        first: string;
        last: string;
        prev: string | null;
        next: string | null;
    };
    meta: {
        current_page: number;
        from: number;
        last_page: number;
        per_page: number;
        to: number;
        total: number;
    };
}

export const productService = {

    async getAll(page = 1, perPage = 15, search = '') {
        const params = new URLSearchParams({
            page: page.toString(),
            per_page: perPage.toString(),
        });

        if (search) {
            params.append('search', search);
        }

        const response = await apiClient.get<PaginatedResponse<Product>>(
            `/products?${params.toString()}`
        );
        return response.data;
    },

    async getById(id: number) {
        const response = await apiClient.get<{ success: boolean; data: Product }>(
            `/products/${id}`
        );
        return response.data.data;
    },

    async create(data: ProductFormData) {
        const response = await apiClient.post<{ success: boolean; data: Product }>(
            '/products',
            data
        );
        return response.data;
    },

    async update(id: number, data: ProductFormData) {
        const response = await apiClient.put<{ success: boolean; data: Product }>(
            `/products/${id}`,
            data
        );
        return response.data;
    },

    async delete(id: number) {
        const response = await apiClient.delete<{ success: boolean }>(
            `/products/${id}`
        );
        return response.data;
    },
};