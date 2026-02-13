<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOpening extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_title',
        'shift',
        'salary',
        'job_timing',
        'estimated_time_to_hire',
        'job_description',
        'status'
    ];

    protected $casts = [
        'salary' => 'decimal:2',
        'estimated_time_to_hire' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }
}