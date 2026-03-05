<?php

namespace App\Filament\Resources\Customers;
use UnitEnum;
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

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;
    protected static ?string $modelLabel = 'Cliente';
    protected static ?string $pluralModelLabel = 'Clientes';
    protected static ?string $navigationLabel = 'Clientes';
    protected static string|UnitEnum|null $navigationGroup = 'Administración'; // Opcional: para agrupar

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
                ->weight('bold')
                ->color(fn (Customer $record) => self::getPaymentStatusColor($record))
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
                ->weight('bold')
                ->suffix(' de cada mes')
                ->badge()
                ->color(fn (Customer $record) => self::getPaymentStatusColor($record))
                ->label('Día de Pago'),

            TextColumn::make('phone')
                ->color(fn (Customer $record) => self::getPaymentStatusColor($record))
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
                    $monto = number_format($record->base_monthly_payment, 2);
                    $mes = now()->translatedFormat('F'); // Esto pondrá "febrero" automáticamente
                    
                    // 2. Limpiamos el teléfono por si tiene espacios o guiones
                    $telefono = preg_replace('/[^0-9]/', '', $record->phone);

                    // 3. Redactamos el mensaje con negritas (*) y emojis
                    $mensaje = "Hola *{$nombre}*, te saludamos de *ICEA*. Te recordamos de realizar tu pago de *{$mes}* por un monto de *\${$monto}*. ¡Bendiciones!";

                    return "https://wa.me/{$telefono}?text=" . urlencode($mensaje);
                })
                ->openUrlInNewTab(),
            EditAction::make()
                ->label('Editar'),
        ]);
}
    public static function getPaymentStatusColor(Customer $record): string
    {
        $hoy = now();
        
        // 1. Verificamos si ya existe un pago registrado este mes (Marzo 2026)
        $yaPagoEsteMes = $record->payments()
            ->whereMonth('payment_date', $hoy->month)
            ->whereYear('payment_date', $hoy->year)
            ->exists();

        if ($yaPagoEsteMes) {
            return 'success'; // Verde
        }

        // 2. Si no ha pagado y ya es su día (o ya pasó): Rojo
        // Como hoy es 4 de marzo, si billing_day es 1, 2, 3 o 4, saldrá rojo.
        if ($hoy->day >= $record->billing_day) {
            return 'danger';
        }

        // 3. Si no ha pagado pero aún no llega su fecha: Gris
        return 'gray';
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
