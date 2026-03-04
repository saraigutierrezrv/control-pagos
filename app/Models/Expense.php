<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    /** @use HasFactory<\Database\Factories\ExpenseFactory> */
    use HasFactory;
    protected $fillable = [
        'description',
        'amount_base', // Subtotal sin IVA
        'tax_amount',  // El 13%
        'total',       // Total de la factura
        'expense_date',
    ];
}
