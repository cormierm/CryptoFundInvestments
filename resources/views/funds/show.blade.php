@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>
            Fund Details
            @if ($fund->user_id == Auth::user()->getAuthIdentifier())
                <a href="/funds/{{ $fund->id }}/edit"><button class="btn btn-primary">Edit Fund</button></a>
            @endif
            <a href=""><button class="btn btn-danger">Invest In Fund</button></a>
        </h1>
        <br>
        <h2><small>Name:</small> {{ $fund->name }}</h2>
        <br>
        <p><strong>Description:</strong> {{ $fund->description }}</p>
        <br>
        <p>Risk: {{ $fund->risk->name }}</p>
        <p>Creator: <a href="/trader/{{ $fund->user_id }}">{{ $fund->user->first_name . " " . $fund->user->last_name }}</a></p>

    </div>
@endsection