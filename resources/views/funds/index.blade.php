@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">All Funds</div>

                    <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Risk Level</th>
                            <th>Created by</th>
                        </tr>
                        @foreach ($funds as $fund)
                            <tr>
                                <td><a href="/funds/{{ $fund->id }}">{{ $fund->name }}</a></td>
                                <td>{{ $fund->description }}</td>
                                <td>{{ $fund->risk->name }}</td>
                                <td><a href="/trader/{{ $fund->user->id }}">{{ $fund->user->first_name . " " . $fund->user->last_name }}</a></td>
                            </tr>
                        @endforeach
                    </table>
                    <a href="/funds"><button class="btn btn-primary">All Funds</button></a>
                    @if ($user->roles->has(1))
                        <a href="/funds/create"><button class="btn btn-primary">Create Fund</button></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection