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
        
        <div class="text-center">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fa fa-send"></i> {{ trans('front::view.generate_random_word') }} 
                <i class="fa fa-spin fa-refresh loading hidden"></i>
            </button>
        </div>
        
        {!! Form::close() !!}
        
    </div>
    
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
        <p class="text-center"><a target="_blank" href="http://sentence.yourdictionary.com"><i>{{ trans('dict::view.check_your_sentence') }}</i></a></p>
        <div class="text-center">
            <input type="hidden" name="word_id" id="input_word_id" value="{{ $word ? $word->id : null }}">
            <button type="submit" class="btn btn-primary btn-lg">{{ trans('dict::view.make_sentence') }}</button>
        </div>
        
        {!! Form::close() !!}
    </div>
    
    <h3 class="page-title center-title mgb-20"><span>{{ trans('dict::view.list_sentences') }}</span></h3>
    
    @if (isset($sentences) && !$sentences->isEmpty())
    <div class="comment-body mgb-30">
        <ul class="comment-lists">
        @foreach($sentences as $sentence)
        <li class="comment-item" data-id="44">
            <div class="inner media">
                <div class="media-left comment-avatar mr-3">
                    {!! $sentence->author ? $sentence->author->getAvatar(42) : getDefaultAvatar(42) !!}
                </div>
                <div class="media-body">
                    <h4 class="comment-author-name">{{ $sentence->user_name }}<span class="comment-date">{{ $sentence->created_at->format('H:i d-m-Y') }}</span></h4>
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
    <p class="text-center mgb-30">{{ trans('dict::view.none_sentence') }}</p>
    @endif
    
    <div class="wrap pdt-30">
        <h3 class="text-center sub-title">{{ trans('front::view.register_reminder_make_sentence_everyday') }}</h3>

        <div class="mgb-30"></div>

        <div class="post-content">
            {!! $page->content !!}
        </div>

        {!! Form::open([
            'method' => 'post',
            'route' => 'front::quote.register',
        ]) !!}

        <div class="row">
            <div class="col-sm-6 mr-auto ml-auto">

                <div class="form-group">
                    <label>{{ trans('front::view.email') }} (*)</label>
                    <input type="email" name="email" class="form-control" placeholder="example@mail.com"
                           value="{{ old('email') ? old('email') : null }}">
                    {!! errorField('email') !!}
                </div>

                <div class="form-group">
                    <label>{{ trans('front::view.full_name') }} (*)</label>
                    <input type="text" name="name" class="form-control" placeholder="{{ trans('front::view.full_name') }}"
                           value="{{ old('name') ? old('name') : null }}">
                    {!! errorField('name') !!}
                </div>

                <div class="form-group mgb-30">
                    <label>{{ trans('front::view.time_receive') }}</label>
                    <input type="text" name="time" class="form-control time_picker" placeholder="08:00"
                           value="{{ old('time') ? old('time') : null }}">
                    <span class="text-desc">{{ trans('front::view.you_can_fill_multi_time') }}</span>
                </div>

                <div class="form-group text-center">
                    <input type="hidden" name="type" value="{{ AdConst::FORMAT_DICT }}">
                    <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-send"></i> {{ trans('front::view.Register') }}</button>
                </div>

            </div>
        </div>

        {!! Form::close() !!}
    
    </div>
    
</div>

@stop

@section('sidebar_col', '')

@section('foot')
<script src="/js/bootbox.min.js"></script>
@stop



