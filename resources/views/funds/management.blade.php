@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Fund Management</div>

                    <div class="card-body">

                        <h2><small>Name:</small> {{ $fund->name }} <a href="/funds/{{ $fund->id }}/edit"><button class="btn btn-primary">Edit Fund Details</button></a></h2>
                        <p><strong>Description:</strong> {{ $fund->description }}</p>
                        <p><strong>Risk:</strong> {{ $fund->risk->name }}</p>
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
                        <p><strong>Total Shares:</strong> {{ number_format($fund->totalShares(),2) }}</p>
                        <p><strong>Current Market Value (CAD):</strong> ${{ number_format($fund->marketValue(), 2) }}</p>
                        <p><strong>Share Market Value (CAD):</strong> ${{ number_format($fund->shareMarketValue(), 2) }}</p>

                        <form method="post" action="/transactions">
                            @csrf
                            <input type=hidden name="fund_id" id="fund_id" value="{{ $fund->id }}" />
                            <table class="table">
                                <tr>
                                    <th>Transaction Type</th>
                                    <th>Buy Currency</th>
                                    <th>Buy Amount</th>
                                    <th>Sell Currency</th>
                                    <th>Sell Amount</th>
                                    <th>Rate</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="transaction_type_id" id="transaction_type_id" class="form-control">
                                            @foreach ($transactionTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="buy_currency_id" id="buy_currency_id" class="form-control">
                                            @foreach ($currencies as $currency)
                                                <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="0.00000001" name="buy_amount" id="buy_amount" class="form-control"/>
                                    </td>
                                    <td>
                                        <select name="sell_currency_id" id="sell_currency_id" class="form-control">
                                            @foreach ($currencies as $currency)
                                                <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="0.00000001" name="sell_amount" id="sell_amount"  class="form-control"/>
                                    </td>
                                    <td>
                                        <input type="number" step="0.00000001" name="rate" id="rate" class="form-control" />
                                    </td>
                                    <td><button class="btn btn-danger">Add Transaction</button></td>

                                </tr>
                            </table>
                        </form>

                    </div>
                </div>
                @if($unconfirmedInvestments->count() > 0)
                    <br>
                    <div class="card card-default">
                        <div class="card-header">Unconfirmed Investments</div>
                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <th>Amount</th>
                                    <th>Client</th>
                                    <th>Submitted on</th>
                                    <th></th>
                                </tr>

                                @foreach($unconfirmedInvestments as $unconfirmedInvestment)
                                    <tr>
                                        <td>${{  $unconfirmedInvestment->amount }}</td>
                                        <td>{{  $unconfirmedInvestment->user->email }}</td>
                                        <td>{{  $unconfirmedInvestment->created_at }}</td>
                                        <td>
                                            <form method="post" action="/investments/approve">
                                                @csrf
                                                <input type="hidden" name="investment_id" value="{{ $unconfirmedInvestment->id }}" />
                                                <button class="btn btn-danger">Approve</button>
                                            </form>
                                        </td>

                                    </tr>
                                @endforeach

                            </table>
                        </div>
                    </div>
                @endif
                <br>
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
    </div>
@endsection