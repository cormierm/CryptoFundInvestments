<?php

namespace App\Http\Controllers;

use App\CoinPrice;
use App\Currency;
use App\Fund;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function fundMarketSharePriceHistory($id, $days) {
        $fund = Fund::findOrFail($id);

        $ts = Carbon::now()->subDays($days);

        $timestampList = $this->getTimestamps($ts);

        $data = array();

        foreach($timestampList as $timestamp) {
            $data[$timestamp->timestamp] = $fund->marketValueByTimestamp($timestamp);

        }

        return $data;

    }

    private function getTimestamps($ts) {
        $btc = Currency::where('symbol', 'BTC')->first();

        $temp = CoinPrice::where('currency_id', $btc->id)->where('created_at', '>', $ts)->get()->pluck('created_at');

        return $temp;
    }
}
