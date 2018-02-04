@extends('front::layouts.default')

@section('title', trans('admin::view.forget_password'))

@section('content_full')

<h2 class="page-title bd-title">{{ trans('admin::view.forget_password') }}</h2>

<div class="row">
    <div class="col-sm-6 ml-auto mr-auto">
        <br />
        <br />
        
        {!! showMessage() !!}
        
        {!! Form::open(['method' => 'post', 'route' => 'front::account.post_forget_pass']) !!}
        
        <div class="form-group">
            <label>@lang('admin::view.enter_email')</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="example@example.com">
            {!! errorField('email') !!}
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-block btn-default">@lang('admin::view.submit')</button>
        </div>
        
        {!! Form::close() !!}
    </div>
</div>

@stop

@section('sidebar_col')

@stop
