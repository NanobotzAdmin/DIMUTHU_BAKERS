<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmSessionActivity extends Model
{
    protected $table = 'sm_session_activity';
    protected $fillable = [
        'session_id',
        'user_id',
        'activity_type',
        'description',
        'created_by',
        'updated_by',
    ];

    public function session()
    {
        return $this->belongsTo(SmSession::class, 'session_id');
    }

    public function user()
    {
        return $this->belongsTo(UmUser::class, 'user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(UmUser::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(UmUser::class, 'updated_by');
    }
}
