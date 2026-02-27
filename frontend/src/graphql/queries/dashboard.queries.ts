import { gql } from '@apollo/client';

/**
 * Query para obtener estadísticas del dashboard
 */
export const GET_DASHBOARD_STATS = gql`
  query GetDashboardStats {
    products(first: 1000) {
      data {
        id
        active
        purchase_price
        stock
        min_stock
      }
    }
  }
`;

/**
 * Query para obtener productos con stock bajo
 */
export const GET_LOW_STOCK_PRODUCTS = gql`
  query GetLowStockProducts {
    productsLowStock {
      id
      code
      name
      stock
      min_stock
      is_low_stock
    }
  }
`;