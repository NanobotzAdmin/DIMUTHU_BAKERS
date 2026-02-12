<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmCustomer extends Model
{
    use HasFactory;

    protected $table = 'cm_customer';

    protected $fillable = [
        'name',
        'customer_type',
        'phone',
        'email',
        'address',
        'latitude',
        'longitude',
        'created_by',
        'updated_by',
    ];

    public function businessDetails()
    {
        return $this->hasOne(AdCustomerHasBusiness::class, 'customer_id');
    }

    public function orders()
    {
        return $this->hasMany(StmOrderRequest::class, 'customer_id');
    }

    public function creator()
    {
        return $this->belongsTo(UmUser::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(UmUser::class, 'updated_by');
    }
}
