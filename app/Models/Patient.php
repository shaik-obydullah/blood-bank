<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patients';

    protected $fillable = [
        'id',
        'name',
        'email',
        'medical_history',
        'address',
        'last_blood_taking_date',
    ];

    protected $casts = [
        'last_blood_taking_date' => 'date',
    ];
}