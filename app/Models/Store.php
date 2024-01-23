<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function users()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }


    public function transactions()
    {
        return $this->hasMany(Transaction::class,'store_id');
    }

    public function request()
    {
        return $this->hasMany(RequestStore::class,'store_id');
    }


    public function products()
    {
        return $this->hasMany(ProductStore::class,'store_id');
    }
}
