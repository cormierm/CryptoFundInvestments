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
        </div>
    </div>
</div>
@endsection
