@extends('admin::layouts.auth')

@section('title', trans('admin::view.register'))

@section('content')

<div class="row">
    
    <div class="col-sm-6 col-sm-offset-3">
        <h1 class="page-header">@lang('admin::view.register')</h1>
        <br />
        
        {!! showMessage() !!}
        
        {!! Form::open(['method' => 'post', 'route' => 'admin::auth.post_register']) !!}
        
        <div class="form-group">
            <label>{{ trans('admin::view.name') }}</label>
            <input type="text" name="name" value="{{old('name')}}" class="form-control" placeholder="{{ trans('admin::view.name') }}">
            {!! errorField('name') !!}
        </div>
        
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
            <label>{{ trans('admin::view.repassword') }}</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="{{ trans('admin::view.repassword') }}">
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-block btn-default">{{ trans('admin::view.register') }}</button>
        </div>
        
        <div><p>{{ trans('admin::view.has_account') }} <a href="{{ route('admin::auth.get_login') }}">{{ trans('admin::view.login') }}</a></p></div>
        
        {!! Form::close() !!}
    </div>
    
</div>

@stop
