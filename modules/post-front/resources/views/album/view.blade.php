@extends('front::layouts.default')

@section('title', $album->name)

@section('keywords', $album->meta_keyword)
@section('description', $album->meta_desc)

@section('head')
<link rel="stylesheet" href="/plugins/fancybox3/dist/jquery.fancybox.min.css">
@stop

@section('content_col')

    <h2 class="page-title bd-title mgb-30">
        <span class="text-uppercase">{{ $album->name }}</span>
    </h2>

    @if (!$collectMedias->isEmpty())
    <div class="posts row">
        @foreach ($collectMedias as $media)
        <div class="post col-sm-6 post-col">
            <div class="thumb text-center">
                <a href="{{ $media->getSrc('full') }}" title="{{ $media->name }}" class="gallery-fancybox" data-fancybox="album-{{ $album->id }}">
                    <img class="img-fluid" src="{{ $media->getSrc('medium') }}" alt="{{ $media->name }}">
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <?php
    $mediaPaginate = $collectMedias->links();
    ?>
    @if ($mediaPaginate)
    <div class="paginate-box">
        {!! $mediaPaginate !!}
    </div>
    @endif

@stop

@section('foot')
<script src="/plugins/fancybox3/dist/jquery.fancybox.min.js"></script>
<script>
    (function ($) {
        $('.gallery-fancybox').fancybox({
            animationEffect : false,
        });
    })(jQuery);
</script>
@stop

