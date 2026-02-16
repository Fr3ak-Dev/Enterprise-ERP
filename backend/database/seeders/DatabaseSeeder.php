<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\CustomerType;
use App\Models\Customer;
use App\Models\Account;
use App\Models\TransactionType;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ============================================
        // USUARIOS
        // ============================================
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@enterpriseerp.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Juan Arteaga',
            'email' => 'juan@example.com',
            'password' => Hash::make('123456'),
        ]);

        // ============================================
        // CATEGORÍAS
        // ============================================
        $categories = [
            ['name' => 'Acero y Metales', 'description' => 'Productos de acero, hierro y metales', 'active' => true],
            ['name' => 'Materiales de Construcción', 'description' => 'Cemento, arena, piedra', 'active' => true],
            ['name' => 'Herramientas', 'description' => 'Herramientas para construcción', 'active' => true],
            ['name' => 'Productos Financieros', 'description' => 'Cajas de ahorro, créditos, DPFs', 'active' => true],
            ['name' => 'Servicios', 'description' => 'Servicios de corte, doblado', 'active' => true],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // ============================================
        // PROVEEDORES
        // ============================================
        $suppliers = [
            [
                'name' => 'ArcelorMittal Bolivia',
                'email' => 'ventas@arcelormittal.com.bo',
                'phone' => '3-3334455',
                'address' => 'Av. Grigotá 456',
                'city' => 'Santa Cruz',
                'country' => 'Bolivia',
                'tax_id' => '1234567890',
                'notes' => 'Proveedor principal de acero',
                'active' => true,
            ],
            [
                'name' => 'Distribuidora Ferretería Total',
                'email' => 'info@ferretotal.com',
                'phone' => '3-3445566',
                'address' => 'Av. Santos Dumont 789',
                'city' => 'Santa Cruz',
                'country' => 'Bolivia',
                'tax_id' => '9876543210',
                'notes' => 'Herramientas y materiales varios',
                'active' => true,
            ],
            [
                'name' => 'Importadora ABC',
                'email' => 'contacto@abc.com',
                'phone' => '3-3556677',
                'address' => 'Calle Warnes 321',
                'city' => 'Santa Cruz',
                'country' => 'Bolivia',
                'tax_id' => '5555555555',
                'active' => true,
            ],
        ];

        foreach ($suppliers as $sup) {
            Supplier::create($sup);
        }

        // ============================================
        // PRODUCTOS
        // ============================================
        $products = [
            [
                'code' => 'PROD-001',
                'name' => 'Fierro Corrugado 12mm',
                'description' => 'Fierro corrugado de 12mm para construcción',
                'category_id' => 1,
                'supplier_id' => 1,
                'purchase_price' => 45.00,
                'sale_price' => 55.00,
                'stock' => 500,
                'min_stock' => 100,
                'unit' => 'unidad',
                'active' => true,
            ],
            [
                'code' => 'PROD-002',
                'name' => 'Calamina Ondulada 2.5m',
                'description' => 'Calamina ondulada galvanizada',
                'category_id' => 2,
                'supplier_id' => 1,
                'purchase_price' => 85.00,
                'sale_price' => 110.00,
                'stock' => 200,
                'min_stock' => 50,
                'unit' => 'unidad',
                'active' => true,
            ],
            [
                'code' => 'PROD-003',
                'name' => 'Cemento Portland 50kg',
                'description' => 'Cemento portland bolsa de 50kg',
                'category_id' => 2,
                'supplier_id' => 2,
                'purchase_price' => 52.00,
                'sale_price' => 65.00,
                'stock' => 1000,
                'min_stock' => 200,
                'unit' => 'bolsa',
                'active' => true,
            ],
            [
                'code' => 'PROD-004',
                'name' => 'Alambre de Púas Rollo',
                'description' => 'Alambre de púas galvanizado rollo de 500m',
                'category_id' => 1,
                'supplier_id' => 1,
                'purchase_price' => 320.00,
                'sale_price' => 420.00,
                'stock' => 50,
                'min_stock' => 10,
                'unit' => 'rollo',
                'active' => true,
            ],
            [
                'code' => 'PROD-005',
                'name' => 'Martillo de Construcción',
                'description' => 'Martillo de acero con mango de madera',
                'category_id' => 3,
                'supplier_id' => 2,
                'purchase_price' => 35.00,
                'sale_price' => 50.00,
                'stock' => 80,
                'min_stock' => 20,
                'unit' => 'unidad',
                'active' => true,
            ],
            [
                'code' => 'PROD-006',
                'name' => 'Plancha de Acero 1.5mm',
                'description' => 'Plancha de acero laminado en caliente',
                'category_id' => 1,
                'supplier_id' => 1,
                'purchase_price' => 450.00,
                'sale_price' => 580.00,
                'stock' => 30,
                'min_stock' => 5,
                'unit' => 'plancha',
                'active' => true,
            ],
            [
                'code' => 'PROD-007',
                'name' => 'Clavos 3 pulgadas Kg',
                'description' => 'Clavos de acero 3 pulgadas por kilogramo',
                'category_id' => 1,
                'supplier_id' => 2,
                'purchase_price' => 8.50,
                'sale_price' => 12.00,
                'stock' => 500,
                'min_stock' => 100,
                'unit' => 'kg',
                'active' => true,
            ],
            [
                'code' => 'PROD-008',
                'name' => 'Tubo de PVC 4 pulgadas',
                'description' => 'Tubo de PVC para desagüe 4 pulgadas x 6m',
                'category_id' => 2,
                'supplier_id' => 3,
                'purchase_price' => 45.00,
                'sale_price' => 62.00,
                'stock' => 150,
                'min_stock' => 30,
                'unit' => 'tubo',
                'active' => true,
            ],
        ];

        foreach ($products as $prod) {
            Product::create($prod);
        }

        // ============================================
        // TIPOS DE CLIENTE
        // ============================================
        $customerTypes = [
            ['name' => 'Minorista', 'description' => 'Cliente compra pequeñas cantidades', 'discount_percentage' => 0, 'active' => true],
            ['name' => 'Mayorista', 'description' => 'Cliente compra grandes cantidades', 'discount_percentage' => 10, 'active' => true],
            ['name' => 'Corporativo', 'description' => 'Empresas y constructoras', 'discount_percentage' => 15, 'active' => true],
            ['name' => 'Socio VIP', 'description' => 'Socios con beneficios especiales', 'discount_percentage' => 20, 'active' => true],
        ];

        foreach ($customerTypes as $type) {
            CustomerType::create($type);
        }

        // ============================================
        // CLIENTES
        // ============================================
        $customers = [
            [
                'code' => 'CLI-001',
                'name' => 'Constructora Los Andes S.A.',
                'email' => 'ventas@losandes.com',
                'phone' => '3-3667788',
                'customer_type_id' => 3,
                'address' => 'Av. San Martín 555',
                'city' => 'Santa Cruz',
                'country' => 'Bolivia',
                'tax_id' => '1122334455',
                'credit_limit' => 50000.00,
                'current_balance' => 0,
                'notes' => 'Cliente corporativo preferencial',
                'active' => true,
            ],
            [
                'code' => 'CLI-002',
                'name' => 'María García López',
                'email' => 'maria.garcia@email.com',
                'phone' => '7788-9900',
                'customer_type_id' => 1,
                'address' => 'Calle Beni 123',
                'city' => 'Santa Cruz',
                'country' => 'Bolivia',
                'tax_id' => '4455667788',
                'credit_limit' => 5000.00,
                'current_balance' => 0,
                'active' => true,
            ],
            [
                'code' => 'CLI-003',
                'name' => 'Ferretería El Progreso',
                'email' => 'info@elprogreso.com',
                'phone' => '3-3778899',
                'customer_type_id' => 2,
                'address' => 'Av. Mutualista 789',
                'city' => 'Santa Cruz',
                'country' => 'Bolivia',
                'tax_id' => '7788990011',
                'credit_limit' => 30000.00,
                'current_balance' => 0,
                'notes' => 'Compra regularmente cada semana',
                'active' => true,
            ],
            [
                'code' => 'SOCIO-001',
                'name' => 'Carlos Rodríguez Pérez',
                'email' => 'carlos.rodriguez@email.com',
                'phone' => '7799-8877',
                'customer_type_id' => 4,
                'address' => 'Av. Banzer 234',
                'city' => 'Santa Cruz',
                'country' => 'Bolivia',
                'tax_id' => '9988776655',
                'credit_limit' => 10000.00,
                'current_balance' => 0,
                'notes' => 'Socio fundador',
                'active' => true,
            ],
            [
                'code' => 'CLI-004',
                'name' => 'Distribuidora Norte',
                'email' => 'ventas@norte.com',
                'phone' => '3-3889900',
                'customer_type_id' => 2,
                'address' => 'Calle Sucre 678',
                'city' => 'Santa Cruz',
                'country' => 'Bolivia',
                'tax_id' => '3344556677',
                'credit_limit' => 40000.00,
                'current_balance' => 0,
                'active' => true,
            ],
        ];

        foreach ($customers as $cust) {
            Customer::create($cust);
        }

        // ============================================
        // CUENTAS
        // ============================================
        $accounts = [
            [
                'name' => 'Caja Principal',
                'account_number' => 'CAJA-001',
                'type' => 'caja',
                'balance' => 10000.00,
                'currency' => 'BOB',
                'description' => 'Caja chica principal',
                'active' => true,
            ],
            [
                'name' => 'Banco Nacional de Bolivia - Cuenta Corriente',
                'account_number' => '10012345678',
                'type' => 'banco',
                'balance' => 150000.00,
                'currency' => 'BOB',
                'description' => 'Cuenta corriente empresarial',
                'active' => true,
            ],
            [
                'name' => 'Banco Mercantil - Caja de Ahorro USD',
                'account_number' => '20098765432',
                'type' => 'banco',
                'balance' => 5000.00,
                'currency' => 'USD',
                'description' => 'Caja de ahorro en dólares',
                'active' => true,
            ],
        ];

        foreach ($accounts as $acc) {
            Account::create($acc);
        }

        // ============================================
        // TIPOS DE TRANSACCIÓN
        // ============================================
        $transactionTypes = [
            ['name' => 'Venta', 'category' => 'ingreso', 'description' => 'Ingreso por venta de productos', 'active' => true],
            ['name' => 'Compra', 'category' => 'egreso', 'description' => 'Pago a proveedores', 'active' => true],
            ['name' => 'Pago de Salario', 'category' => 'egreso', 'description' => 'Pago de sueldos a empleados', 'active' => true],
            ['name' => 'Depósito', 'category' => 'ingreso', 'description' => 'Depósito de clientes', 'active' => true],
            ['name' => 'Retiro', 'category' => 'egreso', 'description' => 'Retiro de efectivo', 'active' => true],
            ['name' => 'Pago de Servicios', 'category' => 'egreso', 'description' => 'Pago de luz, agua, internet', 'active' => true],
            ['name' => 'Transferencia Interna', 'category' => 'ingreso', 'description' => 'Transferencia entre cuentas', 'active' => true],
        ];

        foreach ($transactionTypes as $tt) {
            TransactionType::create($tt);
        }
    }
}