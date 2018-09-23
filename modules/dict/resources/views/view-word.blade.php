<?php
use Admin\Facades\AdConst;
use Dict\Models\DictEnVn;

if (!isset($word)) {
    $word = DictEnVn::makeRandWord(true);
}
?>

@extends('front::layouts.default')

@section('title', $page->title)

@section('keywords', $page->meta_keyword)
@section('description', $page->meta_desc)

@section('content_full')

<div class="post-container">
    <h2 class="page-title center-title mgb-30"><span>{{ $page->title }}</span></h2>
    
    <div class="text-center">
        {!! showMessage() !!}
    </div>
    
    <div class="wrap">

        @if ($word)
        <div class="text-center">
            <div class="word-box">
                <div class="main-word">{{ $word->word }}</div>
                <div class="word-desc">
                    @if ($word->type)
                    <span class="type">{{ $word->type }}</span>
                    @endif
                    @if ($word->pronun)
                    <span class="pronun">{{ $word->pronun }}</span>
                    @endif
                    <a data-toggle="collapse" href="#mean_box" text-hide="{{ trans('dict::view.hide_mean') }}" text-show="{{ trans('dict::view.view_mean') }}">
                        {{ trans('dict::view.view_mean') }}
                    </a>
                </div>

                <div class="collapse mgt-20" id="mean_box">
                    <div class="card card-body">
                        {!! $word->mean !!}
                    </div>
                </div>
            </div>
        </div>
        @endif
        
    </div>

    @if (auth()->check())
    <div class="wrap">
        {!! Form::open([
            'method' => 'post',
            'route' => 'dict::word.make_sentence'
        ]) !!}
        
        <h3 class="sub-title text-center mgb-20">{{ trans('dict::view.make_a_sentence_with_this_word') }}</h3>
        <div class="form-group">
            <textarea class="no-resize form-control" name="sentence"></textarea>
            {!! errorField('sentence') !!}
        </div>
        <p class="text-center">
            <a id="check_sentence_link" target="_blank" data-href="http://sentence.yourdictionary.com" href="http://sentence.yourdictionary.com/{{ $word->word }}?direct_search_result=yes"><i>{{ trans('dict::view.check_your_sentence') }}</i></a>
        </p>
        <div class="text-center">
            <input type="hidden" name="word_id" id="input_word_id" value="{{ $word ? $word->id : null }}">
            <button type="submit" class="btn btn-primary btn-lg">{{ trans('dict::view.make_sentence') }}</button>
        </div>
        
        {!! Form::close() !!}
    </div>
    @else
    <p class="text-center">
        <a href="{{ route('front::account.login') }}">{{ trans('dict::view.login_to_make_sentence') }}</a>
    </p>
    @endif

    <h3 class="page-title center-title mgb-20"><span>{{ trans('dict::view.list_sentences') }}</span></h3>    
    <div class="mgb-30">

        @if (isset($sentences) && !$sentences->isEmpty())
        <div class="comment-body">
            <ul class="comment-lists">
            @foreach($sentences as $sentence)
            <li class="comment-item" data-id="44">
                <div class="inner media">
                    <div class="media-left comment-avatar mr-3">
                        {!! $sentence->author ? $sentence->author->getAvatar(42) : getDefaultAvatar(42) !!}
                    </div>
                    <div class="media-body">
                        <h4 class="comment-author-name">
                            {{ $sentence->user_name }}
                            <span class="comment-date">{{ $sentence->created_at->format('H:i d-m-Y') }}</span>
                            <div class="comment-actions">
                                @if (auth()->id() == $sentence->user_id)
    <!--                            <button type="button" class="edit-comment-btn btn btn-info btn-sm" title="{{ trans('front::view.edit') }}"
                                        data-url="{{ route('dict::word.edit', ['id' => $sentence->id]) }}">
                                    <i class="fa fa-edit"></i>
                                </button>-->
                                <button type="button" class="del-comment-btn btn btn-danger btn-sm" title="{{ trans('front::view.delete') }}"
                                        data-url="{{ route('dict::word.delete', ['id' => $sentence->id]) }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </h4>
                        <div class="comment-item-content">
                            <div class="comment-item-show">{{ $sentence->sentence }}</div>
                        </div>
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
    
    @include('dict::includes.form-subscribe')
    
</div>

@stop

@section('sidebar_col', '')

@section('foot')
<script src="/js/bootbox.min.js"></script>
@stop



