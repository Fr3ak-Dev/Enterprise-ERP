import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider } from './contexts/AuthContext';
import Login from './pages/Login';
import Register from './pages/Register';
import ProtectedRoute from './components/ProtectedRoute';
import MainLayout from './layouts/MainLayout';

const Dashboard = () => {
  return (
    <div>
      <h1 className="text-3xl font-bold text-gray-800 mb-4">Dashboard</h1>
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div className="card">
          <p className="text-sm text-gray-600">Total Productos</p>
          <p className="text-3xl font-bold text-gray-800 mt-2">150</p>
        </div>
        <div className="card">
          <p className="text-sm text-gray-600">Clientes</p>
          <p className="text-3xl font-bold text-gray-800 mt-2">45</p>
        </div>
        <div className="card">
          <p className="text-sm text-gray-600">Órdenes</p>
          <p className="text-3xl font-bold text-gray-800 mt-2">230</p>
        </div>
        <div className="card">
          <p className="text-sm text-gray-600">Stock Bajo</p>
          <p className="text-3xl font-bold text-red-600 mt-2">8</p>
        </div>
      </div>
    </div>
  );
};

const Products = () => <div><h1 className="text-3xl font-bold">Productos</h1></div>;
const Customers = () => <div><h1 className="text-3xl font-bold">Clientes</h1></div>;
const Orders = () => <div><h1 className="text-3xl font-bold">Órdenes</h1></div>;

function App() {
  return (
    <BrowserRouter>
      <AuthProvider>
        <Routes>
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
          {/* Rutas protegidas con Layout */}
          <Route
            path="/"
            element={
              <ProtectedRoute>
                <MainLayout />
              </ProtectedRoute>
            }
          >
            {/* Rutas hijas */}
            <Route index element={<Navigate to="/dashboard" replace />} />
            <Route path="dashboard" element={<Dashboard />} />
            <Route path="products" element={<Products />} />
            <Route path="customers" element={<Customers />} />
            <Route path="orders" element={<Orders />} />
          </Route>
          {/* 404 - Ruta no encontrada */}
          <Route path="*" element={<Navigate to="/login" replace />} />
        </Routes>
      </AuthProvider>
    </BrowserRouter>
  );
}

export default App;