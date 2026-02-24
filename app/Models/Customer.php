<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'name',
        'phone',
        'billing_day',
        'monthly_amount',
        'is_active',
    ];

    /**
     * Relación con los pagos del cliente.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}