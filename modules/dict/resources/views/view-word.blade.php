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
            <textarea class="no-resize form-control" name="sentence" placeholder="{{ trans('dict::view.your_sentence') }}"></textarea>
            {!! errorField('sentence') !!}
        </div>
        <div class="text-center form-group">
            <input type="hidden" name="word_id" id="input_word_id" value="{{ $word ? $word->id : null }}">
            <button type="submit" class="btn btn-primary btn-lg">{{ trans('dict::view.make_sentence') }}</button>
        </div>
        <p class="text-center">
            <a id="check_sentence_link" target="_blank" data-href="http://sentence.yourdictionary.com" href="http://sentence.yourdictionary.com/{{ $word->word }}?direct_search_result=yes"><i>{{ trans('dict::view.check_your_sentence') }}</i></a>
        </p>
        
        {!! Form::close() !!}
    </div>
    @else
    <p class="text-center">
        <a href="{{ route('front::account.login') }}">{{ trans('dict::view.login_to_make_sentence') }}</a>
    </p>
    @endif

    <h3 class="page-title center-title mgb-20"><span>{{ trans('dict::view.list_sentences') }}</span></h3>    
    <div class="mgb-30">
        @include('dict::includes.list-sentences')
    </div>
    
    @include('dict::includes.form-subscribe')
    
</div>

@stop

@section('sidebar_col', '')

@section('foot')
<script src="/js/bootbox.min.js"></script>
@stop



