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
        
        {!! Form::open([
            'method' => 'post',
            'route' => 'dict::word.make_word',
            'id' => 'form_make_rand_word'
        ]) !!}
        
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
        
        <div class="text-center form-group">
            <button type="submit" class="btn btn-success btn-lg">
                {{ trans('front::view.generate_random_word') }} 
                <i class="fa fa-spin fa-refresh loading hidden"></i>
            </button>
        </div>
        
        {!! Form::close() !!}
        
    </div>
    
    <div class="text-center mgb-30">
        <a href="{{ route('dict::word.view_word', ['id' => $word->id, 'slug' => str_slug($word->word)]) }}" id="view_word_link"
           target="_blank" class="btn btn-info btn-lg">{{ trans('front::view.make_sentence_for_this_word') }}</a>
        <div class="mgb-15"></div>
        @if (auth()->check())
        <a href="{{ route('dict::word.my_sentences') }}">{{ trans('front::view.view_my_sentence_maked') }}</a>
        @endif
    </div>

    @include('dict::includes.form-subscribe')
    
</div>

@stop

@section('sidebar_col', '')

@section('foot')
<script src="/js/bootbox.min.js"></script>
@stop



