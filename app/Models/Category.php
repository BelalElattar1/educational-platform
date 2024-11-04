<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $fillable = [
        'name',
        'title',
        'course_id'
    ];

    public function course() {
        return $this->belongsTo(Course::class);
    }
}
