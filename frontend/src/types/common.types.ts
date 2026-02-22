/**
 * Tipos compartidos en toda la aplicación.
 */

export interface Category {
    id: number;
    name: string;
    description: string | null;
    active: boolean;
    products_count?: number;
    created_at: string;
    updated_at: string;
}

export interface Supplier {
    id: number;
    name: string;
    email: string | null;
    phone: string | null;
    address: string | null;
    city: string | null;
    country: string;
    tax_id: string | null;
    notes: string | null;
    active: boolean;
    products_count?: number;
    created_at: string;
}