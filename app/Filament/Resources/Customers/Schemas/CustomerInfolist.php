<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section; // Importante
use Filament\Schemas\Components\Grid;    // Importante
use Filament\Schemas\Schema;

class CustomerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // SECCIÓN 1: DATOS GENERALES
                Section::make('Información General')
                    ->description('Datos de contacto y estado del cliente.')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Grid::make(3) // Divide en 3 columnas
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Nombre Completo')
                                    ->weight('bold'),
                                TextEntry::make('phone')
                                    ->label('Teléfono')
                                    ->copyable(), // Permite copiar el número con un clic
                                IconEntry::make('is_active')
                                    ->label('Estado')
                                    ->boolean(),
                            ]),
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('billing_day')
                                    ->label('Día de Cobro')
                                    ->suffix(' de cada mes'),
                                TextEntry::make('created_at')
                                    ->label('Fecha de Registro')
                                    ->dateTime('d/m/Y H:i'),
                                TextEntry::make('updated_at')
                                    ->label('Última Actualización')
                                    ->dateTime('d/m/Y H:i'),
                            ]),
                    ])
                    ->collapsible(), // Permite contraer la sección

                // SECCIÓN 2: DATOS FINANCIEROS (ICEA)
                Section::make('Configuración Financiera')
                    ->description('Detalle de cobros mensuales, impuestos y retenciones.')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('base_monthly_payment')
                                    ->label('Pago Mensual Base')
                                    ->money('USD')
                                    ->color('gray'),
                                IconEntry::make('has_iva')
                                    ->label('Aplica IVA (13%)')
                                    ->boolean(),
                                IconEntry::make('has_renta')
                                    ->label('Aplica Renta (10%)')
                                    ->boolean(),
                            ]),
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('iva_amount')
                                    ->label('Monto de IVA')
                                    ->money('USD')
                                    ->visible(fn ($record) => $record->has_iva), // Solo se ve si aplica
                                TextEntry::make('renta_amount')
                                    ->label('Monto de Renta')
                                    ->money('USD')
                                    ->visible(fn ($record) => $record->has_renta), // Solo se ve si aplica
                                TextEntry::make('final_monthly_payment') // Campo calculado que agregamos antes
                                    ->label('TOTAL A PAGAR')
                                    ->money('USD')
                                    ->weight('bold')
                                    ->color('success'),
                            ]),
                    ])
                    ->columns(1),
            ]);
    }
}