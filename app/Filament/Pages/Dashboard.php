<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            // ->components([
            //     Section::make('Filtro de Período Contable')
            //         ->schema([
            //             DatePicker::make('startDate')
            //                 ->label('Fecha Inicio')
            //                 ->default(now()->startOfMonth()),
            //             DatePicker::make('endDate')
            //                 ->label('Fecha Fin')
            //                 ->default(now()->endOfMonth()),
            //         ])
            //         ->columns(2),
            // ])
            ;
    }
}