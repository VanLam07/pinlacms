@extends('front::layouts.default')

@section('title', trans('front::view.login'))

@section('content_full')

<div class="row mgt-50">
    <div class="col-md-6 mr-auto ml-auto">
        
        <div class="box-wrapper">
            <h2 class="page-title bd-pattern"><span>{{ trans('front::view.login') }}</span></h2>

            {!! showMessage() !!}

            <form method="post" action="{{ route('front::account.post_login') }}">
                {!! csrf_field() !!}
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

                <div class="form-group row">
                    <div class="col-sm-6">
                        <label>
                            <input type="checkbox" name="remember"
                                   {{ old('remember') ? 'checked' : '' }}> {{ trans('front::view.remember_login') }}
                        </label>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('front::account.get_forget_pass') }}">{{ trans('admin::view.forget_password') }}?</a>
                    </div>
                </div>
                
                <div class="form-group">
                    <div>
                        {{ trans('front::message.dont_have_account') }} 
                        <a href="{{ route('front::account.register') }}">{{ trans('front::view.register') }} <i class="fa fa-long-arrow-right"></i></a>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="main-btn lg-btn">{{ trans('front::view.login') }}</button>
                </div>
            </form>
        </div>
        
    </div>
</div>

@stop

@section('sidebar_col', '')

