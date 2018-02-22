<?php

namespace App\Http\Controllers;

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
        if (Auth::user()->roles->has(1))
        {
            $funds = Auth::user()->funds;
            return view('trader_dashboard', compact('funds'));
        }
        return view('client_dashboard');
    }
}
