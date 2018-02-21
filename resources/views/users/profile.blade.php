@extends('layouts.app')

@section('content')
    <div class="container">

        <a href="/profile/edit"><button class="btn btn-primary">Edit Profile</button></a>
        <a href="/profile/apply_trader_role"><button class="btn btn-primary">Apply to be trader</button></a>
        <br>
        Email: {{ $user->email }}
        <br>
        First Name: {{ $user->first_name }}
        <br>
        Last Name: {{ $user->last_name }}
        <br>
        Phone: {{ $user->phone }}
        <br>
        Trader Title: {{ $user->trader_title }}
        <br>
        Trader Description: {{ $user->trader_description }}

    </div>
@endsection