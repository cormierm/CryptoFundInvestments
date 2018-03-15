<?php

namespace App\Http\Controllers;

use App\Fund;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionsController extends Controller
{
    public function store(Request $request) {
        $this->validate($request, [
            'fund_id'        =>  'required',
            'buy_currency_id' =>  'required',
            'buy_amount' =>  'required|min:0',
            'buy_price' =>  'required|min:0',
            'sell_currency_id' =>  'required',
            'sell_amount'     =>  'required|min:0',
            'sell_price'     =>  'required|min:0'
        ]);

        try {
            $fund = Fund::findOrFail($request->fund_id);
        }
        catch (ModelNotFoundException $ex) {
            return redirect()->back()->with('errorMessage', 'There was an error retrieving fund information');
        }

        if($fund->getTotalByCurrencyId($request->sell_currency_id) < $request->sell_amount) {
            return redirect()->back()->with('errorMessage', 'Not enough sell currency available');
        }

        if(Auth::user()->getAuthIdentifier() == $fund->user->id) {
            Transaction::create($request->all());
            return redirect()->back()->with('successMessage', 'Successfully added transaction.');
        }


        return redirect()->back()->with('errorMessage', 'There was an error processing transaction.');
    }
}
