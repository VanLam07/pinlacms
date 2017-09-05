@extends('layouts.frontend')

@section('keyword', $page->meta_keyword)
@section('description', $page->meta_desc)

@section('title', trans('front.albums'))

@section('content_col3')

<?php
$albums = Tax::query('album');
?>

<div class="_wrapper albums_box">
    <div class="container">
        <h2 class="page-header nice_clbd"><span>{{$page->title}}</span></h2>
        <div class="wrap_inner">
            @if(!$albums->isEmpty())
            <div class="_items">
                @foreach($albums as $al)
                <div class="_item">
                    <div class="_inner">
                        <div class="thumb">
                            <a href="{{route('album.view', ['id' => $al->id, 'slug' => $al->slug])}}">
                                {!! $al->getThumbnail('medium') !!}
                            </a>
                        </div>
                        <div class="item_body">
                            <h3 class="title"><a href="{{route('album.view', ['id' => $al->id, 'slug' => $al->slug])}}">{{$al->name}}</a></h3>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p>{{trans('front.no_items')}}</p>
            @endif
        </div>
    </div>
</div>

@stop


