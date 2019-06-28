@extends('front::layouts.mail')

@section('content')

<h2>Liên hệ mới</h2>

<table>
    <tr>
        <th style="text-align: left;">Họ tên: </th> <td>{{ $fullname }}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Email: </th> <td>{{ $email }}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Chủ đề: </th> <td>{{ $subject }}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Nội dung: </th> <td>{{ $content }}</td>
    </tr>
</table>

@stop

