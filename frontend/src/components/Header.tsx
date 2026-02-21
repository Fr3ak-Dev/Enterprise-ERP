import { useAuth } from '../contexts/AuthContext';
import { Menu, LogOut, User, Bell } from 'lucide-react';

type HeaderProps = {
    onMenuClick: () => void;  // Función para abrir/cerrar sidebar en móvil
};

const Header = ({ onMenuClick }: HeaderProps) => {
    const { user, logout } = useAuth();

    const handleLogout = () => {
        if (window.confirm('¿Estás seguro de cerrar sesión?')) {
            logout();
        }
    };

    return (
        <header className="bg-white shadow-sm sticky top-0 z-10">
            <div className="flex items-center justify-between px-4 py-3">
                {/* Left: Menu button + Logo */}
                <div className="flex items-center gap-4">
                    {/* Botón de menú (solo visible en móvil) */}
                    <button
                        onClick={onMenuClick}
                        className="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors"
                        aria-label="Toggle menu"
                    >
                        <Menu className="w-6 h-6 text-gray-600" />
                    </button>

                    {/* Logo y nombre */}
                    <div className="flex items-center gap-2">
                        <div className="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                            <span className="text-white font-bold text-lg">E</span>
                        </div>
                        <span className="font-bold text-xl text-gray-800 hidden sm:block">
                            EnterpriseERP
                        </span>
                    </div>
                </div>

                {/* Right: Notifications + User menu */}
                <div className="flex items-center gap-4">
                    {/* Notificaciones */}
                    <button
                        className="p-2 rounded-lg hover:bg-gray-100 transition-colors relative"
                        aria-label="Notificaciones"
                    >
                        <Bell className="w-5 h-5 text-gray-600" />
                        {/* Badge de notificaciones */}
                        <span className="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    {/* User menu */}
                    <div className="flex items-center gap-3 pl-3 border-l border-gray-200">
                        <div className="hidden sm:block text-right">
                            <p className="text-sm font-medium text-gray-800">
                                {user?.name || 'Usuario'}
                            </p>
                            <p className="text-xs text-gray-500">
                                {user?.email || ''}
                            </p>
                        </div>

                        <div className="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                            <User className="w-5 h-5 text-primary-600" />
                        </div>

                        {/* Botón de logout */}
                        <button
                            onClick={handleLogout}
                            className="p-2 rounded-lg hover:bg-red-50 transition-colors group"
                            aria-label="Cerrar sesión"
                        >
                            <LogOut className="w-5 h-5 text-gray-600 group-hover:text-red-600" />
                        </button>
                    </div>
                </div>
            </div>
        </header>
    );
};

export default Header;