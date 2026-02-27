# EnterpriseERP - Frontend

Sistema ERP moderno con React 19, TypeScript, GraphQL y diseño responsive.

## 🚀 Stack Tecnológico

- **React 19** - Framework UI
- **TypeScript** - Type safety
- **Vite** - Build tool
- **Tailwind CSS** - Styling
- **Apollo Client** - GraphQL client
- **React Router 7** - Routing
- **React Hook Form + Zod** - Form validation
- **Recharts** - Data visualization
- **Lucide React** - Icons

## 📦 Instalación
```bash
# Instalar dependencias
npm install

# Variables de entorno
cp .env.example .env

# Iniciar desarrollo
npm run dev
```

## 🎯 Funcionalidades Implementadas

### ✅ Autenticación
- Login/Register
- JWT tokens
- Protected routes
- Contexto global de usuario

### ✅ Dashboard
- 4 KPI cards
- Gráfico de líneas (movimientos)
- Gráfico circular (categorías)
- Productos con stock bajo (GraphQL real-time)

### ✅ Módulo Productos (CRUD Completo)
- Lista con paginación
- Búsqueda con debounce (500ms)
- Crear/Editar con validación Zod
- Eliminar con confirmación
- Relaciones: Categorías y Proveedores
- Validación custom (precio venta > compra)

## 🔗 Endpoints

- **Frontend:** http://localhost:5173
- **Backend API:** http://localhost:8000/api
- **GraphQL:** http://localhost:8000/graphql