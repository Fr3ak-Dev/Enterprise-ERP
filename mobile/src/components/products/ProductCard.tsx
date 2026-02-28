import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity } from 'react-native';
import type { Product } from '../../types';

/**
 * Muestra:
 * - Código y nombre
 * - Precio
 * - Stock con indicador de color
 * - Badge si stock bajo
 */

interface ProductCardProps {
    product: Product;
    onPress?: () => void;
}

const ProductCard = ({ product, onPress }: ProductCardProps) => {

    const stockColor = product.is_low_stock ? '#ef4444' : '#10b981';

    return (
        <TouchableOpacity
            style={styles.card}
            onPress={onPress}
            activeOpacity={0.7}
        >
            {/* Código y Badge */}
            <View style={styles.header}>
                <Text style={styles.code}>{product.code}</Text>
                {product.is_low_stock && (
                    <View style={styles.badge}>
                        <Text style={styles.badgeText}>Stock Bajo</Text>
                    </View>
                )}
            </View>

            {/* Nombre */}
            <Text style={styles.name} numberOfLines={2}>
                {product.name}
            </Text>

            {/* Precio y Stock */}
            <View style={styles.infoRow}>

                <View style={styles.priceContainer}>
                    <Text style={styles.priceLabel}>Precio</Text>
                    <Text style={styles.priceValue}>
                        Bs. {product.sale_price.toFixed(2)}
                    </Text>
                </View>

                <View style={styles.stockContainer}>
                    <Text style={styles.stockLabel}>Stock</Text>
                    <Text style={[styles.stockValue, { color: stockColor }]}>
                        {product.stock} {product.unit}
                    </Text>
                </View>
            </View>

            {/* Categoría */}
            {product.category && (
                <Text style={styles.category}>
                    📦 {product.category.name}
                </Text>
            )}
        </TouchableOpacity>
    );
};

const styles = StyleSheet.create({
    card: {
        backgroundColor: '#fff',
        borderRadius: 12,
        padding: 16,
        marginBottom: 12,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.1,
        shadowRadius: 4,
        elevation: 3,
    },
    header: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
        marginBottom: 8,
    },
    code: {
        fontSize: 12,
        fontFamily: 'monospace',
        color: '#6b7280',
        backgroundColor: '#f3f4f6',
        paddingHorizontal: 8,
        paddingVertical: 4,
        borderRadius: 4,
    },
    badge: {
        backgroundColor: '#fee2e2',
        paddingHorizontal: 8,
        paddingVertical: 4,
        borderRadius: 4,
    },
    badgeText: {
        fontSize: 10,
        fontWeight: '600',
        color: '#dc2626',
    },
    name: {
        fontSize: 16,
        fontWeight: '600',
        color: '#1f2937',
        marginBottom: 12,
        lineHeight: 22,
    },
    infoRow: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        marginBottom: 8,
    },
    priceContainer: {
        flex: 1,
    },
    priceLabel: {
        fontSize: 12,
        color: '#6b7280',
        marginBottom: 2,
    },
    priceValue: {
        fontSize: 18,
        fontWeight: 'bold',
        color: '#3b82f6',
    },
    stockContainer: {
        flex: 1,
        alignItems: 'flex-end',
    },
    stockLabel: {
        fontSize: 12,
        color: '#6b7280',
        marginBottom: 2,
    },
    stockValue: {
        fontSize: 16,
        fontWeight: '600',
    },
    category: {
        fontSize: 12,
        color: '#6b7280',
        marginTop: 4,
    },
});

export default ProductCard;