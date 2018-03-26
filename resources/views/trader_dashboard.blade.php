@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">{{ Auth::user()->first_name }}'s Trader Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <h3>Your Funds</h3>
                    <table class="table">
                        <tr>
                            <th>Fund Name</th>
                            <th>Holdings(CAD)</th>
                            <th>24h Percent Change</th>
                            <th>Risk Type</th>
                        </tr>
                        @foreach ($funds as $fund)
                            <tr>
                                <td><a href="/funds/{{ $fund->id }}">{{ $fund->name }}</a></td>
                                <td>${{ number_format($fund->marketValue(), 2) }}</td>
                                <td></td>
                                <td>{{ $fund->risk->name }}</td>
                            </tr>
                        @endforeach
                    </table>

                </div>
            </div>
            <br>
            <div class="card card-default">
                <div class="card-header">Coin Lookup</div>

                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Currency</th>
                            <th>Price (CAD)</th>
                            <th>Volume (CAD)</th>
                            <th>1Hr Change</th>
                            <th>24Hr Change</th>
                            <th>7 Day Change</th>
                        </tr>
                        @foreach ($coins as $coin)
                            <tr>
                                <td>{{ $coin->name }} (<strong>{{ $coin->symbol }}</strong>)</td>
                                <td>${{ number_format($coin->latestCoinPrice->price_cad, 2) }}</td>
                                <td>${{ number_format($coin->latestCoinPrice->volume_cad, 0) }}</td>
                                @if ($coin->latestCoinPrice->percent_change_hour < 0)
                                    <td><font color="red">{{ $coin->latestCoinPrice->percent_change_hour }}%</font></td>
                                @else
                                    <td><font color="green">{{ $coin->latestCoinPrice->percent_change_hour }}%</font></td>
                                @endif
                                @if ($coin->latestCoinPrice->percent_change_day < 0)
                                    <td><font color="red">{{ $coin->latestCoinPrice->percent_change_day }}%</font></td>
                                @else
                                    <td><font color="green">{{ $coin->latestCoinPrice->percent_change_day }}%</font></td>
                                @endif
                                @if ($coin->latestCoinPrice->percent_change_week < 0)
                                    <td><font color="red">{{ $coin->latestCoinPrice->percent_change_week }}%</font></td>
                                @else
                                    <td><font color="green">{{ $coin->latestCoinPrice->percent_change_week }}%</font></td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
