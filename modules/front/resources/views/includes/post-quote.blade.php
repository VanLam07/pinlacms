<?php 
$imageSrc = $post->getImageSrc('medium'); 
$postLink = $post->getLink();
?>

<div class="post post-quote">
    <div class="thumb" style="background: {{ $bgColor }} url({{ $imageSrc }});">
        <div class="post-excerpt">
            {!! $post->getExcerpt(25) !!}
        </div>
    </div>
    <div class="post-content">
        <h3 class="post-title"><a href="{{ $postLink }}" title="{{ $post->title }}">{{ $post->title }}</a></h3>
    </div>
    <div class="post-foot">
        <div class="row">
            <div class="col-sm-7">
            @include('front::meta.social-share')
            </div>
            <div class="col-sm-5 text-right">
                <span><i class="fa fa-eye"></i> {{ $post->views }}</span>
            </div>
        </div>
    </div>
</div>


