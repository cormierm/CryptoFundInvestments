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
        return $this->getTotalByCurrencyId(1);
    }

    public function totalShares() {
        return $this->confirmInvestments()->sum('shares');
    }

    public function totalSharesByTimestamp($ts) {
        return $this->confirmInvestments()->where('created_at', '<', $ts)->sum('shares');
    }

    public function transactions() {
        return $this->hasMany('App\Transaction', 'fund_id', 'id');
    }

    public function transactionsByTimestamp($ts) {
        return $this->hasMany('App\Transaction', 'fund_id', 'id')
            ->where('created_at', '<',  $ts)->get();
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

    public function marketValueByTimestamp($ts) {
        $value = 0;

        foreach ($this->balancesByTimestamp($ts) as $coin_id => $balance) {
            if ($coin_id != 1) {
                $currency = Currency::find($coin_id);

                $value += $currency->coinPriceByTimestamp($ts)->price_cad * $balance;
            }
            else {
                $value += $balance;
            }
        }

        return $value;
    }

    public function shareMarketValue() {
        if($this->totalShares() != 0) {
            return $this->marketValue() / $this->totalShares();
        }

        return 0;
    }

    public function shareMarketValueByTimestamp($ts) {
        $totalShares = $this->totalSharesByTimestamp($ts);
        if($totalShares != 0) {
            return $this->marketValueByTimestamp($ts) / $totalShares;
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
            if($key != '' && $balance > 0) {
                $bals[Currency::find($key)->symbol] = $balance;
            }
        }

        return $bals;
    }

    public function balancesByTimestamp($ts) {
        $balances = array();
        foreach ($this->transactionsByTimestamp($ts) as $transaction) {

            if(!isset($balances[$transaction->sell_currency_id])) {
                $balances[$transaction->sell_currency_id] = 0;
            }

            if(!isset($balances[$transaction->buy_currency_id])) {
                $balances[$transaction->buy_currency_id] = 0;
            }

            $balances[$transaction->sell_currency_id] -= $transaction->sell_amount;
            $balances[$transaction->buy_currency_id] += $transaction->buy_amount;
        }

        unset($balances['']);

        return $balances;

//        $bals = array();
//        foreach ($balances as $key => $balance) {
//            if($key != '' && $balance > 0) {
//                $bals[Currency::find($key)->symbol] = $balance;
//            }
//        }
//
//        return $bals;
    }

    public function userMarketValue() {
        $investments = $this->confirmInvestments()->where('user_id', Auth::user()->getAuthIdentifier())->get();
        $value = 0;
        foreach($investments as $investment) {
            $value += $investment->marketValue();
        }
        return $value;
    }

    public function userShares() {
        $investments = $this->confirmInvestments()->where('user_id', Auth::user()->getAuthIdentifier())->get();
        $value = 0;
        foreach($investments as $investment) {
            $value += $investment->shares;
        }
        return $value;
    }

    public function userAvailableShares() {
        $investments = $this->confirmInvestments()->where('user_id', Auth::user()->getAuthIdentifier())->get();
        $value = 0;
        foreach($investments as $investment) {
            $value += $investment->shares;
        }

        $fundsRemoval = FundsRemoval::where('user_id', Auth::user()->getAuthIdentifier())->where('fund_id', $this->attributes['id'])->get();
        foreach($fundsRemoval as $fr) {
            $value -= $fr->share_amount;
        }
        return $value;
    }
}
