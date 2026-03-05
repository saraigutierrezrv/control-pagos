<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Expenses\Pages\ListExpenses;
use App\Models\Payment;
use App\Models\Expense;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Illuminate\Support\Carbon;

class TaxResourceWidget extends BaseWidget
{
    use InteractsWithPageTable; // Este ya define $tableColumnSearches internamente

    protected function getTablePage(): string
    {
        // Detecta automáticamente en qué página estamos (Gastos o Pagos)
        return $this->column->getLivewire()::class; 
    }

    protected function getStats(): array
    {
        // Accedemos a los filtros de la tabla de ExpensesTable
        $startDate = $this->tableFilters['fecha_rango']['desde'] ?? now()->startOfMonth();
        $endDate = $this->tableFilters['fecha_rango']['hasta'] ?? now()->endOfMonth();

        // Join (unir las tablas) en la consulta, para que podamos sumar la columna iva_amount 
        // de la tabla customers únicamente de aquellos clientes que han realizado pagos en el rango 
        // de fechas seleccionado.
       $ivaDebito = Payment::query()
        ->whereBetween('payments.created_at', [$startDate, $endDate])
        ->join('customers', 'payments.customer_id', '=', 'customers.id')
        ->sum('customers.iva_amount');

        $ivaCredito = Expense::whereBetween('expense_date', [$startDate, $endDate])->sum('tax_amount');

        $impuestoAPagar = $ivaDebito - $ivaCredito;

        return [
            Stat::make('IVA Débito (Pagos)', '$' . number_format($ivaDebito, 2))
                ->description('Del ' . Carbon::parse($startDate)->format('d/m/Y'))
                ->color('success'),

            Stat::make('IVA Crédito (Gastos)', '$' . number_format($ivaCredito, 2))
                ->description('Del ' . Carbon::parse($endDate)->format('d/m/Y'))
                ->color('danger'),

            Stat::make('Impuesto a Pagar', '$' . number_format(max(0, $impuestoAPagar), 2))
                ->description($impuestoAPagar > 0 ? 'Monto a pagar' : 'Saldo a favor')
                ->chart([5, 10, 8, 12, 7, 15]) // Opcional: una gráfica de adorno
                ->color($impuestoAPagar > 0 ? 'warning' : 'info'),
        ];
    }
}