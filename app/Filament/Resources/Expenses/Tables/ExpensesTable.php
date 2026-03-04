<?php

namespace App\Filament\Resources\Expenses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
