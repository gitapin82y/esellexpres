<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $guarded = [];

    public function setBalanceAttribute($value)
    {
        $this->attributes['balance'] = round($value, 2);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public function stores()
    {
        return $this->hasOne(Store::class,'user_id');
    }

    public function transactionBalances()
    {
        return $this->hasMany(TransactionBalance::class,'user_id','id');
    }

    public function bank_account()
    {
        return $this->hasMany(BankAccount::class,'user_id','id');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
