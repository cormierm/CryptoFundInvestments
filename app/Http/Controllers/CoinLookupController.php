<?php

namespace App\Http\Controllers;

use App\Currency;
use Illuminate\Http\Request;

class CoinLookupController extends Controller
{
    public function index() {
        $coins = Currency::where('currency_type_id', 1)->orderBy('name')->get();

        return view('coin_lookup', compact('coins'));
    }
}
