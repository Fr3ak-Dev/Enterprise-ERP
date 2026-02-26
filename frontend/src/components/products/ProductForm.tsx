import { useState, useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { Button, Input } from '../ui';
import { productService, type Product } from '../../services/product.service';
import { categoryService } from '../../services/category.service';
import { supplierService } from '../../services/supplier.service';
import type { Category, Supplier } from '../../types/common.types';

const productSchema = z.object({
    code: z.string().min(3, 'Mínimo 3 caracteres').max(50, 'Máximo 50 caracteres'),
    name: z.string().min(3, 'Mínimo 3 caracteres').max(255, 'Máximo 255 caracteres'),
    description: z.string().optional(),
    category_id: z.number().positive('Selecciona una categoría'),
    supplier_id: z.number().optional().nullable(),
    purchase_price: z.number().min(0, 'Debe ser mayor o igual a 0'),
    sale_price: z.number().min(0, 'Debe ser mayor o igual a 0'),
    stock: z.number().int('Debe ser entero').min(0, 'Debe ser mayor o igual a 0'),
    min_stock: z.number().int('Debe ser entero').min(0, 'Debe ser mayor o igual a 0'),
    unit: z.string().max(50, 'Máximo 50 caracteres'),
    active: z.boolean().default(true),
}).refine((data) => data.sale_price > data.purchase_price, {
    message: 'El precio de venta debe ser mayor al de compra',
    path: ['sale_price'],
});

type ProductFormData = z.infer<typeof productSchema>;

interface ProductFormProps {
    product: Product | null;
    onSuccess: () => void;
    onCancel: () => void;
}

const ProductForm = ({ product, onSuccess, onCancel }: ProductFormProps) => {
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [categories, setCategories] = useState<Category[]>([]);
    const [suppliers, setSuppliers] = useState<Supplier[]>([]);
    const [isLoadingData, setIsLoadingData] = useState(true);

    const { register, handleSubmit, formState: { errors } } = useForm<ProductFormData>({
        resolver: zodResolver(productSchema) as any,
        // valores del producto existente (editar)
        defaultValues: product ? {
            code: product.code,
            name: product.name,
            description: product.description || '',
            category_id: product.category_id,
            supplier_id: product.supplier_id || undefined,
            purchase_price: product.purchase_price,
            sale_price: product.sale_price,
            stock: product.stock,
            min_stock: product.min_stock,
            unit: product.unit,
            active: product.active,
        } : {   // valores por defecto para nuevo producto (crear)
            code: '',
            name: '',
            description: '',
            category_id: 0,
            purchase_price: 0,
            sale_price: 0,
            stock: 0,
            min_stock: 0,
            unit: 'unidad',
            active: true,
        },
    });

    useEffect(() => {
        const loadData = async () => {
            try {
                setIsLoadingData(true);
                const [categoriesData, suppliersData] = await Promise.all([
                    categoryService.getAll(),
                    supplierService.getAll(),
                ]);
                setCategories(categoriesData);
                setSuppliers(suppliersData);
            } catch (error) {
                console.error('Error:', error);
                alert('Error al cargar datos');
            } finally {
                setIsLoadingData(false);
            }
        };
        loadData();
    }, []);

    const onSubmit = async (data: ProductFormData) => {
        setIsSubmitting(true);
        try {
            const payload = {
                code: data.code,
                name: data.name,
                description: data.description || undefined,
                category_id: data.category_id,
                supplier_id: data.supplier_id || undefined,
                purchase_price: data.purchase_price,
                sale_price: data.sale_price,
                stock: data.stock,
                min_stock: data.min_stock,
                unit: data.unit,
                active: data.active,
            };

            if (product) {
                await productService.update(product.id, payload);
                alert('Producto actualizado exitosamente');
            } else {
                await productService.create(payload);
                alert('Producto creado exitosamente');
            }
            onSuccess();
        } catch (error: any) {
            console.error('Error:', error);
            alert(error.response?.data?.message || 'Error al guardar');
        } finally {
            setIsSubmitting(false);
        }
    };

    if (isLoadingData) {
        return (
            <div className="flex items-center justify-center py-8">
                <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
                <p className="ml-3 text-gray-600">Cargando datos...</p>
            </div>
        );
    }

    return (
        <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input label="Código" {...register('code')} error={errors.code?.message} placeholder="PROD-001" required />
                <Input label="Nombre" {...register('name')} error={errors.name?.message} placeholder="Nombre del producto" required />
            </div>

            <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                <textarea {...register('description')} className={`w-full px-4 py-2 border rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-primary-500 ${errors.description ? 'border-red-500' : 'border-gray-300'}`} rows={3} placeholder="Descripción del producto (opcional)" />
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label htmlFor="category_id" className="block text-sm font-medium text-gray-700 mb-2">Categoría <span className="text-red-500">*</span></label>
                    <select id="category_id" {...register('category_id', { valueAsNumber: true })} className={`w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 ${errors.category_id ? 'border-red-500' : 'border-gray-300'}`}>
                        <option value="">Seleccionar categoría</option>
                        {categories.map((c) => <option key={c.id} value={c.id}>{c.name}</option>)}
                    </select>
                    {errors.category_id && <p className="mt-1 text-xs text-red-600">{errors.category_id.message}</p>}
                </div>

                <div>
                    <label htmlFor="supplier_id" className="block text-sm font-medium text-gray-700 mb-2">Proveedor</label>
                    <select id="supplier_id" {...register('supplier_id', { setValueAs: (v) => v === '' ? undefined : Number(v) })} className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">Sin proveedor</option>
                        {suppliers.map((s) => <option key={s.id} value={s.id}>{s.name}</option>)}
                    </select>
                </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input label="Precio de Compra" type="number" step="0.01" {...register('purchase_price', { valueAsNumber: true })} error={errors.purchase_price?.message} placeholder="0.00" required />
                <Input label="Precio de Venta" type="number" step="0.01" {...register('sale_price', { valueAsNumber: true })} error={errors.sale_price?.message} placeholder="0.00" required />
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                <Input label="Stock" type="number" {...register('stock', { valueAsNumber: true })} error={errors.stock?.message} placeholder="0" required />
                <Input label="Stock Mínimo" type="number" {...register('min_stock', { valueAsNumber: true })} error={errors.min_stock?.message} placeholder="0" required />
                <Input label="Unidad" {...register('unit')} error={errors.unit?.message} placeholder="unidad, kg, litro" required />
            </div>

            <div className="flex items-center gap-2">
                <input id="active" type="checkbox" {...register('active')} className="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500" />
                <label htmlFor="active" className="text-sm text-gray-700">Producto activo</label>
            </div>

            <div className="flex items-center justify-end gap-3 pt-4 border-t">
                <Button type="button" variant="secondary" onClick={onCancel} disabled={isSubmitting}>Cancelar</Button>
                <Button type="submit" isLoading={isSubmitting} disabled={isSubmitting}>{product ? 'Actualizar' : 'Crear'} Producto</Button>
            </div>
        </form>
    );
};

export default ProductForm;