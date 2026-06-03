<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmEmailSend extends Model
{
    use HasFactory;

    protected $table = 'em_email_send';

    public $timestamps = true;

    protected $fillable = [
        'email_address',
        'process_id',
        'template_id',
        'email_content',
        'email_subject',
        'send_response',
        'status',
        'created_by',
        'updated_by',
        'attachment_path',
    ];
}
