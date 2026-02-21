import { NavLink } from 'react-router-dom';
import {
    LayoutDashboard,
    Package,
    Users,
    ShoppingCart,
    Warehouse,
    DollarSign,
    FileText,
    Settings,
    X,
} from 'lucide-react';

type SidebarProps = {
    isOpen: boolean;
    onClose: () => void;
};

const Sidebar = ({ isOpen, onClose }: SidebarProps) => {

    const menuItems = [
        {
            name: 'Dashboard',
            path: '/dashboard',
            icon: LayoutDashboard,
        },
        {
            name: 'Productos',
            path: '/products',
            icon: Package,
        },
        {
            name: 'Clientes',
            path: '/customers',
            icon: Users,
        },
        {
            name: 'Órdenes',
            path: '/orders',
            icon: ShoppingCart,
        },
        {
            name: 'Inventario',
            path: '/inventory',
            icon: Warehouse,
        },
        {
            name: 'Transacciones',
            path: '/transactions',
            icon: DollarSign,
        },
        {
            name: 'Reportes',
            path: '/reports',
            icon: FileText,
        },
        {
            name: 'Configuración',
            path: '/settings',
            icon: Settings,
        },
    ];

    return (
        <>
            {/* Overlay (fondo oscuro en móvil) */}
            {isOpen && (
                <div
                    className="fixed inset-0 bg-black/50 z-20 lg:hidden"
                    onClick={onClose}
                />
            )}

            {/* Sidebar */}
            <aside
                className={`
          fixed lg:sticky top-0 left-0 z-30
          w-64 h-screen bg-white border-r border-gray-200
          transition-transform duration-300 ease-in-out
          ${isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'}
        `}
            >
                {/* Header del sidebar (solo en móvil) */}
                <div className="flex items-center justify-between p-4 border-b border-gray-200 lg:hidden">
                    <span className="font-bold text-xl text-gray-800">Menú</span>
                    <button
                        onClick={onClose}
                        className="p-2 rounded-lg hover:bg-gray-100"
                    >
                        <X className="w-5 h-5 text-gray-600" />
                    </button>
                </div>

                {/* Navigation */}
                <nav className="p-4 space-y-2 overflow-y-auto h-[calc(100vh-80px)] lg:h-[calc(100vh-64px)]">
                    {menuItems.map((item) => {
                        const Icon = item.icon;
                        return (
                            <NavLink
                                key={item.path}
                                to={item.path}
                                onClick={onClose}  // Cerrar sidebar en móvil al hacer click
                                className={({ isActive }) =>
                                    `flex items-center gap-3 px-4 py-3 rounded-lg transition-colors ${isActive
                                        ? 'bg-primary-50 text-primary-600 font-medium'
                                        : 'text-gray-600 hover:bg-gray-50'
                                    }`
                                }
                            >
                                <Icon className="w-5 h-5" />
                                <span>{item.name}</span>
                            </NavLink>
                        );
                    })}
                </nav>
            </aside>
        </>
    );
};

export default Sidebar;