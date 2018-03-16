<?php

namespace App\Http\Controllers;

use App\Currency;
use App\Investment;
use App\TransactionType;
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

    public function index()
    {
        $user = Auth::user();
        $funds = Fund::all();

        return view('funds.index', compact('funds', 'user'));
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
            return view('funds.management',
                compact('fund', 'unconfirmedInvestments', 'transactions', 'currencies', 'transactionTypes'));
        }

        $investments = Investment::all()->where('user_id', Auth::user()->getAuthIdentifier());

        return view('funds.show', compact('fund', 'user', 'investments'));
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
        Fund::whereId($id)->delete();
        return redirect('/funds');
    }
}
