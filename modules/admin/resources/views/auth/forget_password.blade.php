@extends('admin::layouts.auth')

@section('title', trans('admin::view.forget_password'))

@section('content')
<div class="row">
    <div class="col-sm-6 col-sm-offset-3">
        <br />
        <br />
        <h1 class="page-header">{{trans('admin::view.forget_password')}}</h1>
        <br />
        
        {!! showMessage() !!}
        
        {!! Form::open(['method' => 'post', 'route' => 'admin::auth.post_forget_pass']) !!}
        
        <div class="form-group">
            <label>@lang('admin::view.enter_email')</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="example@example.com">
            {!! errorField('email') !!}
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-block btn-default">@lang('admin::view.submit')</button>
        </div>
        <p class="text-right">
            <a href="{{ route('admin::auth.get_login') }}"><i class="fa fa-long-arrow-left"></i> @lang('admin::view.login')</a>
        </p>
        
        {!! Form::close() !!}
    </div>
</div>
@stop
