<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ============================================
        // TABLA 1: CATEGORIES
        // ============================================
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // ============================================
        // TABLA 2: SUPPLIERS
        // ============================================
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre del proveedor
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Bolivia');
            $table->string('tax_id')->nullable(); // NIT/RUC
            $table->text('notes')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // ============================================
        // TABLA 3: PRODUCTS
        // ============================================
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            
            // Relaciones
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
            
            // Precios
            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->decimal('sale_price', 10, 2)->default(0);
            
            // Inventario
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(0);
            
            // Unidades
            $table->string('unit')->default('unidad'); // unidad, kg, m, etc.
            
            // Metadatos
            $table->string('image')->nullable(); // Ruta de la imagen
            $table->boolean('active')->default(true);
            
            $table->timestamps();
        });

        // ============================================
        // TABLA 4: INVENTORY_MOVEMENTS
        // ============================================
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Quien registró el movimiento
            
            // Tipo de movimiento
            $table->enum('type', ['entrada', 'salida']); // Entrada (compra) o Salida (venta)
            
            // Detalles
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2)->nullable();
            
            // Motivo/Referencia
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            
            // Stock después del movimiento
            $table->integer('stock_after');
            
            $table->timestamps();
        });

        // ============================================
        // TABLA 5: CUSTOMER_TYPES
        // ============================================
        Schema::create('customer_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // "Minorista", "Mayorista", "Corporativo", "VIP", "Socio", etc.
            $table->text('description')->nullable();
            $table->decimal('discount_percentage', 5, 2)->default(0);   // Descuento automático por tipo (ej: mayoristas 10%)
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // ============================================
        // TABLA 6: CUSTOMERS
        // ============================================
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_type_id')->constrained()->onDelete('cascade');
            $table->string('code')->unique();
            $table->string('name');  // Nombre completo o razón social
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Bolivia');
            $table->string('tax_id')->nullable(); // Información fiscal (NIT/RUC)
            // Información financiera
            $table->decimal('credit_limit', 10, 2)->default(0);
            $table->decimal('current_balance', 10, 2)->default(0);  // Saldo actual (positivo = a favor, negativo = deuda)
            // Metadatos
            $table->text('notes')->nullable();
            $table->boolean('active')->default(true);
            
            $table->timestamps();
        });

        // ============================================
        // TABLA 7: ACCOUNTS
        // ============================================
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('account_number')->unique();
            $table->enum('type', ['caja', 'banco', 'otro']);
            $table->decimal('balance', 12, 2)->default(0);
            $table->string('currency')->default('BOB');
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // ============================================
        // TABLA 8: TRANSACTION_TYPES
        // ============================================
        Schema::create('transaction_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['ingreso', 'egreso']);
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // ============================================
        // TABLA 9: TRANSACTIONS
        // ============================================
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->foreignId('transaction_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Quien registró
            // Detalles
            $table->decimal('amount', 12, 2);
            $table->date('transaction_date');
            $table->string('reference')->nullable(); // Referencia (nro factura, etc)
            $table->text('description')->nullable();
            $table->enum('status', ['completada', 'pendiente', 'cancelada'])->default('completada');
            $table->timestamps();
        });

        // ============================================
        // TABLA 10: ORDERS
        // ============================================
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->date('order_date');
            $table->enum('type', ['venta', 'compra']);  // 'venta' (a clientes) o 'compra' (a proveedores)
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->enum('status', ['pendiente', 'procesando', 'completada', 'cancelada'])->default('pendiente');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // ============================================
        // TABLA 11: ORDER_ITEMS
        // ============================================
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar en orden inverso (por las foreign keys)
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('transaction_types');
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('customer_types');
        Schema::dropIfExists('inventory_movements');
        Schema::dropIfExists('products');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('categories');
    }
};