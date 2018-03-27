<?php

namespace App\Http\Controllers;

use App\FundsRemoval;
use App\Investment;
use App\Transaction;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FundsRemovalController extends Controller
{
    public function cancel(Request $request) {
        try {
            $fundRemoval = FundsRemoval::findOrFail($request->removal_id);
        }
        catch (ModelNotFoundException $ex) {
            return redirect()->back()->with('errorMessage', 'Error cancelling Fund Removal Request');
        }

        if($fundRemoval->user_id = Auth::user()->getAuthIdentifier()) {
            $fundRemoval->delete();
            return redirect()->back()->with('successMessage', 'Successfully cancelled Fund Remove Request');
        }

        return redirect()->back()->with('errorMessage', 'Error cancelling Fund Removal Request');
    }

    public function approve(Request $request) {
        try {
            $fundRemoval = FundsRemoval::findOrFail($request->removal_id);
        }
        catch (ModelNotFoundException $ex) {
            return redirect()->back()->with('errorMessage', 'Error approving Fund Removal Request');
        }

        if($fundRemoval->fund->user->id = Auth::user()->getAuthIdentifier()) {
            $marketValue = $fundRemoval->marketValue();

            Transaction::create([
                'fund_id'             => $fundRemoval->fund->id,
                'transaction_type_id' => 4,
                'sell_currency_id'    => 1,
                'sell_amount'          => $marketValue
            ]);

            Investment::create([
                'user_id'     => $fundRemoval->user->id,
                'fund_id'     => $fundRemoval->fund->id,
                'amount'      => $marketValue * -1,
                'shares'      => $fundRemoval->share_amount * -1,
                'is_approved' => 1
            ]);

            $fundRemoval->delete();

            return redirect()->back()->with('successMessage', 'Successfully approved Fund Remove Request');
        }

        return redirect()->back()->with('errorMessage', 'Error approving Fund Removal Request');
    }
}
