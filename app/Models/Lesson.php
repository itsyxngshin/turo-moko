<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lesson extends Model
{
    use HasFactory;
    protected $fillable = [
        'module_id', 'title', 'content', 'status',
        'start_date', 'end_date', 'order', 'attachments', 'visibility'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function module()
{
    return $this->belongsTo(Module::class);
}

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    
}
