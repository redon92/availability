<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAvailability extends Model
{
    use HasFactory;

    protected $table = 'user_availability';

    protected $fillable = [
        'user_id',
        'date',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];
}
