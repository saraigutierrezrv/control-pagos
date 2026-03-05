<?php

namespace App\Filament\Resources\Expenses\Pages;

use App\Filament\Resources\Expenses\ExpenseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Widgets\TaxResourceWidget;

use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListExpenses extends ListRecords
{
    protected static string $resource = ExpenseResource::class;

    use ExposesTableToWidgets;

    // --- AQUÍ REGISTRAS EL WIDGET ---
    protected function getHeaderWidgets(): array
{
    return [
        TaxResourceWidget::class,
    ];
}
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    
}
