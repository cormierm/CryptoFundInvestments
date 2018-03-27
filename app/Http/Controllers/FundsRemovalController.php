<?php

namespace App\Http\Controllers;

use App\FundsRemoval;
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

        return redirect()->back()->with('errorMessage', '123Error cancelling Fund Removal Request');
    }
}
