<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class PaymentStats extends BaseWidget
{
    protected function getStats(): array
    {
        // 1. Recaudación del mes en curso
        $monthlyRevenue = Payment::where('month', now()->month)
            ->where('year', now()->year)
            ->sum('amount');

        // 2. Clientes que no han pagado este mes
        $pendingCount = Customer::whereDoesntHave('payments', function ($query) {
            $query->where('month', now()->month)
                ->where('year', now()->year);
        })->count();

        // 3. Contar total de clientes activos
        $totalCustomers = Customer::where('is_active', true)->count();
        

        return [
            Stat::make('Ingresos de ' . now()->translatedFormat('F'), '$' . number_format($monthlyRevenue, 2))
                ->description('Total cobrado este mes')
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('success'),

            Stat::make('Pendientes de Pago', $pendingCount)
                ->description('Clientes que aún deben')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color($pendingCount > 0 ? 'danger' : 'success')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    // Podrías incluso linkear esto a la tabla de clientes filtrada
                ]),

            Stat::make('Total Clientes', $totalCustomers)
                ->description('Clientes activos en el sistema')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }
}