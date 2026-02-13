<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'date',
        'created_by'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function creator()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }
}