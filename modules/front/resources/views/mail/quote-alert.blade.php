@extends('front::layouts.mail')

@section('head')
<style>
    #mail_content h1 {
        line-height: 30px!important;
    }
</style>
@stop

@section('content')

<p>Xin chào <strong>{{ $dearName }}</strong>,</p>
<p>Câu nói của ngày hôm nay:</p>

<p>&nbsp;</p>

<div style="margin-bottom: 20px; line-height: 22px;" id="mail_content">
    {!! $content !!}
</div>

<p>Chúc bạn ngày mới vui vẻ!</p>

<div style="margin-bottom: 20px; line-height: 22px;">
    <a href="{{ $detailLink }}">Xem chi tiết</a>
</div>

<p>&nbsp;</p>

@stop

