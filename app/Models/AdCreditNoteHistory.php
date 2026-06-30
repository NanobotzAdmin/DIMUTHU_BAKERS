<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdCreditNoteHistory extends Model
{
    use HasFactory;

    protected $table = 'ad_credit_note_histories';

    protected $fillable = [
        'ad_credit_note_id',
        'created_by',
        'action',
        'status',
        'description',
    ];

    /**
     * Get the credit note associated with the history log.
     */
    public function creditNote()
    {
        return $this->belongsTo(AdCreditNote::class, 'ad_credit_note_id');
    }

    /**
     * Get the user who made the change.
     */
    public function creator()
    {
        return $this->belongsTo(UmUser::class, 'created_by');
    }
}
