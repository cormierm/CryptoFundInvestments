@extends('layouts.app')

@section('content')
<div class="container">
    <h2><small>Name:</small> {{ $fund->name }}</h2>
    <br>
    <p><strong>Description:</strong> {{ $fund->description }}</p>
    <p><strong>Risk:</strong> {{ $fund->risk->name }}</p>
    <p><strong>Creator:</strong> <a href="/trader/{{ $fund->user_id }}">{{ $fund->user->first_name . " " . $fund->user->last_name }}</a></p>

    <form method="post" action="/investments">
        @csrf

        <input type="hidden" name="fund_id" id="fund_id" value="{{ $fund->id }}">

        <div class="form-group row">

            <label for="amount" class="col-md-4 col-form-label text-md-right">Investment Amount</label>

            <div class="col-md-6">
                <input id="amount" type="number" step="0.01" class="form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}" name="amount" value="{{ old('amount') }}" required>
                @if ($errors->has('amount'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('amount') }}</strong>
                    </span>
                @endif
            </div>

        </div>

        <div class="form-group row mb-0">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    Submit Investment
                </button>
            </div>
        </div>
    </form>


</div>


@endsection