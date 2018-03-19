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
                                    <td>{{ $investment->shares }}</td>
                                    <td>${{ $investment->shares * $investment->fund->shareMarketValue() }}</td>
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
