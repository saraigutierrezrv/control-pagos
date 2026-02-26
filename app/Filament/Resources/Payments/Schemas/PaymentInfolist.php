<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PaymentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('customer_id')
                    ->numeric(),
                TextEntry::make('month')
                    ->numeric(),
                TextEntry::make('year')
                    ->numeric(),
                TextEntry::make('amount')
                    ->numeric(),
                TextEntry::make('payment_date')
                    ->date(),
                TextEntry::make('payment_method')
                    ->placeholder('-')
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => 'Transferencia', 2 => 'Efectivo', 3 => 'Otro',
                        default => $state,
                    }),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
