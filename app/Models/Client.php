<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lastname',
        'id_number',
        'email',
        'phone',
        'department',
        'city',
        'habeas_data',
        'is_winner',
    ];

    protected $casts = [
        'habeas_data' => 'boolean',
        'is_winner' => 'boolean',
    ];
}
