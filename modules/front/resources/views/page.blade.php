@extends('front::layouts.default')

@section('title', $page->title)

@section('content_col')

<div class="post-container">
    
    <h2 class="page-title bd-title">{{ $page->title }}</h2>
    
    <div class="post-content">
        {!! $page->content !!}
    </div>
    
</div>

@stop

@section('sidebar_col')

@stop
