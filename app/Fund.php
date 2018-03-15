<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    protected $fillable = [ 'name', 'description', 'user_id', 'risk_id' ];

    public function risk() {
        return $this->hasOne('App\Risk', 'id', 'risk_id');
    }

    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function confirmInvestments() {
        return $this->hasMany('App\Investment', 'fund_id', 'id')
            ->where('is_approved', true);
    }

    public function availableCash() {
        return $this->confirmInvestments()->sum('amount');
    }

    public function totalShares() {
        return $this->confirmInvestments()->sum('shares');
    }

    public function getMarketValue() {
        return 1 * $this->availableCash();
    }

    public function getTransactions() {
        return $this->hasMany('App\Transaction', 'fund_id', 'id');
    }

    public function getTotalByCurrencyId($currencyId) {
        $sell_sum = $this->getTransactions()->where('sell_currency_id', $currencyId)->sum('sell_amount');
        $buy_sum = $this->getTransactions()->where('buy_currency_id', $currencyId)->sum('buy_amount');
        return $buy_sum - $sell_sum;
    }

    public function allBalances() {
        $balances = array();
        foreach ($this->getTransactions()->get() as $transaction) {

            if(!isset($balances[$transaction->sell_currency_id])) {
                $balances[$transaction->sell_currency] = 0;
            }

            if(!isset($balances[$transaction->buy_currency_id])) {
                $balances[$transaction->buy_currency_id] = 0;
            }

            $balances[$transaction->sell_currency_id] -= $transaction->sell_amount;
            $balances[$transaction->buy_currency_id] += $transaction->buy_amount;
        }

        $bals = array();
        foreach ($balances as $key => $balance) {
            if($key != '') {
                $bals[Currency::find($key)->symbol] = $balance;
            }
        }

        return $bals;
    }
}
