<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['fund_id', 'transaction_type_id', 'sell_currency_id', 'sell_amount', 'buy_currency_id',
        'buy_amount', 'rate'];

    public function buy_currency() {
        return $this->hasOne('App\Currency', 'id', 'buy_currency_id');
    }

    public function sell_currency() {
        return $this->hasOne('App\Currency', 'id', 'sell_currency_id');
    }

    public function type() {
        return $this->hasOne('App\TransactionType', 'id', 'transaction_type_id');
    }

    public function fund() {
        return $this->hasOne('App\Fund', 'id', 'fund_id');
    }
}
