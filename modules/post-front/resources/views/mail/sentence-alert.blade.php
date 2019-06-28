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
<p>Từ của ngày hôm nay:</p>

<p>&nbsp;</p>

<div style="margin-bottom: 20px; line-height: 22px;" id="mail_content">
    
    <div style="text-align: center; max-width: 400px; margin: 0 auto 20px auto; border: 1px solid #ddd; padding: 30px 20px;">
        <span style="font-size: 40px; font-weight: 700;">{{ $randWord->word }}</span><br />
        <span style="font-size: 20px;">{{ $randWord->type ? '(' . $randWord->type . ')' : null }}</span>
    </div>
    
</div>

<p style="text-align: center;">Vào <a href="{{ $randWord->link }}">đường dẫn này</a> để đặt câu nhé</p>

<div style="margin-bottom: 20px; line-height: 22px; text-align: center;">
    Muốn xem thêm từ khác thì <a href="{{ $detailLink }}">vào đây</a> nhé
</div>

<p>&nbsp;</p>

@stop

