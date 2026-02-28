import React from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { View, ActivityIndicator, StyleSheet } from 'react-native';
import { useAuth } from '../contexts/AuthContext';

import LoginScreen from '../screens/Auth/LoginScreen';
import ProductListScreen from '../screens/Products/ProductListScreen';

const Stack = createNativeStackNavigator();

const AppNavigator = () => {
    const { user, isLoading } = useAuth();
    if (isLoading) {
        return (
            <View style={styles.loadingContainer}>
                <ActivityIndicator size="large" color="#3b82f6" />
            </View>
        );
    }

    return (
        <NavigationContainer>
            <Stack.Navigator
                screenOptions={{
                    headerShown: false,
                }}
            >
                {user ? (
                    // Usuario autenticado
                    <Stack.Screen name="Products" component={ProductListScreen} />
                ) : (
                    // Usuario no autenticado
                    <Stack.Screen name="Login" component={LoginScreen} />
                )}
            </Stack.Navigator>
        </NavigationContainer>
    );
};

const styles = StyleSheet.create({
    loadingContainer: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#f9fafb',
    },
});

export default AppNavigator;