<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'image',
        'price',
        'description',
        'year_id'
    ];

    public function year() {
        return $this->belongsTo(Year::class);
    }

    public function categories() {
        return $this->hasMany(Category::class);
    }

    public function invoices() {
        return $this->hasMany(Invoice::class);
    }
}
