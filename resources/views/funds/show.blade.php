@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Fund Details</h1>

        <a href=""><button class="btn btn-danger">Invest In Fund</button></a>
        @if ($fund->user_id == $user->id)
            <a href="/risk/edit"><button class="btn btn-primary">Edit Fund</button></a>
        @endif
        <br>
        Name: {{ $fund->name }}
        <br>
        Description: {{ $fund->description }}
        <br>
        Risk: {{ $fund->risk->name }}
        <br>
        Creator: <a href="/trader/{{ $fund->user_id }}">{{ $fund->user->first_name . " " . $fund->user->last_name }}</a>

    </div>
@endsection