<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmVariation extends Model
{
    use HasFactory;

    protected $table = 'pm_variation';

    protected $fillable = [
        'variation_name',
        'status',
        'created_by'
    ];

    public function values()
    {
        return $this->hasMany(PmVariationValue::class, 'pm_variation_id');
    }
}
