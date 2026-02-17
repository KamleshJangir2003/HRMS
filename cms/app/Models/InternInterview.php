<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternInterview extends Model
{
    use HasFactory;

    protected $fillable = [
        'intern_id',
        'interviewer_id',
        'interview_date',
        'interview_time',
        'interview_type',
        'meeting_link',
        'status',
        'result',
        'feedback',
        'technical_score',
        'communication_score',
        'overall_score'
    ];

    protected $casts = [
        'interview_date' => 'date',
        'interview_time' => 'datetime'
    ];

    public function intern()
    {
        return $this->belongsTo(Intern::class);
    }

    public function interviewer()
    {
        return $this->belongsTo(Employee::class, 'interviewer_id');
    }
}