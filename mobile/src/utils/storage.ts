import AsyncStorage from '@react-native-async-storage/async-storage';

export const storage = {
    async setToken(token: string): Promise<void> {
        await AsyncStorage.setItem('token', token);
    },

    async getToken(): Promise<string | null> {
        return await AsyncStorage.getItem('token');
    },

    async setUser(user: any): Promise<void> {
        await AsyncStorage.setItem('user', JSON.stringify(user));
    },

    async getUser(): Promise<any | null> {
        const userStr = await AsyncStorage.getItem('user');
        return userStr ? JSON.parse(userStr) : null;
    },

    async clear(): Promise<void> {
        await AsyncStorage.multiRemove(['token', 'user']);
    },
};