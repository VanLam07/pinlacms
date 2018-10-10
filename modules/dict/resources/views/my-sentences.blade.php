<?php
$pageTitle = trans('front::view.view_my_sentence_maked');
?>

@extends('front::layouts.default')

@section('title', $pageTitle)

@section('keywords', $pageTitle)
@section('description', $pageTitle . ' - ' . request()->getHost())

@section('content_col')

<div class="post-container">
    <h2 class="page-title center-title mgb-30"><span>{{ $pageTitle }}</span></h2>

    <div class="mgb-30">
        @if (!$collection->isEmpty())
        <div class="comment-body">
            <ul class="comment-lists">
            @foreach($collection as $sentence)
            <?php
            $canEditSentence = canDo('edit_sentence', $sentence->user_id);
            ?>
            <li class="comment-item" data-id="{{ $sentence->id }}">
                <div class="inner">
                    <h4 class="comment-author-name">
                        <a href="{{ route('dict::word.view_word', ['slug' => str_slug($sentence->word), 'id' => $sentence->word_id]) }}">{{ $sentence->word }}</a>
                        <span class="comment-date">{{ $sentence->created_at->format('H:i d-m-Y') }}</span>
                    </h4>
                    <div class="comment-item-content" data-id="{{ $sentence->id }}">
                        <div class="comment-item-show">{{ $sentence->sentence }}</div>
                        @if ($canEditSentence)
                        <div class="comment-item-edit"></div>
                        @endif
                    </div>
                </div>
            </li>
            @endforeach
            </ul>
        </div>
        @else
        <p class="text-center">{{ trans('dict::view.none_sentence') }}</p>
        @endif
    </div>
    
    <div class="paginate-box">
        {!! $collection->links() !!}
    </div>
</div>

@stop

@section('foot')
<script src="/js/bootbox.min.js"></script>
@stop





