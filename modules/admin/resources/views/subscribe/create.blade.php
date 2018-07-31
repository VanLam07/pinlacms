@extends('admin::layouts.manage')

@section('title', trans('admin::view.subscribe'))

<?php
use Admin\Facades\AdConst;

$listTypes = AdConst::listPostFormats();
?>

@section('content')

<div class="row">
    <div class="col-sm-6">
        
        {!! showMessage() !!}
        
        {!! Form::open(['method' => 'post', 'route' => 'admin::subs.store']) !!}
        
        <div class="form-group">
            <label>{{ trans('admin::view.fullname') }} (*)</label>
            {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => trans('admin::view.name')]) !!}
            {!! errorField('name') !!}
        </div>
        
        <div class="form-group">
            <label>{{ trans('admin::view.email') }} (*)</label>
            {!! Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => trans('admin::view.email')]) !!}
            {!! errorField('label') !!}
        </div>
        
        <div class="form-group">
            <label>{{ trans('admin::view.time_receive') }}</label>
            {!! Form::text('time', old('time'), ['class' => 'form-control time_picker', 'placeholder' => '08:00']) !!}
        </div>
        
        <div class="form-group">
            <label>{{ trans('admin::view.type_subscribe') }}</label>
            {!! Form::select('type', $listTypes, old('type'), ['class' => 'form-control']) !!}
        </div>
        
        <div class="form-group">
            <label>{{ trans('admin::view.status') }}</label>
            {!! Form::select('status', [1 => 'Enable', 2 => 'Disable'], old('status'), ['class' => 'form-control']) !!}
        </div>
        
        <a href="{{ route('admin::subs.index') }}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{ trans('admin::view.back') }}</a>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('admin::view.create') }}</button>
        
        {!! Form::close() !!}
    </div>
</div>

@stop

