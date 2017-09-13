@extends('admin::layouts.auth')

@section('title', trans('admin::view.forget_password'))

@section('content')
<div class="row">
    <div class="col-sm-6 col-sm-offset-3">
        <h1 class="page-header">{{ trans('admin::view.forget_password') }}</h1>
        <br />
        
        {!! showMessage() !!}
        
        @if(isset($token) && $token)
        
            {!! Form::open(['method' => 'post', 'route' => 'admin::auth.post_reset_pass']) !!}

            <div class="form-group">
                <label>{{ trans('admin::view.new_password') }}</label>
                <input type="password" name="password" value="{{ old('password') }}" class="form-control">
                {!! errorField('password') !!}
            </div>
            <div class="form-group">
                <label>{{ trans('admin::view.repassword') }}</label>
                <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control">
            </div>

            <div class="form-group">
                <input type="hidden" name="token" value="{{$token}}">
                {!! errorField('token') !!}
                <button type="submit" class="btn btn-block btn-default">{{ trans('admin::view.submit') }}</button>
            </div>

            {!! Form::close() !!}

        @else

            <p class="alert alert-danger">{{ trans('admin::message.invalid_token' )}}</p>
        
        @endif
        
    </div>
</div>
@stop
