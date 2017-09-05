@extends('layouts.frontend')

@section('keyword', $album->meta_keyword)
@section('description', $album->meta_description)

@section('title', $album->name)

@section('content')

<div class="container">
    <div class="wrapper">
        <div class="wrap_inner">
            <h2 class="single-title nice_clbd"><span>{{$album->name}}</span></h2>

            @if($images)
            <div class="row items grid_items gallery_items">
                @foreach($images as $image)
                <div class="item col-xs-6 col-md-3">
                    <div class="inner">
                        <div class="thumb">
                            <a rel="gallery" href="{{getImageSrc($image->thumb_url, 'full')}}" title="{{$image->name}}">
                                {!! $image->getImage('medium') !!}
                            </a>
                        </div>
                        <div class="item_body">
                            <h3 class="title"><a href="{{getImageSrc($image->thumb_url, 'full')}}">{{$image->name}}</a></h3>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
            
        </div>
    </div>
</div>

@stop


