@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-md-12">
        <div class="card card-default">
            <div class="card-header">Fund Details</div>
            <div class="card-body">
                <h2><small>Name:</small> {{ $fund->name }}</h2>
                <br>
                <p><strong>Description:</strong> {{ $fund->description }}</p>
                <p><strong>Risk:</strong> {{ $fund->risk->name }}</p>
                <p><strong>Creator:</strong> <a href="/trader/{{ $fund->user_id }}">{{ $fund->user->first_name . " " . $fund->user->last_name }}</a></p>
            </div>
        </div>
        <br>
        <div class="card card-default">
            <div class="card-header">Fund Investment Removal Request</div>
            <div class="card-body">
                <p>Available shares: {{ $availableShares }}</p>
                <p>Market Value: ${{ number_format($availableFunds, 2) }}</p>


                <form method="post" action="/investments/removal">
                    @csrf

                    <input type="hidden" name="fund_id" id="fund_id" value="{{ $fund->id }}">

                    <div class="form-group">
                        <label for="amount" class="col-form-label ">Enter Amount of Shares You Want To Requests For Removal</label>
                    </div>

                    <div class="form-group">
                        <input id="amount" type="number" step="0.01" class="form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}" name="amount" value="{{ old('amount') }}" required>

                        @if ($errors->has('amount'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('amount') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-danger">
                            Submit Share Removal
                        </button>
                    </div>
                </form>
            </div>
        </div>




</div>


@endsection