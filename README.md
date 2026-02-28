# 🏢 EnterpriseERP

Sistema ERP moderno full-stack con backend Laravel, frontend React y app móvil React Native.

## 🚀 Stack Tecnológico

### Backend
- Laravel 12
- SQL Server
- JWT Authentication
- GraphQL (Lighthouse)
- N8N Integration
- PDF/Excel Reports

### Frontend Web
- React 19
- TypeScript
- Apollo Client
- Tailwind CSS
- Recharts
- React Hook Form + Zod

### Mobile
- React Native
- Expo SDK 54
- TypeScript
- React Navigation
- AsyncStorage

## 📚 Módulos Implementados

#### 🔐 Autenticación
- Login con validación
- Registro de usuarios
- JWT tokens (access + refresh)
- Logout
- Persistencia de sesión
- Protected routes

#### 📊 Dashboard
- 4 KPI cards (productos, clientes, órdenes, stock bajo)
- Gráfico de líneas (movimientos de inventario)
- Gráfico circular (productos por categoría)
- Tabla de productos con stock crítico
- Datos en tiempo real via GraphQL

#### 📦 Productos
- CRUD completo (Create, Read, Update, Delete)
- Lista con búsqueda (debounce 500ms)
- Paginación (20 items por página)
- Validaciones:
  - Código único
  - Precio de venta > precio de compra
  - Stock >= 0
- Relaciones:
  - Categorías
  - Proveedores
- Campos calculados:
  - Stock bajo (stock <= min_stock)
  - Margen de ganancia

## 🚀 Características Principales

### ✅ Sistema de Autenticación
- Login/Register con validación
- JWT tokens con refresh
- Persistencia de sesión
- Protección de rutas

### 📊 Dashboard Interactivo
- KPIs en tiempo real (productos, clientes, órdenes, stock bajo)
- Gráficos con Recharts (líneas y circulares)
- Productos con stock crítico (GraphQL)
- Diseño responsive

### 📦 Gestión de Productos (CRUD Completo)
- Crear, editar, eliminar productos
- Búsqueda con debounce (500ms)
- Validación cross-field (precio venta > compra)
- Paginación
- Relaciones (categorías, proveedores)
- Campos calculados (stock bajo, margen)

### 📱 Aplicación Móvil Nativa
- Login con JWT
- Lista de productos con FlatList virtualizada
- Búsqueda con debounce
- Pull to refresh
- Scroll infinito (paginación)
- Persistencia con AsyncStorage

## 📸 Screenshots

### Web Dashboard
![Web Dashboard](screenshots/web_dashboard1.png)
![Web Dashboard Graficos](screenshots/web_dashboard2.png)

### Web Products
![Web Products](screenshots/web_products.png)

### Mobile App
![Mobile App](screenshots/mobile_app.png)

## 🛠️ Instalación

### Backend
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

### Frontend Web
```bash
cd frontend
npm install
cp .env.example .env
npm run dev
```

### Mobile
```bash
cd mobile
npm install
cp .env.example .env
npx expo start
```