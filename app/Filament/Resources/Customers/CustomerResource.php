<?php

namespace App\Filament\Resources\Customers;

use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Filament\Resources\Customers\Pages\ViewCustomer;
use App\Filament\Resources\Customers\Schemas\CustomerForm;
use App\Filament\Resources\Customers\Schemas\CustomerInfolist;
use App\Filament\Resources\Customers\Tables\CustomersTable;
use App\Models\Customer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use App\Filament\Resources\Customers\RelationManagers\PaymentsRelationManager;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CustomerForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CustomerInfolist::configure($schema);
    }

    // En CustomerResource.php
public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('name')
                ->label('Cliente')
                ->searchable(),

            IconColumn::make('payment_status')
                ->label('Estado Mes Actual')
                ->getStateUsing(fn ($record) => 
                    $record->payments()
                        ->where('month', now()->month)
                        ->where('year', now()->year)
                        ->exists()
                )
                ->boolean()
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-clock')
                ->trueColor('success')
                ->falseColor('danger'),

            TextColumn::make('billing_day')
                ->label('Día de Pago'),

            TextColumn::make('phone')
                ->label('Teléfono'),
        ])
        ->actions([
            Action::make('enviar_recordatorio')
                ->label('Recordar')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('success')
                ->url(function ($record) {
                    // 1. Preparamos los datos dinámicos
                    $nombre = $record->name;
                    $monto = number_format($record->monthly_amount, 2);
                    $mes = now()->translatedFormat('F'); // Esto pondrá "febrero" automáticamente
                    
                    // 2. Limpiamos el teléfono por si tiene espacios o guiones
                    $telefono = preg_replace('/[^0-9]/', '', $record->phone);

                    // 3. Redactamos el mensaje con negritas (*) y emojis
                    $mensaje = "Hola *{$nombre}*, te saludamos de *ICEA*. 👋 Te recordamos de realizar tu pago de *{$mes}* por un monto de *\${$monto}*. ¡Bendiciones! ✨";

                    return "https://wa.me/{$telefono}?text=" . urlencode($mensaje);
                })
                ->openUrlInNewTab(),
            EditAction::make(),
        ]);
}

    public static function getRelations(): array
    {
        return [
            PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'view' => ViewCustomer::route('/{record}'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }
}
