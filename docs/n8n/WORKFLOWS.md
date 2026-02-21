# N8N Workflows - EnterpriseERP

## Workflow 1: Stock Bajo - Alerta Diaria

**Descripción:** Envía email diario con productos que tienen stock bajo o igual al mínimo.

**Trigger:** Schedule - Todos los días (ajustar hora según timezone de N8N)

**Nodos:**
1. Schedule Trigger
2. HTTP Request → GraphQL (productsLowStock)
3. IF (verificar si hay productos)
4. Set (crear mensaje)
5. Send Email (SMTP)

**Query GraphQL:**
```graphql
query {
  productsLowStock {
    id
    code
    name
    stock
    min_stock
    category {
      name
    }
  }
}
```

**Estado:** ✅ Funcionando
**Última prueba:** 2026-02-21