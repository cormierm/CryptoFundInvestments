@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-5">
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
                        <a href="/investments/create/{{ $fund->id }}"><button class="btn btn-info">Invest In Fund</button></a>
                        <a href="/investments/removal/{{ $fund->id }}"><button class="btn btn-danger">Request Investment Removal</button></a>
                    </div>
                </div>
                @if($fundsRemoval->count() > 0)
                    <br>
                    <div class="card card-default">
                        <div class="card-header">Pending Fund Removal Requests</div>

                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <th>Shares Amount</th>
                                    <th>Market Value(CAD)</th>
                                    <th>Created on</th>
                                    <th></th>
                                </tr>
                                @foreach ($fundsRemoval as $fr)
                                    <tr>
                                        <td>${{ $fr->share_amount }}</td>
                                        <td>${{ number_format($fr->marketValue(), 2) }}</td>
                                        <td>{{ $fr->created_at }}</td>
                                        <td>
                                            <form method="post" action="/investments/remove/cancel">
                                                @csrf
                                                <input type="hidden" name="removal_id" id="removal_id" value="{{ $fr->id }}"/>
                                                <input type="submit" value="Cancel" class="btn btn-danger"/>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                @endif
                @if($investments->count() > 0)
                    <br>
                    <div class="card card-default">
                        <div class="card-header">Your Investment History</div>

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
                                        <td>${{ number_format(abs($investment->marketValue()), 2) }}</td>
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
            <div class="col-md-7">
                <div class="card card-default">
                    <div class="card-header">Transaction History</div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Type</th>
                                <th>Buy Currency</th>
                                <th>Buy Amount</th>
                                <th>Sell Currency</th>
                                <th>Sell Amount</th>
                                <th>Rate</th>
                                <th>Submitted on</th>
                            </tr>

                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>
                                        {{ $transaction->type->name }}
                                    </td>
                                    <td>
                                        @if($transaction->buy_currency)
                                            {{  $transaction->buy_currency->name }}
                                        @endif
                                    </td>
                                    <td>{{  $transaction->buy_amount }}</td>
                                    <td>
                                        @if($transaction->sell_currency)
                                            {{  $transaction->sell_currency->name }}
                                        @endif
                                    </td>
                                    <td>{{  $transaction->sell_amount }}</td>
                                    <td>{{ $transaction->rate }}</td>
                                    <td>{{  $transaction->created_at }}</td>
                                </tr>
                            @endforeach

                        </table>
                    </div>
            </div>
        </div>
    </div>
@endsection