<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Lesson extends Model
{
    protected $fillable = [
        'module_id', 'content', 'status',
        'start_date', 'end_date', 'order','attachments_original_name', 'attachments', 'visibility'
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
