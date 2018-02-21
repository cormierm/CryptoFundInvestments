<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function profile()
    {
        $currentUser = Auth::user();
        $user = Auth::user();

        return view('users.profile', compact('user', 'currentUser'));
    }

    public function trader($id)
    {
        $currentUser = Auth::user();
        $user = User::findOrFail($id);
        if ($user->roles->has(1))
        {
            return view('users.profile', compact('user', 'currentUser'));
        }

        return redirect('/home');
    }

    public function edit()
    {
        $user = Auth::user();

        return view('users.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $user->update($request->all());
        return redirect('/profile');
    }

    public function apply_trader_role()
    {
        $user = Auth::user();
        $user->roles()->sync([1,2]);
        return redirect('/profile');
    }

    public function remove_trader_role()
    {
        $user = Auth::user();
        $user->roles()->sync([2]);
        return redirect('/profile');
    }
}
