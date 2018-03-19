<?php

namespace App\Http\Controllers;

use App\Investment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function dashboard()
    {
        if (Auth::user()->isTrader())
        {
            $funds = Auth::user()->funds;
            return view('trader_dashboard', compact('funds'));
        }

        $investments = Investment::where('user_id', Auth::user()->getAuthIdentifier())->get();

        return view('client_dashboard', compact('investments'));
    }
}
