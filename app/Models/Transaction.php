<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function stores()
    {
        return $this->belongsTo(Store::class,'store_id','id');
    }

    public function delivery()
    {
        return $this->belongsTo(DeliveryServices::class,'delivery_service_id','id');
    }

    public function details(){
        return $this->hasMany(TransactionDetail::class,'transaction_id');
    }
}
