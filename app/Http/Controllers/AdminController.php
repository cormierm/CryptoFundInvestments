<?php

namespace App\Http\Controllers;

use App\TraderRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index() {
        $user = User::findOrFail(Auth::user()->getAuthIdentifier());

        if ($user->isAdmin()) {
            $pendingTraders = TraderRequest::all();

            return view('admin.index', compact('pendingTraders'));
        }

        return redirect()->back()->with('errorMessage', 'Access denied! This has been logged!');
    }

    public function approveTraderRequest(Request $request) {
        $user = User::findOrFail(Auth::user()->getAuthIdentifier());

        if ($user->isAdmin()) {
            $traderRequest = TraderRequest::find($request->trader_request_id);

            $traderRequest->user->roles()->sync([1,2]);

            $traderRequest->delete();

            return redirect()->back()->with('successMessage', 'Successfully approved trader request');
        }

        return redirect()->back()->with('errorMessage', 'Error approving trader request');
    }

    public function cancelTraderRequest(Request $request) {
        $user = User::findOrFail(Auth::user()->getAuthIdentifier());

        if ($user->isAdmin()) {
            $traderRequest = TraderRequest::find($request->trader_request_id);
            $traderRequest->delete();

            return redirect()->back()->with('successMessage', 'Successfully cancelled trader request');
        }

        return redirect()->back()->with('errorMessage', 'Error cancelling trader request');
    }
}
