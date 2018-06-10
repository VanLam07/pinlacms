<?php 
$thumbnail = $post->getThumbnail('medium'); 
$postLink = $post->getLink();
?>

<div class="thumb col-6 col-sm-5">
    <a href="{{ $postLink }}" title="{{ $post->title }}">
        {!! $thumbnail !!}
    </a>
</div>

<div class="post-content col col-sm-7">
    <h3 class="post-title"><a href="{{ $postLink }}" title="{{ $post->title }}">{{ $post->title }}</a></h3>
    
    <div class="post-meta">
        <span class="date"><i class="fa fa-calendar"></i> {{ $post->created_at->format('d-m-Y') }}</span>
        <span class="author"><i class="fa fa-user"></i> {{ $post->authorName() }}</span>
        <span class="view"><i class="fa fa-eye"></i> {{ (int) $post->views }}</span>
    </div>
    
    <div class="post-excerpt">
        {!! $post->getExcerpt(25) !!}
    </div> 
    <a href="{{ $postLink }}" title="{{ $post->title }}" class="read-more-btn">{{ trans('front::view.read_more') }}</a>
</div>

