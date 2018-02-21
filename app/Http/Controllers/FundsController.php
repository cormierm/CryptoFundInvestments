<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FundsController extends Controller
{
    public function index()
    {
        $funds = Fund::all();

        return view('funds.index', compact('funds'));
    }

    public function create()
    {
        return view('funds.create');
    }

    public function store(Request $request)
    {
        Fund::create($request->all());

        return redirect('/funds');
    }

    public function show($id)
    {
        $fund = Fund::findOrFail($id);

        return view('funds.show', compact('fund'));
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
