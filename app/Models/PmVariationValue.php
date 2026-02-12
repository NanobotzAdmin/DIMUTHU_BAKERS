<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmVariationValue extends Model
{
    use HasFactory;

    protected $table = 'pm_variation_value';

    protected $fillable = [
        'pm_variation_id',
        'unit_of_measurement_id',
        'variation_value',
        'status',
        'created_by',
        'updated_by'
    ];

    public function variation()
    {
        return $this->belongsTo(PmVariation::class, 'pm_variation_id');
    }
}
