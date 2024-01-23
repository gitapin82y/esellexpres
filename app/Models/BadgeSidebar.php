<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BadgeSidebar extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function users()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
