<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Productos</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 15px;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            color: #7f8c8d;
            font-size: 14px;
        }

        .info {
            margin-bottom: 20px;
            background-color: #ecf0f1;
            padding: 10px;
            border-radius: 5px;
        }

        .info p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table thead {
            background-color: #34495e;
            color: white;
        }

        table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }

        table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .low-stock {
            color: #e74c3c;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #95a5a6;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .summary {
            margin-top: 20px;
            background-color: #3498db;
            color: white;
            padding: 15px;
            border-radius: 5px;
        }

        .summary p {
            margin: 5px 0;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>REPORTE DE PRODUCTOS</h1>
        <p>EnterpriseERP - Sistema de Gestión</p>
    </div>

    <div class="info">
        <p><strong>Fecha de generación:</strong> {{ date('d/m/Y H:i') }}</p>
        <p><strong>Total de productos:</strong> {{ count($products) }}</p>
        <p><strong>Productos con stock bajo:</strong> {{ $products->where('is_low_stock', true)->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Stock</th>
                <th>Precio Venta</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td class="{{ $product->is_low_stock ? 'low-stock' : '' }}">
                        {{ $product->stock }} / {{ $product->min_stock }}
                    </td>
                    <td>Bs. {{ number_format($product->sale_price, 2) }}</td>
                    <td>{{ $product->active ? 'Activo' : 'Inactivo' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <p><strong>Valor total en inventario:</strong> Bs.
            {{ number_format($products->sum(function ($p) {return $p->stock * $p->sale_price;}),2) }}</p>
        <p><strong>Productos activos:</strong> {{ $products->where('active', true)->count() }}</p>
    </div>

    <div class="footer">
        <p>Generado por EnterpriseERP - {{ date('Y') }}</p>
        <p>Este documento fue generado automáticamente</p>
    </div>
</body>

</html>
