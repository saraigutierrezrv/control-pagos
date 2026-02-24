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
                ->url(fn ($record) => "https://wa.me/{$record->phone}?text=" . urlencode("Hola {$record->name}, te saludamos de ICEA. Te recordamos tu pago de este mes. ¡Saludos!"))
                ->openUrlInNewTab(),
            EditAction::make(),
        ]);
}

    public static function getRelations(): array
    {
        return [
            //
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
