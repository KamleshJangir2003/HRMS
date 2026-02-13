<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'employee_id',
        'title',
        'description',
        'type',
        'priority',
        'status',
        'admin_response',
        'viewed_at',
        'resolved_at'
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
