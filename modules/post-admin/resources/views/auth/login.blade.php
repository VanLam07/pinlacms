@extends('admin::layouts.auth')

@section('title', trans('admin::view.login'))

@section('content')

<div class="row">
    
    <div class="col-sm-6 col-sm-offset-3">
        <h1 class="page-header">{{trans('admin::view.login')}}</h1>
        <br />
        
        {!! showMessage() !!}
        
        {!! Form::open(['method' => 'post', 'route' => 'admin::auth.post_login']) !!}
        
        <div class="form-group">
            <label>{{ trans('admin::view.email') }}</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="example@example.com">
            {!! errorField('email') !!}
        </div>
        
        <div class="form-group">
            <label>{{ trans('admin::view.password') }}</label>
            <input type="password" name="password" class="form-control" placeholder="{{ trans('admin::view.password') }}">
            {!! errorField('password') !!}
        </div>
        
        <div class="form-group">
            <label><input type="checkbox" name="remember"> {{ trans('admin::view.remember') }}</label>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-block btn-default">{{ trans('admin::view.login') }}</button>
        </div>
        
        <div>
            <!--<p class="pull-left">{{ trans('admin::view.no_account') }} <a href="{{route('admin::auth.get_register')}}">{{ trans('admin::view.register') }}</a></p>-->
            <p class="pull-right"><a href="{{route('admin::auth.get_forget_pass')}}">{{ trans('admin::view.forget_password') }}</a></p>
        </div>
        <div class="clearfix"></div>
        
        {!! Form::close() !!}
    </div>
    
</div>

@stop
