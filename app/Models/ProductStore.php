<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStore extends Model
{
    use HasFactory;

    protected $guarded =[];


    public function stores(){
        return $this->belongsTo(Store::class,'store_id','id');
    }

    public function products(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
