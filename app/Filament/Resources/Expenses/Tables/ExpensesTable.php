<?php

namespace App\Filament\Resources\Expenses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class ExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('expense_date')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Descripción')
                    ->searchable(),
                TextColumn::make('amount_base')
                    ->label('Subtotal')
                    ->money('USD'),
                TextColumn::make('tax_amount')
                    ->label('IVA (Crédito)')
                    ->money('USD')
                    ->color('danger'),
                TextColumn::make('total')
                    ->label('Total')
                    ->money('USD')
                    ->weight('bold'),
            ])
            ->filters([
                Filter::make('fecha_rango')
                    ->form([
                        DatePicker::make('desde')
                            ->label('Desde')
                            ->default(now()->startOfMonth()),
                        DatePicker::make('hasta')
                            ->label('Hasta')
                            ->default(now()->endOfMonth()),
                    ])
                    // Esto hace que el filtro se aplique a la base de datos
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['desde'], fn ($q, $date) => $q->whereDate('expense_date', '>=', $date))
                            ->when($data['hasta'], fn ($q, $date) => $q->whereDate('expense_date', '<=', $date));
                    })
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
