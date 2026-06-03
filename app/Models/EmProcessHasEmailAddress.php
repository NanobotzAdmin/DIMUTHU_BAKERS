<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmProcessHasEmailAddress extends Model
{
    use HasFactory;

    protected $table = 'em_process_has_email_addresses';

    public $timestamps = true;

    protected $fillable = [
        'process_id',
        'email_address',
        'um_user_id',
        'status',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'um_user_id');
    }
}
