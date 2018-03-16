@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">{{ Auth::user()->first_name }}'s Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <a href="/funds"><h3>Your Funds</h3></a>
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
                                <td>${{ $fund->marketValue() }}</td>
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
