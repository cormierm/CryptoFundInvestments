<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'phone', 'trader_title', 'trader_description', 'img_path', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles() {
        return $this->belongsToMany('App\Role');
    }

    public function funds() {
        return $this->hasMany('App\Fund');
    }

    public function investments() {
        return $this->hasMany('App\Investment');
    }

    public function isTrader() {
        if ($this->roles->has(1))
        {
            return true;
        }
        return false;
    }

    public function isAdmin() {
        if ($this->roles->has(3))
        {
            return true;
        }
        return false;
    }

    public function fundsRemovalRequests($fund_id) {
        $fundsRemoval = FundsRemoval::where('user_id', $this->attributes['id'])->where('fund_id', $fund_id)->get();
        return $fundsRemoval;
    }

}
