<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class IncomeChart extends ChartWidget
{
    protected ?string $heading = 'Ingresos Mensuales';
    protected static ?int $sort = 2; // Debajo de las estadísticas

    protected function getData(): array
    {
        // Esta lógica suma los pagos de los últimos 6 meses
        $data = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->translatedFormat('F');
            $data[] = Payment::where('month', $month->month)
                ->where('year', $month->year)
                ->sum('amount');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ingresos ($)',
                    'data' => $data,
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Puede ser 'bar' o 'line'
    }
}