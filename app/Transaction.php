<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['fund_id', 'sell_currency_id', 'sell_amount', 'sell_price', 'buy_currency_id', 'buy_amount',
        'buy_price'];

    public function buy_currency() {
        return $this->hasOne('App\Currency', 'id', 'buy_currency_id');
    }

    public function sell_currency() {
        return $this->hasOne('App\Currency', 'id', 'sell_currency_id');
    }
}
