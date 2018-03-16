<?php

namespace App\Http\Controllers;

use App\Fund;
use App\Investment;
use App\Transaction;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestmentsController extends Controller
{
    public function create($fund_id)
    {
        try {
            $fund = Fund::findOrFail($fund_id);
        }
        catch (ModelNotFoundException $ex) {
            return redirect()->back()->with('errorMessage', 'There was an error retrieving fund');
        }

        return view('investments.create', compact('fund'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'amount'        =>  'required'
        ]);

        try {
            $fund = Fund::findOrFail($request->fund_id);
        }
        catch (ModelNotFoundException $ex) {
            return redirect()->back()->with('errorMessage', 'There was an error retrieving fund information');
        }

        Investment::create([
            'user_id'=>Auth::user()->getAuthIdentifier(),
            'fund_id'=>$request->fund_id,
            'amount'=>$request->amount
            ]);

        return redirect()->route('funds.show', [$request->fund_id])->with('successMessage',
            'Successfully invested $' . $request->amount . ' into fund \'' . $fund->name . '\'!');
    }

    public function approve(Request $request)
    {
        try {
            $investment = Investment::findOrFail($request->investment_id);
        }
        catch (ModelNotFoundException $ex) {
            return redirect()->back()->with('errorMessage', 'There was an error approving investment.');
        }

        if($investment->fund->user->id == Auth::user()->getAuthIdentifier()) {
            $investment->is_approved = true;
            $investment->shares = $this->calculateShares($investment);
            $investment->save();

            Transaction::create([
                'fund_id'=>$investment->fund->id,
                'transaction_type_id'=>3,
                'buy_currency_id'=>1,
                'buy_amount'=>$investment->amount]);

            return redirect()->back()->with('successMessage', 'Investment successfully approved.');
        }

        return redirect()->back()->with('errorMessage', 'There was an error approving investment.');
    }

    private function calculateShares($investment) {
        $fund = $investment->fund;
        $totalShares = $fund->totalShares();

        if($totalShares <= 0) {
            return $investment->amount;
        }
        else {
            return $totalShares * ($investment->amount / $fund->marketValue());
        }
    }
}
