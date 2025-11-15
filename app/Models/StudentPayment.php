<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPayment extends Model
{
    use HasFactory;

    // Esta tabla no usa softDeletes

    protected $fillable = [
        'student_id',
        'payment_concept_id',
        'academic_period_id',
        'registered_by_user_id',
        'voucher_id',
        'original_amount',
        'discount_amount',
        'final_amount',
        'due_date',
        'payment_date',
        'transaction_number',
        'payment_method',
        'status',
        'notes',
    ];

    protected $casts = [
        'original_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'due_date' => 'date',
        'payment_date' => 'datetime',
    ];

    /**
     * El pago pertenece a un estudiante.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * El pago es de un concepto específico.
     */
    public function paymentConcept()
    {
        return $this->belongsTo(PaymentConcept::class);
    }

    /**
     * El pago (opcionalmente) pertenece a un periodo académico.
     */
    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    /**
     * El pago fue registrado por un usuario (cajero).
     */
    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by_user_id');
    }

    /**
     * El pago (deuda) fue saldado por un comprobante.
     */
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
