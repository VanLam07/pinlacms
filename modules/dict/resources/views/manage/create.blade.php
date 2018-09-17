@extends('admin::layouts.manage')

@section('title', trans('dict::view.man_dictionaries'))

@section('content')

{!! Form::open(['method' => 'post', 'route' => 'dict::admin.store']) !!}

{!! showMessage() !!}
  
@include('dict::manage.form')

<div class="form-group text-center">
    <a href="{{ route('dict::admin.index', ['status' => 1]) }}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{trans('dict::view.back')}}</a>
    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('dict::view.save')}}</button>
</div>

{!! Form::close() !!}

@stop

