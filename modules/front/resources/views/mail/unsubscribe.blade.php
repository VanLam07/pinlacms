@extends('front::layouts.default')

@section('title', trans('front::view.unsubscribe'))

@section('content_full')

<div class="post-container">

    <h2 class="page-title bd-title">{{ trans('front::view.unsubscribe') }}</h2>
    
    <div class="mgb-30"></div>
    <div class="alert alert-success border_box">
        <div class="succ_mess">{{ trans('front::message.unsubscribe_successful') }}</div>
    </div>

</div>

@stop

@section('sidebar_col', '')
