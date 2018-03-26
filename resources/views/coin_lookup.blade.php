@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
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