@extends('layouts.frontend')


@section('content_col3')

<h1>Excel import</h1>

{!! Form::open(['method' => 'post', 'url' => '/vi/test/excel', 'files' => true]) !!}

{!! Form::file('excel_file') !!}

<button type="submit">Submit</button>

{!! Form::close() !!}

@stop
