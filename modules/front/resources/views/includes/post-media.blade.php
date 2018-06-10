<?php
$postLink = $post->getLink();
?>

<div class="media">
    <div class="media-left mr-3 thumb">
        <a href="{{ $postLink }}" title="{{ $post->title }}">
            {!! $post->getThumbnail('thumbnail') !!}
        </a>
    </div>
    
    <div class="post-content media-body">
        <h3 class="post-title">
            <a href="{{ $postLink }}" title="{{ $post->title }}">{{ $post->title }}</a>
        </h3>
        <div class="post-meta">
            <span class="date"><i class="fa fa-calendar"></i> {{ $post->created_at->format('d-m-Y') }}</span>
            @if (isset($hasView))
            <span class="view"><i class="fa fa-eye"></i> {{ $post->views }}</span>
            @endif
        </div>
    </div>
</div>

