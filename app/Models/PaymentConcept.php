<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentConcept extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentConceptFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'tupa_code',
        'description',
        'amount',
        'concept_type',
        'is_taxable',
        'tax_rate',
        'sunat_service_code',
        'is_mandatory',
        'discount_applicable',
        'status',
    ];

    /**
     * Define los casts para decimales y booleanos.
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'is_taxable' => 'boolean',
        'tax_rate' => 'decimal:2',
        'is_mandatory' => 'boolean',
        'discount_applicable' => 'boolean',
    ];
}
