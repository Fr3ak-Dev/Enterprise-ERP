import apiClient from './api.service';
import type { LoginCredentials, RegisterData, AuthResponse } from '../types/auth.types';

export const authService = {

    async login(credentials: LoginCredentials): Promise<AuthResponse> {
        const response = await apiClient.post<AuthResponse>('/login', credentials);
        return response.data;
    },

    async register(data: RegisterData): Promise<AuthResponse> {
        const response = await apiClient.post<AuthResponse>('/register', data);
        return response.data;
    },

    async logout(): Promise<void> {
        await apiClient.post('/logout');
    },

    async me() {
        const response = await apiClient.get('/me');
        return response.data;
    },

    async refresh(): Promise<AuthResponse> {
        const response = await apiClient.post<AuthResponse>('/refresh');
        return response.data;
    },
};