@extends('front::layouts.default')

@section('title', $post->title)

@section('content_col')

<div class="post-container">

    <h2 class="page-title">{{ $post->title }}</h2>

    <div class="page-head">
        <div class="row">
            <div class="col-6">
                @include('front::meta.social-share')
            </div>
            <div class="col-6 post-meta text-right">
                <span class="date"><i class="fa fa-calendar"></i> {{ $post->created_at->format('d-m-Y') }}</span>
                <span class="author"><i class="fa fa-user"></i> {{ $post->authorName() }}</span>
                <span class="view"><i class="fa fa-eye"></i> {{ (int) $post->views }}</span>
            </div>
        </div>
    </div>

    <div class="post-content">
        {!! $post->content !!}
    </div>
    
    <div class="page-foot">
        <div class="post-tax post-cats">
            @include('front::meta.categories', ['categories' => $post->getCats])
        </div>
        <div class="post-taxs post-tags">
            @include('front::meta.tags', ['tags' => $post->getTags])
        </div>
    </div>
    
</div>

<?php 
$relatedPosts = $post->getRelated();
?>
@if (!$relatedPosts->isEmpty())
<div class="box related-box">
    <h3 class="sub-title bd-title">{{ trans('front::view.view_more') }}</h3>
    <ul class="posts related-posts">
        @foreach ($relatedPosts as $post)
        <li>
            <a href="{{ $post->getLink() }}">{{ $post->title }}</a>
        </li>
        @endforeach
    </ul>
</div>
@endif

@include('front::includes.comments')

@stop

@section('foot')

@stop