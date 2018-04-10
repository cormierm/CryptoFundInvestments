@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card card-default">
                    <div class="card-header">
                        <h3>
                            Fund Details
                            <a href="/investments/create/{{ $fund->id }}">
                                <button class="btn btn-info float-right">Invest In Fund</button>
                            </a>
                            <a href="/investments/removal/{{ $fund->id }}">
                                <button class="btn btn-danger float-right">Request Investment Removal</button>
                            </a>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="canvasDiv">
                            <p>Loading Chart...</p>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
                            <canvas id="fundChart"></canvas>
                            <script>
                                window.onload = function() {
                                    var xmlhttp = new XMLHttpRequest();
                                    xmlhttp.onreadystatechange = function() {
                                        if (this.readyState == 4 && this.status == 200) {
                                            document.getElementById('canvasDiv').innerHTML = "<canvas id=\"fundChart\"></canvas>";
                                            var ctx = document.getElementById('fundChart').getContext('2d');
                                            var timeStamp = [];
                                            var sharePrice = [];
                                            var data = JSON.parse(this.response);

                                            for(var key in data) {
                                                var date = new Date(key*1000);
                                                timeStamp.push(date.getHours() + ':' + (date.getMinutes()<10?'0':'') + date.getMinutes());
                                                sharePrice.push(data[key]);
                                            }

                                            var chart = new Chart(ctx, {
                                                type: 'line',

                                                data: {
                                                    labels: timeStamp,
                                                    datasets: [{
                                                        label: "Price per Share",
                                                        backgroundColor: '#7AA8C0',
                                                        borderColor: '#427995',
                                                        data: sharePrice,
                                                    }]
                                                },

                                                options: {

                                                }
                                            });
                                        }
                                    };
                                    xmlhttp.open("GET", "/api/funds/marketSharePriceHistory/{{ $fund->id }}/1");
                                    xmlhttp.send();

                                };
                            </script>
                        </div>
                        <h2>
                            <small>Name:</small> {{ $fund->name }}
                            @if($fund->is_closed)
                                <span class="badge badge-danger">Closed</span>
                            @endif
                        </h2>
                        <br>
                        <p><strong>Description:</strong> {{ $fund->description }}</p>
                        <p><strong>Risk:</strong> {{ $fund->risk->name }}</p>
                        <p><strong>Creator:</strong> <a href="/trader/{{ $fund->user_id }}">{{ $fund->user->first_name . " " . $fund->user->last_name }}</a></p>

                        <p><strong>Total Shares:</strong> {{ number_format($fund->totalShares(), 2) }}</p>
                        <p><strong>Market Value (CAD):</strong> ${{ number_format($fund->marketValue(), 2) }}</p>
                        <p><strong>Share Market Value (CAD):</strong> ${{ number_format($fund->shareMarketValue(), 2) }}</p>

                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-default">
                    <div class="card-header">Current Holdings</div>
                    <div class="card-body">
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
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-12">
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

                @if($unconfirmedInvestments->count() > 0)
                    <br>
                    <div class="card card-default">
                        <div class="card-header">Investments Waiting Approval</div>

                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <th>Submitted on</th>
                                    <th>Investment (CAD)</th>
                                </tr>
                                @foreach ($unconfirmedInvestments as $investment)
                                    <tr>
                                        <td>{{ $investment->created_at }}</td>
                                        <td>${{ number_format($investment->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                @endif

                @if($confirmedInvestments->count() > 0)
                    <br>
                    <div class="card card-default">
                        <div class="card-header">Your Investment History</div>

                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <th>Timestamp</th>
                                    <th>Investment (CAD)</th>
                                    <th>Shares</th>
                                    <th>Market Value (CAD)</th>
                                </tr>
                                @foreach ($confirmedInvestments as $investment)
                                    <tr>
                                        <td>{{ $investment->created_at }}</td>
                                        <td>${{ number_format($investment->amount, 2) }}</td>
                                        <td>{{ number_format($investment->shares, 2) }}</td>
                                        <td>${{ number_format(abs($investment->marketValue()), 2) }}</td>
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
                                <th>Timestamp</th>
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
                                    <td>
                                        @if($transaction->buy_amount != 0)
                                            {{  $transaction->buy_amount }}</td>
                                        @endif
                                    <td>
                                        @if($transaction->sell_currency)
                                            {{  $transaction->sell_currency->name }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($transaction->sell_amount != 0)
                                            {{  $transaction->sell_amount }}</td>
                                        @endif
                                    <td>
                                        @if($transaction->rate != 0)
                                            {{ $transaction->rate }}</td>
                                        @endif
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