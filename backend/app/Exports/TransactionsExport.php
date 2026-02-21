<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnWidths
{
    /**
     * Obtener las transacciones
     */
    public function collection()
    {
        return Transaction::with(['account', 'transactionType', 'customer', 'user'])
            ->orderBy('transaction_date', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Fecha',
            'Tipo',
            'Categoría',
            'Cuenta',
            'Cliente',
            'Monto',
            'Estado',
            'Referencia',
            'Usuario',
        ];
    }

    /**
     * Mapear cada transacción a una fila
     */
    public function map($transaction): array
    {
        return [
            $transaction->id,
            $transaction->transaction_date->format('d/m/Y'),
            $transaction->transactionType->name,
            $transaction->transactionType->category,
            $transaction->account->name,
            $transaction->customer ? $transaction->customer->name : 'N/A',
            $transaction->amount,
            $transaction->status,
            $transaction->reference ?? 'N/A',
            $transaction->user->name,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo de la fila de encabezados
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2c3e50']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 12,  // Fecha
            'C' => 20,  // Tipo
            'D' => 12,  // Categoría
            'E' => 25,  // Cuenta
            'F' => 25,  // Cliente
            'G' => 12,  // Monto
            'H' => 12,  // Estado
            'I' => 20,  // Referencia
            'J' => 20,  // Usuario
        ];
    }
}
