<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;

    public function setTotalAttribute($value)
    {
        $this->attributes['total'] = round($value, 2);
    }

    protected $guarded = [];

    public function products()
    {
        return $this->belongsTo(Product::class,'category_id','id');
    }
}
