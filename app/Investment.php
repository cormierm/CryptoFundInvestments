<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    protected $fillable = ['user_id', 'fund_id', 'amount', 'shares', 'is_approved'];

    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function fund() {
        return $this->hasOne('App\Fund', 'id', 'fund_id');
    }

    public function marketValue() {
        return $this->attributes['shares'] * $this->fund->shareMarketValue();
    }
}
