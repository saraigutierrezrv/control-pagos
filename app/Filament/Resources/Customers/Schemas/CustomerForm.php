<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Get;
use Filament\Forms\Set;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Cliente')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre del Cliente')
                            ->required(),
                        TextInput::make('phone')
                            ->label('Teléfono')
                            ->tel()
                            ->required(),
                        TextInput::make('billing_day')
                            ->label('Día de Cobro')
                            ->required()
                            ->numeric(),
                        Toggle::make('is_active')
                            ->label('¿Cliente Activo?')
                            ->default(true),
                    ])->columns(2),

                Section::make('Configuración de Pago y Retenciones')
                    ->description('Calcula automáticamente IVA y Renta según la base mensual.')
                    ->schema([
                        TextInput::make('base_monthly_payment')
                            ->label('Pago Mensual Base')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ( $get,  $set) => self::calculateTotals($get, $set)),

                        Toggle::make('has_iva')
                            ->label('Aplicar IVA (13%)')
                            ->live()
                            ->afterStateUpdated(fn ( $get,  $set) => self::calculateTotals($get, $set)),

                        Toggle::make('has_renta')
                            ->label('Aplicar Renta (10%)')
                            ->live()
                            ->afterStateUpdated(fn ( $get,  $set) => self::calculateTotals($get, $set)),

                        TextInput::make('iva_amount')
                            ->label('Monto IVA')
                            ->numeric()
                            ->prefix('$')
                            ->readOnly()
                            ->visible(fn ( $get) => $get('has_iva')),

                        TextInput::make('renta_amount')
                            ->label('Monto Renta')
                            ->numeric()
                            ->prefix('$')
                            ->readOnly()
                            ->visible(fn ( $get) => $get('has_renta')),

                        TextInput::make('final_monthly_payment')
                            ->label('Pago Mensual Final')
                            ->numeric()
                            ->prefix('$')
                            ->readOnly()
                            ->extraAttributes(['class' => 'font-bold text-primary-600']),
                        
                        TextInput::make('pago_a_cuenta_amount')
                            ->label('Pago a Cuenta')
                            ->numeric()
                            ->prefix('$')
                            ->readOnly()
                            ->extraAttributes(['class' => 'font-bold text-primary-600']),
                    ])->columns(3),
            ]);
    }

    public static function calculateTotals($get, $set): void
    {
        $base = (float) $get('base_monthly_payment');
        
        $iva = $get('has_iva') ? ($base * 0.13) : 0;
        $renta = $get('has_renta') ? ($base * 0.10) : 0;
        
        // Fórmula en El Salvador: Base + IVA - Renta
        $final = $base + $iva - $renta;

        $pagoACuenta = ($base * 0.0175);

        $set('iva_amount', number_format($iva, 2, '.', ''));
        $set('renta_amount', number_format($renta, 2, '.', ''));
        $set('final_monthly_payment', number_format($final, 2, '.', ''));
        $set('pago_a_cuenta_amount', number_format($pagoACuenta, 2, '.', ''));
    }
}