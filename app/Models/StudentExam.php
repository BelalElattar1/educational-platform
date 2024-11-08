<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentExam extends Model
{
    protected $fillable = [
        'degree',
        'exam_id',
        'student_id'
    ];

    public function exam() {
        return $this->belongsTo(Section::class, 'exam_id', 'id');
    }

    public function student() {
        return $this->belongsTo(User::class, 'student', 'id');
    }
}
