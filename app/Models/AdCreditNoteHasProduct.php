<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdCreditNoteHasProduct extends Model
{
    use HasFactory;

    protected $table = 'ad_credit_notes_has_product';

    protected $fillable = [
        'credit_note_id',
        'product_id',
        'return_stock_id',
        'stm_stock_id',
        'branch_stock_id',
        'qty',
        'distributor_price',
        'wholesale_price',
        'retail_price',
        'total',
        'reason',
        'status',
    ];

    public function creditNote()
    {
        return $this->belongsTo(AdCreditNote::class, 'credit_note_id');
    }

    public function product()
    {
        return $this->belongsTo(PmProductItem::class, 'product_id');
    }

    public function returnStock()
    {
        return $this->belongsTo(AdReturnProductStock::class, 'return_stock_id');
    }

    public function stock()
    {
        return $this->belongsTo(StmStock::class, 'stm_stock_id');
    }

    public function branchStock()
    {
        return $this->belongsTo(StmBranchStock::class, 'branch_stock_id');
    }
}
