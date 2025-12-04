<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     * We use 'key' (e.g., 'maintenance_mode') instead of 'id'.
     */
    protected $primaryKey = 'key';

    /**
     * Indicates if the IDs are auto-incrementing.
     * Must be set to false because our key is a String, not a Number.
     */
    public $incrementing = false;

    /**
     * The "type" of the auto-incrementing ID.
     */
    protected $keyType = 'string';

    protected $fillable = [
        'key', 
        'value'
    ];
}
