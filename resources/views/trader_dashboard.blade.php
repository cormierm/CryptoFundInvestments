@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">Trader Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h1 class="pull-right">Welcome back {{ Auth::user()->first_name }}!
                        <a href="/funds/create"><button class="btn btn-primary">Create New Fund</button></a>
                        <a href="/funds"><button class="btn btn-info">View All Funds</button></a>
                        <a href="/profile"><button class="btn btn-primary">User Profile</button></a>
                    </h1>
                    <h3>Portfolio</h3>
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
                                <td></td>
                                <td></td>
                                <td>{{ $fund->risk->name }}</td>
                            </tr>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
