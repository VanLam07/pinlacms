@extends('front::layouts.default')

@section('title', trans('front::view.register'))

@section('content_full')

<div class="row mgt-50">
    <div class="col-6 mr-auto ml-auto">
        
        <div class="box-wrapper">
            <h2 class="page-title bd-pattern"><span>{{ trans('front::view.register') }}</span></h2>

            {!! showMessage() !!}

            <form method="post" action="{{ route('front::account.post_register') }}">
                {!! csrf_field() !!}
                <div class="form-group">
                    <label>{{ trans('front::view.fullname') }}</label>
                    <input type="text" name="name" class="form-control" placeholder="{{ trans('front::view.fullname') }}"
                           value="{{ old('name') }}">
                    {!! errorField('name') !!}
                </div>

                <div class="form-group">
                    <label>{{ trans('front::view.email') }}</label>
                    <input type="text" name="email" class="form-control" placeholder="{{ trans('front::view.email') }}"
                           value="{{ old('email') }}">
                    {!! errorField('email') !!}
                </div>

                <div class="form-group">
                    <label>{{ trans('front::view.password') }}</label>
                    <input type="password" name="password" class="form-control" placeholder="{{ trans('front::view.password') }}"
                           value="{{ old('password') }}">
                    {!! errorField('password') !!}
                </div>

                <div class="form-group">
                    <label>{{ trans('front::view.re_password') }}</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="{{ trans('front::view.password') }}"
                           value="{{ old('password_confirmation') }}">
                    {!! errorField('password_confirmation') !!}
                </div>
                
                <div class="form-group">
                    {{ trans('front::message.already_have_account') }} 
                    <a href="{{ route('front::account.login') }}">{{ trans('front::view.login') }} <i class="fa fa-long-arrow-right"></i></a>
                </div>

                <div class="text-center">
                    <button type="submit" class="main-btn lg-btn">{{ trans('front::view.register') }}</button>
                </div>
            </form>
        </div>
        
    </div>
</div>

@stop

@section('sidebar_col', '')

