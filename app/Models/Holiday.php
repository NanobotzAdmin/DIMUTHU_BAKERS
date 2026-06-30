<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = ['year', 'date', 'description', 'summary', 'created_by', 'updated_by'];
    
    protected $casts = [
        'date' => 'date',
    ];
}
