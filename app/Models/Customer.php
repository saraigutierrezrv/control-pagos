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
        'base_monthly_payment', // El pago base (ej. $400)
        'has_iva',              // Boolean (Toggle)
        'has_renta',            // Boolean (Toggle)
        'iva_amount',           // El 13% calculado
        'renta_amount',         // El 10% calculado
        'final_monthly_payment',// El total final (Base + IVA - Renta)
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