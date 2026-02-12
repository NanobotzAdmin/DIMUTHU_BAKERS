<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StmBarcodesHistory extends Model
{
    use HasFactory;

    protected $table = 'stm_barcodes_history';

    protected $fillable = [
        'barcode_id',
        'created_by',
        'action',
        'description',
    ];

    public function barcode()
    {
        return $this->belongsTo(StmBarcode::class, 'barcode_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
