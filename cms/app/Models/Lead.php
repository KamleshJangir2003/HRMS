<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'name',
        'email',
        'phone', 
        'company',
        'role',
        'platform',
        'resume',
        'status',
        'condition_status',
        'reason',
        'final_result',
        'rejection_reason'
    ];

    protected $attributes = [
        'status' => 'new',
        'condition_status' => '',
        'role' => 'Unknown'
    ];

    public function interviews()
    {
        return $this->hasMany(Interview::class);
    }

    public function hasScheduledInterview()
    {
        return $this->interviews()->exists();
    }
}