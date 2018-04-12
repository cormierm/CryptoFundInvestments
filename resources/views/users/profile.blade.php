@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if ($user->isTrader())
                    <div class="card card-default">
                        <div class="card-header">Trader Profile Information</div>
                        <div class="card-body">
                            <p><strong>Trader Title:</strong> {{ $user->trader_title }}</p>
                            <p><strong>Trader Description:</strong> {{ $user->trader_description }}</p>
                        </div>
                    </div>
                    <br>
                @endif
                <div class="card card-default">
                    <div class="card-header">
                        <h3>
                            {{ $user->first_name }}'s Profile
                            @if($user->id == Auth::user()->getAuthIdentifier())
                                <a href="/profile/edit"><button class="btn btn-primary float-right">Edit Profile</button></a>
                            @endif
                            @if (!$user->isTrader() && $user->id == Auth::user()->getAuthIdentifier())
                                <form method="post" action="/profile/requestTraderRole" class="float-right">
                                    @csrf
                                    <input type="submit" class="btn btn-danger" value="Request to be Trader" />
                                </form>
                            @elseif ($user->id == Auth::user()->getAuthIdentifier())
                                <a href="/profile/remove_trader_role"><button class="btn btn-danger float-right">Remove trader role</button></a>
                            @endif

                        </h3>
                    </div>
                        <div class="card-body">

                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>First Name:</strong> {{ $user->first_name }}</p>
                        <p><strong>Last Name:</strong> {{ $user->last_name }}</p>
                        <p><strong>Phone:</strong> {{ $user->phone }}</p>





                        </div>
                </div>

            </div>
        </div>
    </div>
@endsection