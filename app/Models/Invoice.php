<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'price',
        'status',
        'course_id',
        'student_id'
    ];

    public function student() {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }
}
