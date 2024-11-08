<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'name',
        'exam_id'
    ];

    public function chooses() {
        return $this->hasMany(Choose::class);
    }

    public function exam() {
        return $this->belongsTo(Section::class, 'exam_id', 'id');
    }
}