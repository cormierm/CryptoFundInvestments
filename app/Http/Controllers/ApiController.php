<?php

namespace App\Http\Controllers;

use App\CoinPrice;
use App\Currency;
use App\Fund;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiController extends Controller
{
    public function fundMarketSharePriceHistory($id, $days) {
        try {
            $fund = Fund::findOrFail($id);
        }
        catch (ModelNotFoundException $ex) {
            return response()->json(['message' => 'Invalid fund id'],
                Response::HTTP_BAD_REQUEST);
        }

        $ts = Carbon::now()->subDays($days);

        $timestampList = $this->getTimestamps($ts);

        $data = array();

        foreach($timestampList as $timestamp) {
            $data[$timestamp->timestamp] = $fund->shareMarketValueByTimestamp($timestamp);
        }

        return response()->json($data,Response::HTTP_OK);
    }

    private function getTimestamps($ts) {
        $btc = Currency::where('symbol', 'BTC')->first();

        $temp = CoinPrice::where('currency_id', $btc->id)->where('created_at', '>', $ts)->get()->pluck('created_at');

        return $temp;
    }
}
