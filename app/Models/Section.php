<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'name',
        'time',
        'link',
        'type',
        'exam_mark',
        'category_id'
    ];

    public function category() {
        return $this->belongsTo(Category::class);    
    }

    public function questions() {
        return $this->hasMany(Question::class, 'exam_id', 'id');
    }
}
