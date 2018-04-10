<?php

namespace App\Http\Controllers;

use App\Fund;
use App\FundsRemoval;
use App\Investment;
use App\Transaction;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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

        if ($fund->is_closed) {
            return redirect()->back()->with('errorMessage', 'Error processing due to fund being closed.');
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

        if ($fund->is_closed) {
            return redirect()->back()->with('errorMessage', 'Error processing due to fund being closed.');
        }

        $user = User::find(Auth::user()->getAuthIdentifier());

        Investment::create([
            'user_id'=>$user->id,
            'fund_id'=>$request->fund_id,
            'amount'=>$request->amount
            ]);

        $user = User::find(Auth::user()->getAuthIdentifier());

        $data = [
            'owner'  => $fund->user->first_name . ' ' . $fund->user->last_name,
            'fund_name' => $fund->name,
            'amount' => $request->amount,
            'client' => $user->email
        ];

//        Mail::send('emails.investment', $data, function ($message) use ($fund) {
//            $message->to(
//                $fund->user->email,
//                $fund->user->first_name . ' ' . $fund->user->last_name
//            )->subject('You have received an CryptoFundInvestment investment');
//        });

        return redirect()->route('funds.show', [$request->fund_id])->with('successMessage',
            'Successfully submitted investment $' . $request->amount . ' into fund \'' . $fund->name . '\'!');
    }

    public function approve(Request $request)
    {
        try {
            $investment = Investment::findOrFail($request->investment_id);
        }
        catch (ModelNotFoundException $ex) {
            return redirect()->back()->with('errorMessage', 'There was an error approving investment.');
        }

        if ($investment->fund->is_closed) {
            return redirect()->back()->with('errorMessage', 'Error processing due to fund being closed.');
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

    public function removal($fundId) {
        try {
            $fund = Fund::findOrFail($fundId);
        }
        catch (ModelNotFoundException $ex) {
            return redirect()->back()->with('errorMessage', 'There was an error retrieving fund');
        }

        if ($fund->is_closed) {
            return redirect()->back()->with('errorMessage', 'Error processing due to fund being closed.');
        }

        $user = Auth::user();

        $availableFunds = $fund->userMarketValue();
        $availableShares = $fund->userAvailableShares();

        if ($availableShares <= 0) {
            return redirect()->back()->with('errorMessage', 'You do not have any available shares in this fund');
        }

        return view('investments.removal', compact('fund', 'availableFunds', 'availableShares'));
    }

    public function removalRequest(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required'
        ]);

        try {
            $fund = Fund::findOrFail($request->fund_id);
        }
        catch (ModelNotFoundException $ex) {
            return redirect()->back()->with('errorMessage', 'There was an error retrieving fund');
        }

        if ($fund->is_closed) {
            return redirect()->back()->with('errorMessage', 'Error processing due to fund being closed.');
        }

        if ($fund->userAvailableShares() < $request->amount) {
            return redirect()->back()->with('errorMessage', 'You do not have any shares for that request');
        }

        FundsRemoval::create([
            'user_id'      => Auth::user()->getAuthIdentifier(),
            'fund_id'      => $fund->id,
            'share_amount' => $request->amount
        ]);

        return redirect('/funds/' . $fund->id)->with('successMessage', 'Successfully submitted investment removal request');
    }
}
