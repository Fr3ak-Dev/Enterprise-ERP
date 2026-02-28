import React, { useState, useEffect } from 'react';
import {
    View,
    Text,
    StyleSheet,
    FlatList,
    TextInput,
    ActivityIndicator,
    RefreshControl,
} from 'react-native';
import { useAuth } from '../../contexts/AuthContext';
import { Button } from '../../components/common';
import ProductCard from '../../components/products/ProductCard';
import { productService } from '../../services/product.service';
import type { Product } from '../../types';

/**
 * - Lista con FlatList optimizada
 * - Búsqueda con debounce
 * - Pull to refresh
 * - Paginación
 * - Loading states
 * - Empty state
 */

const ProductListScreen = () => {
    const { user, logout } = useAuth();

    const [products, setProducts] = useState<Product[]>([]);
    const [search, setSearch] = useState('');
    const [isLoading, setIsLoading] = useState(true);
    const [isRefreshing, setIsRefreshing] = useState(false);
    const [page, setPage] = useState(1);
    const [hasMore, setHasMore] = useState(true);

    useEffect(() => {
        loadProducts(1, search);
    }, []);

    // Debounce para búsqueda
    useEffect(() => {
        const timer = setTimeout(() => {
            loadProducts(1, search);
        }, 500);

        return () => clearTimeout(timer);
    }, [search]);

    const loadProducts = async (pageNum: number = 1, searchQuery: string = '') => {
        try {
            if (pageNum === 1) {
                setIsLoading(true);
            }

            const response = await productService.getProducts(pageNum, searchQuery);

            if (pageNum === 1) {
                setProducts(response.data);
            } else {
                setProducts(prev => [...prev, ...response.data]);
            }

            setPage(pageNum);
            setHasMore(response.meta.current_page < response.meta.last_page);
        } catch (error) {
            console.error('Error al cargar productos:', error);
        } finally {
            setIsLoading(false);
            setIsRefreshing(false);
        }
    };

    /**
     * Pull to refresh
     */
    const handleRefresh = () => {
        setIsRefreshing(true);
        loadProducts(1, search);
    };

    /**
     * Load more (scroll infinito)
     */
    const handleLoadMore = () => {
        if (!isLoading && hasMore) {
            loadProducts(page + 1, search);
        }
    };

    const handleLogout = async () => {
        await logout();
    };

    const renderProduct = ({ item }: { item: Product }) => (
        <ProductCard
            product={item}
            onPress={() => console.log('Producto seleccionado:', item.id)}
        />
    );

    if (isLoading && products.length === 0) {
        return (
            <View style={styles.centerContainer}>
                <ActivityIndicator size="large" color="#3b82f6" />
                <Text style={styles.loadingText}>Cargando productos...</Text>
            </View>
        );
    }

    /**
     * Empty state
     */
    const renderEmpty = () => (
        <View style={styles.emptyContainer}>
            <Text style={styles.emptyIcon}>📦</Text>
            <Text style={styles.emptyTitle}>No hay productos</Text>
            <Text style={styles.emptyText}>
                {search
                    ? `No se encontraron productos con "${search}"`
                    : 'Aún no hay productos registrados'
                }
            </Text>
        </View>
    );

    /**
     * Footer con loading
     */
    const renderFooter = () => {
        if (!hasMore || products.length === 0) return null;

        return (
            <View style={styles.footer}>
                {isLoading ? (
                    <ActivityIndicator color="#3b82f6" />
                ) : (
                    <Text style={styles.footerText}>
                        {products.length} productos cargados
                    </Text>
                )}
            </View>
        );
    };

    return (
        <View style={styles.container}>

            <View style={styles.header}>
                <View>
                    <Text style={styles.title}>Productos</Text>
                    <Text style={styles.subtitle}>Bienvenido, {user?.name}</Text>
                </View>
                <Button
                    title="Salir"
                    onPress={handleLogout}
                    variant="secondary"
                />
            </View>

            <View style={styles.searchContainer}>
                <TextInput
                    style={styles.searchInput}
                    placeholder="Buscar productos..."
                    value={search}
                    onChangeText={setSearch}
                    autoCapitalize="none"
                    autoCorrect={false}
                />
            </View>

            <FlatList
                data={products}
                renderItem={renderProduct}
                keyExtractor={item => item.id.toString()}
                contentContainerStyle={styles.listContent}
                ListEmptyComponent={renderEmpty}
                ListFooterComponent={renderFooter}
                refreshControl={
                    <RefreshControl
                        refreshing={isRefreshing}
                        onRefresh={handleRefresh}
                        colors={['#3b82f6']}
                        tintColor="#3b82f6"
                    />
                }
                onEndReached={handleLoadMore}
                onEndReachedThreshold={0.5}
            />
        </View>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#f9fafb',
    },
    centerContainer: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#f9fafb',
    },
    loadingText: {
        marginTop: 12,
        fontSize: 14,
        color: '#6b7280',
    },
    header: {
        backgroundColor: '#fff',
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
        padding: 16,
        paddingTop: 60,
        borderBottomWidth: 1,
        borderBottomColor: '#e5e7eb',
    },
    title: {
        fontSize: 24,
        fontWeight: 'bold',
        color: '#1f2937',
    },
    subtitle: {
        fontSize: 12,
        color: '#6b7280',
        marginTop: 2,
    },
    searchContainer: {
        padding: 16,
        backgroundColor: '#fff',
    },
    searchInput: {
        backgroundColor: '#f3f4f6',
        borderRadius: 8,
        paddingHorizontal: 16,
        paddingVertical: 12,
        fontSize: 16,
    },
    listContent: {
        padding: 16,
    },
    emptyContainer: {
        alignItems: 'center',
        justifyContent: 'center',
        paddingVertical: 60,
    },
    emptyIcon: {
        fontSize: 64,
        marginBottom: 16,
    },
    emptyTitle: {
        fontSize: 20,
        fontWeight: '600',
        color: '#1f2937',
        marginBottom: 8,
    },
    emptyText: {
        fontSize: 14,
        color: '#6b7280',
        textAlign: 'center',
        paddingHorizontal: 32,
    },
    footer: {
        paddingVertical: 20,
        alignItems: 'center',
    },
    footerText: {
        fontSize: 12,
        color: '#6b7280',
    },
});

export default ProductListScreen;