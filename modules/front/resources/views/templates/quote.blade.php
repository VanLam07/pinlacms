<?php
use App\Models\Subscribe;
use Admin\Facades\AdConst;

$subs = Subscribe::where('ip', request()->ip())->orderBy('created_at', 'desc')->first();
?>

@extends('front::layouts.default')

@section('title', $page->title)

@section('keywords', $page->meta_keyword)
@section('description', $page->meta_desc)

@section('head')
<link rel="stylesheet" href="/plugins/datetimepicker/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" href="/plugins/prism/prism.css">
@stop

@section('content_full')

<div class="post-container">
    <div class="wrap pdt-30">
    
    <h2 class="page-title center-title"><span>{{ $page->title }}</span></h2>
    
    <div class="mgb-30"></div>
    
    <div class="post-content">
        {!! $page->content !!}
    </div>
    
    {!! Form::open([
        'method' => 'post',
        'route' => 'front::quote.register',
    ]) !!}
    
    <div class="row">
        <div class="col-sm-6 mr-auto ml-auto">
            
            {!! showMessage() !!}
        
            <div class="form-group">
                <label>{{ trans('front::view.email') }}</label>
                <input type="email" name="email" class="form-control" placeholder="example@mail.com"
                       value="{{ old('email') ? old('email') : ($subs ? $subs->email : null) }}">
                {!! errorField('email') !!}
            </div>

            <div class="form-group">
                <label>{{ trans('front::view.full_name') }}</label>
                <input type="text" name="name" class="form-control" placeholder="{{ trans('front::view.full_name') }}"
                       value="{{ old('name') ? old('name') : ($subs ? $subs->name : null) }}">
                {!! errorField('name') !!}
            </div>

            <div class="form-group mgb-30">
                <label>{{ trans('front::view.time_receive') }}</label>
                <input type="text" name="time" class="form-control time_picker" placeholder="08:00"
                       value="{{ old('time') ? old('time') : ($subs ? $subs->time : null) }}">
            </div>

            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-send"></i> {{ trans('front::view.Register') }}</button>
            </div>

        </div>
    </div>
        
    {!! Form::close() !!}
    
    </div>
    
    <?php
    $posts = PlPost::getQuotes();
    ?>
    @if (!$posts->isEmpty())
    <div class="posts">
        <div class="row">
            @foreach ($posts as $post)
            <div class="col-md-4">
                @include('front::includes.post-quote', ['bgColor' => AdConst::randBgPost()])
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    <?php
    $postPaginate = $posts->links();
    ?>
    @if ($postPaginate)
    <div class="paginate-box">
        {!! $postPaginate !!}
    </div>
    @endif
</div>

@stop

@section('sidebar_col', '')

@section('foot')

<script src="/js/moment.min.js"></script>
<script src="/plugins/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script src="/plugins/prism/prism.js"></script>
<script >
    (function ($) {
        $('.time_picker').datetimepicker({
            format: 'HH:mm'
        });
    })(jQuery);
</script>

@stop
