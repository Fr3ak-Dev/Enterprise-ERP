export interface User {
    id: number;
    name: string;
    email: string;
}

export interface AuthResponse {
    access_token: string;
    token_type: string;
    expires_in: number;
    user: User;
}

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
    // Relaciones
    category?: {
        id: number;
        name: string;
    };
    supplier?: {
        id: number;
        name: string;
    };
    // Campos calculados
    is_low_stock?: boolean;
    profit_margin?: number;
}

export interface PaginatedResponse<T> {
    data: T[];
    meta: {
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
}