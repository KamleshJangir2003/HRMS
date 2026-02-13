<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_type',
        'amount',
        'issue_date',
        'due_date',
        'status'
    ];

    protected $dates = [
        'issue_date',
        'due_date'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2'
    ];

    // Scope for due bills
    public function scopeDueToday($query)
    {
        return $query->where('due_date', Carbon::today())
                    ->where('status', '!=', 'paid');
    }

    // Scope for overdue bills
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', Carbon::today())
                    ->where('status', '!=', 'paid');
    }

    // Scope for pending bills
    public function scopePending($query)
    {
        return $query->where('status', '!=', 'paid');
    }
}