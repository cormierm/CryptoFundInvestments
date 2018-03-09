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
                        <h3>Currency Holdings</h3>
                        <table class="table">
                        <tr>
                            <th>Currency</th>
                            <th>Holdings</th>
                            <th>Price(CAD)</th>
                            <th>Holdings(CAD)</th>
                            <th>24h Change</th>
                        </tr>
                        </table>

                        @if ($fund->user_id == Auth::user()->getAuthIdentifier())
                                <a href="/funds/{{ $fund->id }}/edit"><button class="btn btn-primary">Edit Fund</button></a>
                            @endif
                            <a href=""><button class="btn btn-danger">Invest In Fund</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection