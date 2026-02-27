# EnterpriseERP - Backend

API REST + GraphQL con Laravel 12, SQL Server y autenticación JWT.

## 🚀 Stack Tecnológico

- **Laravel 12** - Framework PHP
- **SQL Server** - Base de datos
- **Lighthouse** - GraphQL server
- **JWT Auth** - Autenticación
- **N8N** - Automatizaciones

## 📦 Instalación
```bash
# Instalar dependencias
composer install

# Configurar .env
cp .env.example .env
php artisan key:generate

# Migrar base de datos
php artisan migrate --seed

# Iniciar servidor
php artisan serve
```

## 🗄️ Base de Datos

SQL Server con 10 tablas:
- users
- products
- categories
- suppliers
- customers
- orders
- order_details
- inventory_movements
- reports
- configurations

## 📡 APIs Disponibles

### REST API (endpoints)
- `/api/auth/*` - Autenticación
- `/api/products` - CRUD Productos
- `/api/categories` - Categorías
- `/api/suppliers` - Proveedores
- `/api/customers` - Clientes
- `/api/orders` - Órdenes
- `/api/inventory` - Movimientos

### GraphQL (endpoints)
- `products` - Consultas de productos
- `productsLowStock` - Productos con stock crítico
- `categories` - Categorías
- `suppliers` - Proveedores

## 🔐 Autenticación

JWT con refresh tokens:
- Login: `POST /api/login`
- Register: `POST /api/register`
- Refresh: `POST /api/refresh`
- Logout: `POST /api/logout`

## 📊 Seeders

Datos de prueba incluidos:
- 10 productos
- 5 categorías
- 3 proveedores
- 5 clientes
```bash
php artisan db:seed
```