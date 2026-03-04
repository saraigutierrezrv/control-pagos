<?php

namespace App\Filament\Resources\Expenses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Get;
use Filament\Forms\Set;

class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('description')
                ->label('Descripción del Gasto')
                ->required()
                ->columnSpanFull(),

            TextInput::make('amount_base')
                ->label('Monto sin IVA (Subtotal)')
                ->numeric()
                ->prefix('$')
                ->live(onBlur: true)
                ->afterStateUpdated(function ($get, $set, $state) {
                    $base = (float) $state;
                    $iva = round($base * 0.13, 2);
                    $set('tax_amount', number_format($iva, 2, '.', ''));
                    $set('total', number_format($base + $iva, 2, '.', ''));
                })
                ->required(),

            TextInput::make('tax_amount')
                ->label('IVA (13% Crédito)')
                ->numeric()
                ->prefix('$')
                ->readOnly() // Se calcula solo para evitar descuadres
                ->required(),

            TextInput::make('total')
                ->label('Monto Total (Con IVA)')
                ->numeric()
                ->prefix('$')
                ->live(onBlur: true)
                ->afterStateUpdated(function ($get, $set, $state) {
                    $total = (float) $state;
                    // Desglose de IVA hacia atrás: Total / 1.13
                    $base = round($total / 1.13, 2);
                    $iva = round($total - $base, 2);
                    
                    $set('amount_base', number_format($base, 2, '.', ''));
                    $set('tax_amount', number_format($iva, 2, '.', ''));
                })
                ->required(),

            DatePicker::make('expense_date')
                ->label('Fecha del Gasto')
                ->default(now())
                ->required(),
        ]);
    }
}
