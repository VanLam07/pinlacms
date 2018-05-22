@extends('front::layouts.default')

@section('title', trans('front::view.blog_title'))

@section('content_col')

<?php
$latestPosts = PlPost::getLatest(10);
?>
@if (!$latestPosts->isEmpty())

<div class="box box-latest">
    <h2 class="box-title bd-pattern"><span>{{ trans('front::view.posts') }}</span></h2>

    <div class="posts latest-posts">
        @foreach ($latestPosts as $post)
        <div class="post post-row">
            <div class="row">
                @include('front::includes.post-row')
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="paginate-box">
        {!! $latestPosts->links() !!}
    </div>
</div>

@endif

@stop

