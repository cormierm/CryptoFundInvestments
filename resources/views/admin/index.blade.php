@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Pending Trader Role Requests</div>
                    <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>User</th>
                            <th>Submitted on</th>
                            <th></th>
                        </tr>
                        @foreach ($pendingTraders as $pt)
                            <tr>
                                <td><a href="/user/{{ $pt->user->id }}">{{ $pt->user->email }}</a></td>
                                <td>{{ $pt->created_at }}</td>
                                <td>
                                    <form method="post" action="/admin/approveTraderRequest">
                                        @csrf
                                        <input type="hidden" name="trader_request_id" id="trader_request_id" value="{{ $pt->id }}"/>
                                        <input type="submit" value="Approve" class="btn btn-success"/>
                                    </form>
                                    <form method="post" action="/admin/cancelTraderRequest">
                                        @csrf
                                        <input type="hidden" name="trader_request_id" id="trader_request_id" value="{{ $pt->id }}"/>
                                        <input type="submit" value="Cancel" class="btn btn-danger"/>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection