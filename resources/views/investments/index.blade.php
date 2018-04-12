@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if($unconfirmedInvestments->count() > 0)
                    <div class="card card-default">
                        <div class="card-header">Investments Waiting Approval</div>

                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <th>Submitted on</th>
                                    <th>Fund</th>
                                    <th>Investment (CAD)</th>
                                </tr>
                                @foreach ($unconfirmedInvestments as $investment)
                                    <tr>
                                        <td>{{ $investment->created_at }}</td>
                                        <td>
                                            <a href="/funds/{{ $investment->fund->id }}">
                                                {{ $investment->fund->name }}
                                            </a>
                                        </td>
                                        <td>${{ number_format($investment->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                @endif

                <div class="card card-default">
                    <div class="card-header">Your Investment History</div>

                    <div class="card-body">
                        @if($confirmedInvestments->count() > 0)
                            <table class="table">
                                <tr>
                                    <th>Timestamp</th>
                                    <th>Fund</th>
                                    <th>Investment (CAD)</th>
                                    <th>Shares</th>
                                    <th>Market Value (CAD)</th>
                                </tr>
                                @foreach ($confirmedInvestments as $investment)
                                    <tr>
                                        <td>{{ $investment->created_at }}</td>
                                        <td>
                                            <a href="/funds/{{ $investment->fund->id }}">
                                                {{ $investment->fund->name }}
                                            </a>
                                        </td>
                                        <td>${{ number_format($investment->amount, 2) }}</td>
                                        <td>{{ number_format($investment->shares, 2) }}</td>
                                        <td>${{ number_format(abs($investment->marketValue()), 2) }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @else
                            <p>No investment history to display.</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
