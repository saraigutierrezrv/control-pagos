<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid; // Este dijiste que funciona aquí
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Get; // Regresamos a Forms
use Filament\Forms\Set; // Regresamos a Forms
use Closure;
use App\Models\Payment;
use App\Models\Customer;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Selector de Cliente
                Select::make('customer_id')
                    ->label('Cliente')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    // Buscamos en la URL (?customer_id=...) o en el contexto del Relation Manager
                    ->default(fn ($get) => request()->query('customer_id') ?? $get('../../id'))
                    ->disabled(fn ($get) => filled(request()->query('customer_id')) || filled($get('../../id')))
                    ->dehydrated()
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        $customer = Customer::find($state);
                        $set('amount', $customer?->monthly_amount ?? 0);
                    }),

                Grid::make(2)
                    ->schema([
                        // Selector de Mes con Validación de Duplicados
                        Select::make('month')
                            ->label('Mes Pagado')
                            ->options([
                                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
                            ])
                            ->default(now()->month)
                            ->required()
                            ->rules(fn ($get): array => [
                                function (string $attribute, $value, Closure $fail) use ($get) {
                                    // Obtenemos el ID del cliente (del select o del padre)
                                    $customerId = $get('customer_id') ?? $get('../../id');

                                    if (!$customerId) return;

                                    $exists = Payment::where('customer_id', $customerId)
                                        ->where('month', $value)
                                        ->where('year', $get('year'))
                                        // Evitamos que se valide a sí mismo al editar
                                        ->when($get('id'), fn ($query) => $query->where('id', '!=', $get('id')))
                                        ->exists();

                                    if ($exists) {
                                        $fail("Este cliente ya tiene un pago registrado para este mes.");
                                    }
                                },
                            ]),

                        TextInput::make('year')
                            ->label('Año')
                            ->numeric()
                            ->default(now()->year)
                            ->required(),
                    ]),

                Grid::make(3)
                    ->schema([
                        // 2. Campo de Monto (para que también cargue el dinero automáticamente)
                        TextInput::make('amount')
                            ->label('Monto Pagado')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->default(function ($get) {
                                // Si ya tenemos un cliente (por URL o por contexto), cargamos su mensualidad
                                $customerId = request()->query('customer_id') ?? $get('../../id') ?? $get('customer_id');
                                if ($customerId) {
                                    return Customer::find($customerId)?->monthly_amount;
                                }
                                return null;
                            }),

                        Select::make('payment_method')
                            ->label('Método de Pago')
                            ->options([
                                'transferencia' => 'Transferencia',
                                'efectivo' => 'Efectivo',
                                'otro' => 'Otro'
                            ])
                            ->default('efectivo'),

                        DatePicker::make('payment_date')
                            ->label('Fecha de Registro')
                            ->default(now())
                            ->required(),
                    ]),
            ]);
    }
}