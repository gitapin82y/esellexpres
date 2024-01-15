<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function setPromoPriceAttribute($value)
    {
        $this->attributes['promo_price'] = round($value, 2);
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = round($value, 2);
    }

    public function categories()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function galleries()
    {
        return $this->hasMany(ProductGallery::class,'products_id');
    }

    public function stores()
    {
        return $this->hasMany(ProductStore::class,'product_id');
    }

    public function details(){
        return $this->hasMany(TransactionDetail::class,'transaction_id');
    }
}
