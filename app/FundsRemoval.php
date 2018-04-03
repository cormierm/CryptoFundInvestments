<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FundsRemoval extends Model
{
    protected $fillable = ['user_id', 'fund_id', 'share_amount'];

    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function fund() {
        return $this->hasOne('App\Fund', 'id', 'fund_id');
    }

    public function marketValue() {
        return $this->fund->shareMarketValue() * $this->attributes['share_amount'];
    }
}
