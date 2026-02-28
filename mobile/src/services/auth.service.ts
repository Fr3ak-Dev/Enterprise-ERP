import apiClient from './api.service';
import type { AuthResponse, User } from '../types';

export const authService = {
    async login(email: string, password: string): Promise<AuthResponse> {
        const response = await apiClient.post<AuthResponse>('/login', {
            email,
            password,
        });
        return response.data;
    },

    async register(name: string, email: string, password: string): Promise<AuthResponse> {
        const response = await apiClient.post<AuthResponse>('/register', {
            name,
            email,
            password,
            password_confirmation: password,
        });
        return response.data;
    },

    async getCurrentUser(): Promise<User> {
        const response = await apiClient.get<{ user: User }>('/me');
        return response.data.user;
    },

    async logout(): Promise<void> {
        await apiClient.post('/logout');
    },
};