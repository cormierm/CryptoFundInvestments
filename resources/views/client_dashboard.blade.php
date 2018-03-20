@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h1>Welcome back {{ Auth::user()->first_name }}!</h1>
                    <h2>Client Dashboard</h2>

                </div>
            </div>
            @if(count($funds) > 0)
                <br>
                <div class="card card-default">
                    <div class="card-header">Your Funds</div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Fund Name</th>
                                <th>Fund Market Value(CAD)</th>
                                <th>Your Market Value(CAD)</th>
                            </tr>
                            @foreach ($funds as $fund)
                                <tr>
                                    <td><a href="/funds/{{ $fund->id }}">{{ $fund->name }}</a></td>
                                    <td>${{ number_format($fund->marketValue(), 2) }}</td>
                                    <td>${{ number_format($fund->userMarketValue(), 2) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            @endif
            @if($investments->count() > 0)
                <br>
                <div class="card card-default">
                    <div class="card-header">Your Investments</div>

                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Fund</th>
                                <th>Investment(CAD)</th>
                                <th>Shares</th>
                                <th>Market Value(CAD)</th>
                                <th>Approved</th>
                                <th>Created on</th>
                            </tr>
                            @foreach ($investments as $investment)
                                <tr>
                                    <td><a href="/funds/{{ $investment->fund->id }}">{{ $investment->fund->name }}</a></td>
                                    <td>${{ $investment->amount }}</td>
                                    <td>{{ number_format($investment->shares, 2) }}</td>
                                    <td>${{ number_format($investment->marketValue(), 2) }}</td>
                                    <td>
                                        @if($investment->is_approved) Yes
                                        @else No
                                        @endif
                                    </td>
                                    <td>{{ $investment->created_at }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
