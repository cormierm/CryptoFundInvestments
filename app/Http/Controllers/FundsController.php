<?php

namespace App\Http\Controllers;

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
        return redirect('FundsController@index');

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
        $fund = Fund::findOrFail($id);

        return view('funds.show', compact('fund', 'user'));
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
