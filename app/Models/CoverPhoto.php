<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoverPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'status',
        'path',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
