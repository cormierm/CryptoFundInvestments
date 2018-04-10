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

        if ($days == 1) {
            $interval = 12;
        }
        else {
            $interval = 12;
        }

        $counter = $interval - (count($timestampList) % $interval) + 1;

        foreach($timestampList as $timestamp) {
            if ($counter == $interval) {
                $data[$timestamp->timestamp] = $fund->shareMarketValueByTimestamp($timestamp);
                $counter = 1;
            }
            else {
                $counter++;
            }

        }

        return response()->json($data,Response::HTTP_OK);
    }

    private function getTimestamps($ts) {
        $btc = Currency::where('symbol', 'BTC')->first();

        $temp = CoinPrice::where('currency_id', $btc->id)->where('created_at', '>', $ts)->get()->pluck('created_at');

        return $temp;
    }
}
