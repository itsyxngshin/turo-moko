<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'announcement_id',
        'file_path',
        'original_name',
        
    ];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }
}
