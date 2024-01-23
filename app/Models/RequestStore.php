<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestStore extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function stores()
    {
        return $this->belongsTo(Store::class,'store_id','id');
    }
}
