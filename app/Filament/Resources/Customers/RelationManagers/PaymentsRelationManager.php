<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use App\Filament\Resources\Payments\PaymentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $relatedResource = PaymentResource::class;

    // Esto ayuda a que el $get('../../id') funcione siempre
    protected static ?string $recordTitleAttribute = 'id';

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make()
                    ->url(fn (): string => route('filament.admin.resources.payments.create', [
                        'customer_id' => $this->getOwnerRecord()->getKey(),
                    ])),
            ]);
    }
}
