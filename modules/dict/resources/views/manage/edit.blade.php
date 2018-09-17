@extends('admin::layouts.manage')

@section('title', trans('dict::view.man_dictionaries'))

@section('content')

{!! Form::open(['method' => 'put', 'route' => ['dict::admin.update', $item->id]]) !!}

{!! showMessage() !!}
  
@include('dict::manage.form')

<div class="form-group text-center">
    <a href="{{ route('dict::admin.index') }}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{trans('dict::view.back')}}</a>
    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('dict::view.update')}}</button>
</div>

{!! Form::close() !!}

@stop

@section('foot')

@include('admin::parts.tinymce-script')

@stop

