@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Fund Details</div>

                    <div class="card-body">

                        <h2><small>Name:</small> {{ $fund->name }}</h2>
                        <br>
                        <p><strong>Description:</strong> {{ $fund->description }}</p>
                        <p><strong>Risk:</strong> {{ $fund->risk->name }}</p>
                        <p><strong>Creator:</strong> <a href="/trader/{{ $fund->user_id }}">{{ $fund->user->first_name . " " . $fund->user->last_name }}</a></p>
                        <p><strong>Current Holdings:</strong>
                        <table class="table">
                            <tr>
                                <th>Currency</th>
                                <th>Balance</th>
                            </tr>
                            @foreach($fund->allBalances() as $currency => $balance)
                                <tr>
                                    <td>{{ $currency }}</td>
                                    <td>{{ $balance }}</td>
                                </tr>
                            @endforeach
                        </table>
                        <p><strong>Total Shares:</strong> {{ number_format($fund->totalShares(), 2) }}</p>
                        <p><strong>Current Market Value (CAD):</strong> ${{ number_format($fund->marketValue(), 2) }}</p>
                        <p><strong>Share Market Value (CAD):</strong> ${{ number_format($fund->shareMarketValue(), 2) }}</p>


                        @if ($fund->user_id == Auth::user()->getAuthIdentifier())
                            <a href="/funds/{{ $fund->id }}/edit"><button class="btn btn-primary">Edit Fund</button></a>
                        @endif
                        <a href="/investments/create/{{ $fund->id }}"><button class="btn btn-danger">Invest In Fund</button></a>
                    </div>
                </div>
                @if($investments->count() > 0)
                    <div class="card card-default">
                        <div class="card-header">Your Investments</div>

                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <th>Investment(CAD)</th>
                                    <th>Shares</th>
                                    <th>Current Market Value(CAD)</th>
                                    <th>Approved</th>
                                    <th>Created on</th>
                                </tr>
                                @foreach ($investments as $investment)
                                    <tr>
                                        <td>${{ number_format($investment->amount, 2) }}</td>
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