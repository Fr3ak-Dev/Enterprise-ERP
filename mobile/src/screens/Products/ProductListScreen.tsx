import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity } from 'react-native';
import { useAuth } from '../../contexts/AuthContext';
import { Button } from '../../components/common';

const ProductListScreen = () => {
    const { user, logout } = useAuth();

    const handleLogout = async () => {
        await logout();
    };

    return (
        <View style={styles.container}>
            <View style={styles.header}>
                <Text style={styles.title}>Productos</Text>
                <Text style={styles.subtitle}>Bienvenido, {user?.name}</Text>
            </View>

            <View style={styles.content}>
                <Text style={styles.message}>
                    🎉 ¡Login exitoso!{'\n\n'}
                    Lista de productos próximamente...
                </Text>
            </View>

            <View style={styles.footer}>
                <Button
                    title="Cerrar Sesión"
                    onPress={handleLogout}
                    variant="secondary"
                />
            </View>
        </View>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#f9fafb',
    },
    header: {
        backgroundColor: '#fff',
        padding: 24,
        paddingTop: 60,
        borderBottomWidth: 1,
        borderBottomColor: '#e5e7eb',
    },
    title: {
        fontSize: 28,
        fontWeight: 'bold',
        color: '#1f2937',
        marginBottom: 4,
    },
    subtitle: {
        fontSize: 14,
        color: '#6b7280',
    },
    content: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        padding: 24,
    },
    message: {
        fontSize: 18,
        color: '#6b7280',
        textAlign: 'center',
        lineHeight: 28,
    },
    footer: {
        padding: 24,
    },
});

export default ProductListScreen;