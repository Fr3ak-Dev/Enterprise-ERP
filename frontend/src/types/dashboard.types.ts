export interface DashboardStats {
    total_products: number;
    total_customers: number;
    total_orders: number;
    low_stock_products: number;
    total_inventory_value: number;
}

export interface ProductLowStock {
    id: number;
    code: string;
    name: string;
    stock: number;
    min_stock: number;
}

export interface InventoryMovementChart {
    date: string;
    entries: number;
    exits: number;
    adjustments: number;
}