<?php

namespace App\Filament\Resources\Expenses\Pages;

use App\Filament\Resources\Expenses\ExpenseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Widgets\TaxOverview; // Importa tu widget

class ListExpenses extends ListRecords
{
    protected static string $resource = ExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    // --- AQUÍ REGISTRAS EL WIDGET ---
    protected function getHeaderWidgets(): array
    {
        return [
            TaxOverview::class,
        ];
    }
}
