@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Edit Profile</div>

                    <div class="card-body">
                        <form method="post" action="/profile/edit">
                            @csrf

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $user->email }}" required>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="first_name" class="col-md-4 col-form-label text-md-right">First Name</label>

                                <div class="col-md-6">
                                    <input id="first_name" type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ $user->first_name }}" required autofocus>

                                    @if ($errors->has('first_name'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="last_name" class="col-md-4 col-form-label text-md-right">Last Name</label>

                                <div class="col-md-6">
                                    <input id="last_name" type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ $user->last_name }}" required autofocus>

                                    @if ($errors->has('last_name'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="phone" class="col-md-4 col-form-label text-md-right">Phone</label>

                                <div class="col-md-6">
                                    <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ $user->phone }}" required autofocus>

                                    @if ($errors->has('phone'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            @if($user->isTrader())

                                <div class="form-group row">
                                    <label for="trader_title" class="col-md-4 col-form-label text-md-right">Trader Title</label>

                                    <div class="col-md-6">
                                        <input id="trader_title" type="text" class="form-control{{ $errors->has('trader_title') ? ' is-invalid' : '' }}" name="trader_title" value="{{ $user->trader_title }}" autofocus>

                                        @if ($errors->has('trader_title'))
                                            <span class="invalid-feedback">
                                            <strong>{{ $errors->first('trader_title') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="trader_description" class="col-md-4 col-form-label text-md-right">Trader Description</label>

                                    <div class="col-md-6">
                                        <input id="trader_description" type="text" class="form-control{{ $errors->has('trader_description') ? ' is-invalid' : '' }}" name="trader_description" value="{{ $user->trader_description }}" autofocus>

                                        @if ($errors->has('trader_description'))
                                            <span class="invalid-feedback">
                                            <strong>{{ $errors->first('trader_description') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                            @endif

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Update Profile
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="card card-default">
                    <div class="card-header">Change Password</div>

                    <div class="card-body">
                        @if (session('errorPassword'))
                            <div class="alert alert-danger">
                                {{ session('errorPassword') }}
                            </div>
                        @endif
                        @if (session('successPassword'))
                            <div class="alert alert-success">
                                {{ session('successPassword') }}
                            </div>
                        @endif
                        <form method="post" action="/profile/changePassword">
                            @csrf

                            <div class="form-group row">
                                <label for="currentPassword" class="col-md-4 col-form-label text-md-right">Current Password</label>

                                <div class="col-md-6">
                                    <input id="currentPassword" type="password" class="form-control{{ $errors->has('currentPassword') ? ' is-invalid' : '' }}" name="currentPassword">

                                    @if ($errors->has('currentPassword'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('currentPassword') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                                </div>
                            </div>



                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-danger">
                                        Change Password
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>
@endsection
