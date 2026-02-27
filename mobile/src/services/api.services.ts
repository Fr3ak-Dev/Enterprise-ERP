import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import Constants from 'expo-constants';

/**
 * API Service para comunicación con el backend Laravel
 */

const config = Constants.expoConfig?.extra || {};
const API_URL = Constants.expoConfig?.extra?.apiUrl || 'http://localhost:8000/api';
const API_TIMEOUT = Constants.expoConfig?.extra?.apiTimeout || 10000;

if (__DEV__) {
    console.log('🔧 API Configuration:');
    console.log('   URL:', API_URL);
    console.log('   Timeout:', API_TIMEOUT, 'ms');
    console.log('   Environment:', config.environment);
}

const apiClient = axios.create({
    baseURL: API_URL,
    timeout: API_TIMEOUT,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
});

/**
 * Interceptor de Request antes de cada petición
 */
apiClient.interceptors.request.use(
    async (config) => {
        const token = await AsyncStorage.getItem('token');

        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }

        console.log('📤 Request:', config.method?.toUpperCase(), config.url);
        return config;
    },
    (error) => {
        console.error('❌ Request Error:', error);
        return Promise.reject(error);
    }
);

/**
 * Interceptor de Response despues de cada respuesta
 */
apiClient.interceptors.response.use(
    (response) => {
        console.log('📥 Response:', response.status, response.config.url);
        return response;
    },
    async (error) => {
        console.error('❌ Response Error:', error.response?.status, error.config?.url);

        if (error.response?.status === 401) {
            await AsyncStorage.removeItem('token');
            await AsyncStorage.removeItem('user');
        }

        return Promise.reject(error);
    }
);

export default apiClient;