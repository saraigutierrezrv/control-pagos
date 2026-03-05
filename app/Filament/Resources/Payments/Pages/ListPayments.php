<?php

namespace App\Filament\Resources\Payments\Pages;

use App\Filament\Resources\Payments\PaymentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Widgets\TaxResourceWidget; // Importa tu widget
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

     use ExposesTableToWidgets;
    
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
