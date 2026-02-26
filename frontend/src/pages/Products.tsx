import { useState, useEffect } from 'react';
import { Plus, Search, Edit2, Trash2 } from 'lucide-react';
import { Button, Input, Table, Badge, Modal } from '../components/ui';
import { productService, type Product } from '../services/product.service';
import ProductForm from '../components/products/ProductForm';

const Products = () => {

    const [products, setProducts] = useState<Product[]>([]);
    const [isLoading, setIsLoading] = useState(true);
    const [search, setSearch] = useState('');
    const [currentPage, setCurrentPage] = useState(1);
    const [totalPages, setTotalPages] = useState(1);

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedProduct, setSelectedProduct] = useState<Product | null>(null);

    const loadProducts = async () => {
        try {
            setIsLoading(true);
            const response = await productService.getAll(currentPage, 15, search);
            setProducts(response.data);
            setTotalPages(response.meta.last_page);
        } catch (error) {
            console.error('Error al cargar productos:', error);
            alert('Error al cargar productos');
        } finally {
            setIsLoading(false);
        }
    };

    useEffect(() => {
        loadProducts();
    }, [currentPage]);

    // Debounce para búsqueda
    useEffect(() => {
        const timer = setTimeout(() => {
            if (currentPage !== 1) {
                setCurrentPage(1); // Esto triggereará el useEffect de arriba
            } else {
                loadProducts(); // Si ya está en página 1, cargar directamente
            }
        }, 500);

        return () => clearTimeout(timer);
    }, [search]);

    // Abrir modal para crear
    const handleCreate = () => {
        setSelectedProduct(null);
        setIsModalOpen(true);
    };

    // Abrir modal para editar
    const handleEdit = (product: Product) => {
        setSelectedProduct(product);
        setIsModalOpen(true);
    };

    // Eliminar producto
    const handleDelete = async (product: Product) => {
        if (!window.confirm(`¿Eliminar el producto "${product.name}"?`)) {
            return;
        }

        try {
            await productService.delete(product.id);
            alert('Producto eliminado exitosamente');
            if (products.length === 1 && currentPage > 1) {
                setCurrentPage(1); // el useEffect recarga automáticamente
            } else {
                loadProducts();
            }
        } catch (error: any) {
            console.error('Error al eliminar:', error);
            alert(error.response?.data?.message || 'Error al eliminar el producto');
        }
    };

    // Cuando se guarda exitosamente
    const handleSaveSuccess = () => {
        setIsModalOpen(false);
        loadProducts();
    };

    const columns = [
        {
            key: 'code',
            label: 'Código',
            className: 'font-mono text-sm',
        },
        {
            key: 'name',
            label: 'Nombre',
            render: (product: Product) => (
                <div>
                    <p className="font-medium text-gray-900">{product.name}</p>
                    {product.description && (
                        <p className="text-sm text-gray-500 truncate max-w-xs">
                            {product.description}
                        </p>
                    )}
                </div>
            ),
        },
        {
            key: 'category',
            label: 'Categoría',
            render: (product: Product) => product.category?.name || '-',
        },
        {
            key: 'stock',
            label: 'Stock',
            render: (product: Product) => (
                <div className="text-center">
                    <p className={`font-semibold ${product.stock <= product.min_stock ? 'text-red-600' : 'text-gray-900'}`}>
                        {product.stock}
                    </p>
                    <p className="text-xs text-gray-500">Min: {product.min_stock}</p>
                </div>
            ),
        },
        {
            key: 'price',
            label: 'Precio',
            render: (product: Product) => (
                <div className="text-right">
                    <p className="text-sm text-gray-500">Compra: Bs. {product.purchase_price.toFixed(2)}</p>
                    <p className="font-semibold text-gray-900">Venta: Bs. {product.sale_price.toFixed(2)}</p>
                </div>
            ),
        },
        {
            key: 'active',
            label: 'Estado',
            render: (product: Product) => (
                <Badge variant={product.active ? 'success' : 'danger'}>
                    {product.active ? 'Activo' : 'Inactivo'}
                </Badge>
            ),
        },
        {
            key: 'actions',
            label: 'Acciones',
            render: (product: Product) => (
                <div className="flex items-center gap-2">
                    <Button
                        size="sm"
                        variant="ghost"
                        onClick={(e) => {
                            e.stopPropagation();
                            handleEdit(product);
                        }}
                    >
                        <Edit2 className="w-4 h-4" />
                    </Button>
                    <Button
                        size="sm"
                        variant="ghost"
                        onClick={(e) => {
                            e.stopPropagation();
                            handleDelete(product);
                        }}
                    >
                        <Trash2 className="w-4 h-4 text-red-600" />
                    </Button>
                </div>
            ),
        },
    ];

    return (
        <div className="space-y-6">

            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-3xl font-bold text-gray-800">Productos</h1>
                    <p className="text-gray-600 mt-1">
                        Gestiona el inventario de productos
                    </p>
                </div>
                <Button onClick={handleCreate}>
                    <Plus className="w-5 h-5 mr-2" />
                    Nuevo Producto
                </Button>
            </div>

            {/* Search */}
            <div className="flex items-center gap-4">
                <div className="flex-1 max-w-md">
                    <div className="relative">
                        <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                        <input
                            type="text"
                            placeholder="Buscar por código o nombre..."
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            className="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                        />
                    </div>
                </div>
            </div>

            <Table
                data={products}
                columns={columns}
                isLoading={isLoading}
                emptyMessage="No se encontraron productos"
            />

            {/* Pagination */}
            {totalPages > 1 && (
                <div className="flex items-center justify-center gap-2">
                    <Button
                        variant="secondary"
                        size="sm"
                        disabled={currentPage === 1}
                        onClick={() => setCurrentPage(currentPage - 1)}
                    >
                        Anterior
                    </Button>
                    <span className="text-sm text-gray-600">
                        Página {currentPage} de {totalPages}
                    </span>
                    <Button
                        variant="secondary"
                        size="sm"
                        disabled={currentPage === totalPages}
                        onClick={() => setCurrentPage(currentPage + 1)}
                    >
                        Siguiente
                    </Button>
                </div>
            )}

            {/* Modal para crear/editar */}
            <Modal
                isOpen={isModalOpen}
                onClose={() => setIsModalOpen(false)}
                title={selectedProduct ? 'Editar Producto' : 'Nuevo Producto'}
                size="lg"
            >
                <ProductForm
                    product={selectedProduct}
                    onSuccess={handleSaveSuccess}
                    onCancel={() => setIsModalOpen(false)}
                />
            </Modal>
        </div>
    );
};

export default Products;