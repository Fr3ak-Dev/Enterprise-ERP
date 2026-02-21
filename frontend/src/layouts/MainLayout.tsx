import { useState } from 'react';
import { Outlet } from 'react-router-dom';
import Header from '../components/Header';
import Sidebar from '../components/Sidebar';

const MainLayout = () => {
    const [isSidebarOpen, setIsSidebarOpen] = useState(false);

    const toggleSidebar = () => {
        setIsSidebarOpen(!isSidebarOpen);
    };

    const closeSidebar = () => {
        setIsSidebarOpen(false);
    };

    return (
        <div className="min-h-screen bg-gray-50 flex">
            <Sidebar isOpen={isSidebarOpen} onClose={closeSidebar} />

            <div className="flex-1 flex flex-col min-w-0">
                <Header onMenuClick={toggleSidebar} />

                <main className="flex-1 p-4 lg:p-8 overflow-auto">
                    <Outlet />  {/* Renderizar las rutas hijas */}
                </main>

                <footer className="bg-white border-t border-gray-200 py-4 px-4 lg:px-8">
                    <div className="flex flex-col sm:flex-row items-center justify-between gap-2">
                        <p className="text-sm text-gray-600">
                            © 2026 EnterpriseERP. Todos los derechos reservados.
                        </p>
                        <p className="text-xs text-gray-500">
                            Versión 1.0.0
                        </p>
                    </div>
                </footer>
            </div>
        </div>
    );
};

export default MainLayout;