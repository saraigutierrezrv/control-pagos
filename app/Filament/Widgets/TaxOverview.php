<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use App\Models\Expense;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters; // <--- REQUERIDO
use Illuminate\Support\Carbon;

class TaxOverview extends BaseWidget
{
    use InteractsWithPageFilters; // <--- Habilita la conexión con el Dashboard
    // app/Filament/Widgets/TaxOverview.php

    protected int | string | array $columnSpan = 'full';
    protected function getStats(): array
    {
        // 1. Capturamos las fechas del filtro global
        // Si no hay nada seleccionado, usamos el mes actual por seguridad
        // Así es como accedes a los datos del 'Schema' del Dashboard:
        $startDate = $this->filters['startDate'] ?? now()->startOfMonth();
        $endDate = $this->filters['endDate'] ?? now()->endOfMonth();

        // 2. IVA Débito (Ingresos de pagos) filtrado
        // Importante: usamos whereBetween para el rango de fechas
        $totalPagos = Payment::whereBetween('created_at', [$startDate, $endDate])->sum('amount');
        $ivaDebito = ($totalPagos / 1.13) * 0.13;

        // 3. IVA Crédito (Gastos) filtrado
        $ivaCredito = Expense::whereBetween('expense_date', [$startDate, $endDate])->sum('tax_amount');

        // 4. Cálculo final
        $impuestoAPagar = $ivaDebito - $ivaCredito;

        return [
            Stat::make('IVA Débito (Ventas)', '$' . number_format($ivaDebito, 2))
                ->description('Del ' . Carbon::parse($startDate)->format('d/m/Y'))
                ->color('success'),

            Stat::make('IVA Crédito (Compras)', '$' . number_format($ivaCredito, 2))
                ->description('Al ' . Carbon::parse($endDate)->format('d/m/Y'))
                ->color('danger'),

            Stat::make('Impuesto a Pagar (IVA)', '$' . number_format(max(0, $impuestoAPagar), 2))
                ->description($impuestoAPagar > 0 ? 'Monto a pagar' : 'Tienes saldo a favor')
                ->chart([5, 10, 8, 12, 7, 15]) // Opcional: una gráfica de adorno
                ->color($impuestoAPagar > 0 ? 'warning' : 'info'),
        ];
    }
}
