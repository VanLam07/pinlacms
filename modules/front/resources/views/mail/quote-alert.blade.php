@extends('front::layouts.mail')

@section('content')

<p>Xin chào <strong>{{ $dearName }}</strong>,</p>
<p>Câu nói của ngày hôm nay:</p>

<p>&nbsp;</p>

<div style="margin-bottom: 20px; line-height: 22px;">
    {!! $content !!}
</div>

<div style="margin-bottom: 20px; line-height: 22px;">
    <a href="{{ $detailLink }}">Xem chi tiết</a>
</div>

<p>Chúc bạn ngày mới vui vẻ!</p>

@stop

