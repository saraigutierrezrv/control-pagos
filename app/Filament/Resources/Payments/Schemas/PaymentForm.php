<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Grid;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Selector de Cliente
                Select::make('customer_id')
                    ->label('Cliente')
                    ->relationship('customer', 'name') // Importante: debe coincidir con la relación en el modelo Payment
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live() // Hace que el formulario reaccione al cambio
                    ->afterStateUpdated(fn ($state, callable $set) => 
                        $set('amount', \App\Models\Customer::find($state)?->monthly_amount)
                    ),

                Grid::make(2)
                    ->schema([
                        // Selector de Mes
                        Select::make('month')
                            ->label('Mes Pagado')
                            ->options([
                                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
                            ])
                            ->default(now()->month)
                            ->required(),

                        // Año
                        TextInput::make('year')
                            ->label('Año')
                            ->numeric()
                            ->default(now()->year)
                            ->required(),
                    ]),

                Grid::make(3)
                    ->schema([
                        // Monto
                        TextInput::make('amount')
                            ->label('Monto Pagado')
                            ->numeric()
                            ->prefix('$')
                            ->required(),

                        // Método de pago
                        Select::make('payment_method')
                            ->label('Método de Pago')
                            ->options([
                                1 => 'Transferencia', 2 => 'Efectivo', 3 => 'Otro'
                            ]),

                        // Fecha de pago
                        DatePicker::make('payment_date')
                            ->label('Fecha de Registro')
                            ->default(now())
                            ->required(),
                    ]),
            ]);
    }
}