import React, { createContext, useState, useEffect, useContext, ReactNode } from 'react';
import { authService } from '../services/auth.service';
import { storage } from '../utils/storage';
import type { User } from '../types';

interface AuthContextData {
    user: User | null;
    isLoading: boolean;
    login: (email: string, password: string) => Promise<void>;
    logout: () => Promise<void>;
}

const AuthContext = createContext<AuthContextData>({} as AuthContextData);

export const AuthProvider = ({ children }: { children: ReactNode }) => {
    const [user, setUser] = useState<User | null>(null);
    const [isLoading, setIsLoading] = useState(true);

    /**
     * Al montar, verificar si hay token guardado
     */
    useEffect(() => {
        loadStoredAuth();
    }, []);

    /**
     * Cargar autenticación guardada
     */
    const loadStoredAuth = async () => {
        try {
            const token = await storage.getToken();
            const storedUser = await storage.getUser();

            if (token && storedUser) {
                setUser(storedUser);
            }
        } catch (error) {
            console.error('Error al cargar autenticación:', error);
        } finally {
            setIsLoading(false);
        }
    };

    const login = async (email: string, password: string) => {
        try {
            const response = await authService.login(email, password);

            await storage.setToken(response.token);
            await storage.setUser(response.user);

            setUser(response.user);
        } catch (error: any) {
            throw new Error(error.response?.data?.message || 'Error al iniciar sesión');
        }
    };

    const logout = async () => {
        try {
            await authService.logout();
        } catch (error) {
            console.error('Error al cerrar sesión:', error);
        } finally {
            await storage.clear();
            setUser(null);
        }
    };

    return (
        <AuthContext.Provider value={{ user, isLoading, login, logout }}>
            {children}
        </AuthContext.Provider>
    );
};

export const useAuth = () => {
    const context = useContext(AuthContext);

    if (!context) {
        throw new Error('useAuth debe usarse dentro de AuthProvider');
    }

    return context;
};