<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\PaginationServiceProvider;
use Illuminate\Support\Facades\Auth;

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

    public function transactions() {
        return $this->hasMany('App\Transaction', 'fund_id', 'id');
    }

    public function getTotalByCurrencyId($currencyId) {
        $sell_sum = $this->transactions()->where('sell_currency_id', $currencyId)->sum('sell_amount');
        $buy_sum = $this->transactions()->where('buy_currency_id', $currencyId)->sum('buy_amount');
        return $buy_sum - $sell_sum;
    }

    public function marketValue() {
        $value = 0;

        foreach ($this->allBalances() as $symbol => $balance) {
            $currency = Currency::where('symbol', $symbol)->first();
            $value += $currency->latestCoinPrice->price_cad * $balance;
        }

        return $value;
    }

    public function shareMarketValue() {
        if($this->totalShares() != 0) {
            return $this->marketValue() / $this->totalShares();
        }

        return 0;
    }

    public function allBalances() {
        $balances = array();
        foreach ($this->transactions()->get() as $transaction) {

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

    public function userMarketValue() {
        $investments = $this->confirmInvestments()->where('user_id', Auth::user()->getAuthIdentifier())->get();
        $value = 0;
        foreach($investments as $investment) {
            $value += $investment->marketValue();
        }
        return $value;
    }
}
