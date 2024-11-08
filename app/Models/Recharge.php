<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recharge extends Model
{
    protected $fillable = [
        'code',
        'price',
        'student_id'
    ];
}
