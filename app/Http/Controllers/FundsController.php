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
        $user_id = Auth::user()->getAuthIdentifier();
        $risks = Risk::all();
        return view('funds.create', compact('risks', 'user_id'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->getAuthIdentifier() == $request->user_id)
        {
            Fund::create($request->all());
        }
        return redirect('/funds');
    }

    public function show($id)
    {
        $user = Auth::user();
        $fund = Fund::findOrFail($id);

        return view('funds.show', compact('fund', 'user'));
    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {
        $fund = Fund::findOrFail($id);
        $fund->update($request->all());
        return redirect('/funds');
    }

    public function destroy($id)
    {
        Fund::whereId($id)->delete();
        return redirect('/funds');
    }
}
