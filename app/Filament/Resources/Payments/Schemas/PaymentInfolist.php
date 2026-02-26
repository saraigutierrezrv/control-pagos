<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PaymentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Mostramos el nombre del cliente usando la relación
                TextEntry::make('customer.name')
                    ->label('Cliente'),

                TextEntry::make('month')
                    ->label('Mes')
                    ->formatStateUsing(fn ($state) => match ((int)$state) {
                        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
                        default => $state,
                    }),

                TextEntry::make('year')
                    ->label('Año'),

                TextEntry::make('amount')
                    ->label('Monto Pagado')
                    ->money('USD'),

                TextEntry::make('payment_date')
                    ->label('Fecha del Pago')
                    ->date('d/m/Y'),

                TextEntry::make('payment_method')
                    ->label('Método de Pago')
                    ->badge() // Lo hace ver más bonito como una etiqueta
                    ->color('info')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'transferencia' => 'Transferencia',
                        'efectivo' => 'Efectivo',
                        'otro' => 'Otro',
                        default => ucfirst($state), // ucfirst pone la primera en mayúscula
                    }),

                TextEntry::make('created_at')
                    ->label('Registrado el')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}