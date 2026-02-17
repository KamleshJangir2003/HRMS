<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'intern_id',
        'amount',
        'payment_date',
        'payment_method',
        'notes'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2'
    ];

    public function intern()
    {
        return $this->belongsTo(Intern::class);
    }
}