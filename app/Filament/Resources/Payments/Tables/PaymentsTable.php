<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Aquí usamos la relación punto 'customer.name'
                TextColumn::make('customer.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('month')
                    ->label('Mes')
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
                        default => $state,
                    }),
                TextColumn::make('year')
                    ->label('Año'),
                TextColumn::make('amount')
                    ->label('Monto')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('payment_date')
                    ->label('Fecha de Pago')
                    ->date('d/m/Y'),

                TextColumn::make('payment_method')
                ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => 'Transferencia', 2 => 'Efectivo', 3 => 'Otro',
                        default => $state,
                    })
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
