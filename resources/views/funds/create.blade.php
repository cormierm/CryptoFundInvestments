@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">Create fund</div>

                    <div class="card-body">
                        <form method="post" action="/funds">
                            @csrf

                            <input type="hidden" name="user_id" id="user_id" value="{{ $user_id }}">

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Fund Name</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required>

                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label text-md-right">Fund Description</label>

                                <div class="col-md-6">
                                    <input id="description" type="textarea" style="height:8em" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" value="{{ old('description') }}" required>

                                    @if ($errors->has('description'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="risk_id" class="col-md-4 col-form-label text-md-right">Fund Risk</label>

                                <div class="col-md-6">
                                    <select name="risk_id" id="risk_id" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}">
                                        @foreach ($risks as $risk)
                                            <option value="{{ $risk->id }}">{{ $risk->name }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('risk_id'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('risk_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Create Fund
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
