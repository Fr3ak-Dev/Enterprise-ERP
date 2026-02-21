<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function productsPdf()
    {
        $products = Product::with(['category'])
            ->where('active', true)
            ->orderBy('name')
            ->get();

        $pdf = Pdf::loadView('reports.products-pdf', [
            'products' => $products
        ]);

        $pdf->setPaper('a4', 'portrait'); // Tamaño carta, vertical
        return $pdf->download('reporte-productos-' . date('Y-m-d') . '.pdf');
    }

    public function transactionsExcel()
    {
        return Excel::download(
            new TransactionsExport,
            'reporte-transacciones-' . date('Y-m-d') . '.xlsx'
        );
    }

    public function productsPdfPreview()
    {
        $products = Product::with(['category'])
            ->where('active', true)
            ->orderBy('name')
            ->get();

        $pdf = Pdf::loadView('reports.products-pdf', [
            'products' => $products
        ]);

        return $pdf->stream();
    }
}
