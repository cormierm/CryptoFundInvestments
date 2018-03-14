<?php

namespace App\Http\Controllers;

use App\Fund;
use App\Investment;
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
}
