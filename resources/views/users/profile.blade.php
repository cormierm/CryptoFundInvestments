@extends('layouts.app')

@section('content')
    <div class="container">

        @if ($currentUser->id == $user->id)
            <a href="/profile/edit"><button class="btn btn-primary">Edit Profile</button></a>
            @if (!$user->roles->has(1))
                <a href="/profile/apply_trader_role"><button class="btn btn-primary">Apply to be trader</button></a>
            @else
                <a href="/profile/remove_trader_role"><button class="btn btn-danger">Remove trader role</button></a>
            @endif
        @endif
        <br>
        Email: {{ $user->email }}
        <br>
        First Name: {{ $user->first_name }}
        <br>
        Last Name: {{ $user->last_name }}
        <br>
        Phone: {{ $user->phone }}
        @if ($user->roles->has(1))
            <br>
            Trader Title: {{ $user->trader_title }}
            <br>
            Trader Description: {{ $user->trader_description }}
        @endif

    </div>
@endsection