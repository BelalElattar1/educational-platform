<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Choose extends Model
{
    protected $fillable = [
        'name',
        'status',
        'question_id'
    ];

    public function question() {
        return $this->belongsTo(Question::class);
    }
}
