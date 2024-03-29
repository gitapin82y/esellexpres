<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model
{
    use HasFactory;

    protected $guarded =[];

    public function product(){
        return $this->belongsTo(Product::class,'products_id','id');
    }

    public function getPhotoAttribute($value)
    {
        return url($value);
    }

}
