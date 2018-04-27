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

        if ($days < 1) {
            return response()->json(['message' => 'Days cannot be less then 1'],
                Response::HTTP_BAD_REQUEST);
        }

        if ($days == '1') {
            $timestampList = $this->getTimestamps(time(), 24, 1);
        }
        else {
            $timestampList = $this->getTimestamps(time(), $days, 24);
        }

        $data = array();

        foreach($timestampList as $timestamp) {
            $data[$timestamp] = $fund->shareMarketValueByTimestamp($timestamp);
        }

        return response()->json($data,Response::HTTP_OK);
    }

    private function getTimestamps($timestamp, $count, $hours) {
        $timestampList = array();

        for ($i=0; $i < $count; $i++) {
            $timestampMinusHours = $timestamp - ($i * (60 * 60 * $hours));
            array_push($timestampList, $timestampMinusHours);
        }

        return $timestampList;
    }
}
