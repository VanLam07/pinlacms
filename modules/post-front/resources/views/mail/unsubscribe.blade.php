@extends('front::layouts.default')

@section('title', trans('front::view.unsubscribe'))

@section('content_full')

<div class="post-container">

    <h2 class="page-title bd-title">{{ trans('front::view.unsubscribe') }}</h2>

    <div class="mgb-30"></div>
    
    @if ($errorMess = Session::get('error_mess'))
    
        <div class="alert alert-danger border_box">
            <div class="succ_mess">{{ $errorMess }}</div>
        </div>
    
    @elseif ($succMess = Session::get('succ_mess'))
    
        <div class="alert alert-success border_box">
            <div class="succ_mess">{{ $succMess }}</div>
        </div>
    
    @else

        @if ($subs)

        <p class="text-center">{{ trans('front::message.confirm_unsubscribe') }}</p>

        {!! Form::open([
            'method' => 'post',
            'route' => 'front::unsubs'
        ]) !!}

        <div class="form-group text-center">
            <input type="hidden" name="code" value="{{ $subs->code }}">
            <input type="hidden" name="type" value="{{ $subs->type }}">
            <button class="btn btn-success" type="submit">{{ trans('front::view.unsubscribe') }}</button>
        </div>

        {!! Form::close() !!}

        @else

        <div class="alert alert-danger border_box">
            <div class="succ_mess">{{ trans('front::message.not_found_unsubscribe') }}</div>
        </div>

        @endif
    
    @endif

</div>

@stop

@section('sidebar_col', '')
