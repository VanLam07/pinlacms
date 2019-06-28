@extends('front::layouts.mail')

@section('content')

<h2>{{ $postTitle }}</h2>

<div style="margin-bottom: 20px; line-height: 22px;">
    {!! $postContent !!}
</div>

<div style="margin-bottom: 20px; line-height: 22px;">
    <a href="{{ $postLink }}">Xem chi tiết</a>
</div>

@stop

