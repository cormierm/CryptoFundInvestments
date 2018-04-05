<?php

namespace App\Http\Controllers;

use App\Currency;
use App\FundsRemoval;
use App\Investment;
use App\Transaction;
use App\TransactionType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Fund;
use App\Risk;

class FundsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        if ($request->query('showClosed') == 'true') {
            $funds = Fund::all();
            $showClosed = true;
        }
        else {
            $funds = Fund::all()->where('is_closed', false);
            $showClosed = false;
        }

        return view('funds.index', compact('funds', 'user', 'showClosed'));
    }

    public function create()
    {
        if (Auth::user()->isTrader()) {
            $user_id = Auth::user()->getAuthIdentifier();
            $risks = Risk::all();
            return view('funds.create', compact('risks', 'user_id'));
        }
        return redirect('/funds');

    }

    public function store(Request $request)
    {
        if (Auth::user()->getAuthIdentifier() == $request->user_id)
        {
            $this->validate($request, [
                'name'        =>  'required',
                'description' =>  'required',
                'risk_id'     =>  'required'
            ]);
            Fund::create($request->all());
            return redirect('/funds');
        }
    }

    public function show($id)
    {
        $user = Auth::user();

        try {
            $fund = Fund::findOrFail($id);

        }
        catch (ModelNotFoundException $ex) {
            return redirect()->back()->with('errorMessage', 'There was an error retrieving fund');
        }

        if ($fund->user_id == $user->id) {
            $unconfirmedInvestments = Investment::all()
                ->where('fund_id', $fund->id)
                ->where('is_approved', false);
            $transactions = $fund->transactions()->orderByDesc('created_at')->get();
            $currencies = Currency::all();
            $transactionTypes = TransactionType::all();

            $pendingFundRemovals = FundsRemoval::where('fund_id', $fund->id)->get();

            return view('funds.management',
                compact('fund', 'unconfirmedInvestments', 'transactions', 'currencies', 'transactionTypes', 'pendingFundRemovals'));
        }

        $transactions = $fund->transactions()->orderByDesc('created_at')->get();

        $fundsRemoval = $user->fundsRemovalRequests($fund->id);

        $confirmedInvestments = Investment::where('user_id', Auth::user()->getAuthIdentifier())->where('fund_id', $id)->where('is_approved', true)->get();
        $unconfirmedInvestments = Investment::where('user_id', Auth::user()->getAuthIdentifier())->where('fund_id', $id)->where('is_approved', false)->get();

        return view('funds.show', compact('fund', 'user', 'confirmedInvestments', 'unconfirmedInvestments', 'transactions', 'fundsRemoval'));
    }

    public function edit($id)
    {

        $fund = Fund::findOrFail($id);
        if ($fund->user_id == Auth::user()->getAuthIdentifier()){
            $risks = Risk::all();
            return view('funds.edit', compact('fund','risks'));
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'        =>  'required',
            'description' =>  'required|min:5',
            'risk_id'     =>  'required'
        ]);

        $fund = Fund::findOrFail($id);
        if (Auth::user()->getAuthIdentifier() == $request->user_id)
        {
            $fund->update($request->all());
        }
        return redirect('/funds/');
    }

    public function destroy($id)
    {
        $fund = $this->getFund($id);

        $balances = $fund->allBalances();

        if(Auth::user()->getAuthIdentifier() != $fund->user->id) {
            return redirect()->back()->with('errorMessage',
                "Permission denied! You are not the creator of this fund.");
        }

        if($fund->is_closed) {
            return redirect()->back()->with('errorMessage', 'Fund already closed.');
        }

        if((count($balances) == 1 && isset($balances['CAD']) || count($balances) == 0)){
            $shareMarketValue = $fund->shareMarketValue();

            $investments = Investment::where('fund_id', $fund->id)->where('is_approved', true)
                ->groupBy('user_id')
                ->selectRaw('sum(shares) as sum_shares, user_id')
                ->pluck('sum_shares', 'user_id');

            foreach ($investments as $user_id => $shares) {
                $amount = $shares * $shareMarketValue;

                Transaction::create([
                    'fund_id'             => $fund->id,
                    'transaction_type_id' => 4,
                    'sell_currency_id'    => 1,
                    'sell_amount'          => $amount
                ]);

                Investment::create([
                    'user_id'     => $user_id,
                    'fund_id'     => $fund->id,
                    'amount'      => $amount * -1,
                    'shares'      => $shares * -1,
                    'is_approved' => 1
                ]);
            }

            $fund->is_closed = true;
            $fund->closed_at = Carbon::now()->toDateTimeString();
            $fund->save();

            return redirect()->back()->with('successMessage', 'Successfully closed fund');
        }

        return redirect()->back()->with('errorMessage',
            "Fund currently contains holdings other then CAD. All balances must be removed or converted into CAD before fund can be closed.");

    }

    private function getFund($id) {
        try {
            $fund = Fund::findOrFail($id);
        }
        catch (ModelNotFoundException $ex) {
            return redirect()->back()->with('errorMessage', 'There was an error retrieving fund');
        }

        return $fund;
    }
}
