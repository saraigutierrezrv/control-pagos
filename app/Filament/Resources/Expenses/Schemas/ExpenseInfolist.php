<?php

namespace App\Filament\Resources\Expenses\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ExpenseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('description'),
                TextEntry::make('amount_base')
                    ->label('Monto Base')
                    ->numeric()
                    ->prefix('$'),
                TextEntry::make('tax_amount')
                    ->label('Monto del IVA')
                    ->numeric()
                    ->prefix('$'),
                    TextEntry::make('total')
                    ->label('Monto Total (Con IVA)')
                    ->numeric()
                    ->prefix('$'),
                TextEntry::make('expense_date')
                    ->label('Fecha del gasto')
                    ->date(),
                TextEntry::make('created_at')
                    ->label('Registrado el')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Actualizado el')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
