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
        
        {!! Form::open(['method' => 'put', 'route' => ['admin::subs.update', $item->id]]) !!}
        
        <div class="form-group">
            <label>{{ trans('admin::view.fullname') }} (*)</label>
            {!! Form::text('name', old('name') ? old('name') : $item->name, ['class' => 'form-control', 'placeholder' => trans('admin::view.name')]) !!}
            {!! errorField('name') !!}
        </div>
        
        <div class="form-group">
            <label>{{ trans('admin::view.email') }} (*)</label>
            {!! Form::text('email', old('email') ? old('email') : $item->email, ['class' => 'form-control', 'placeholder' => trans('admin::view.email')]) !!}
            {!! errorField('label') !!}
        </div>
        
        <div class="form-group">
            <label>{{ trans('admin::view.time_receive') }}</label>
            {!! Form::text('time', old('time') ? old('time') : $item->time, ['class' => 'form-control time_picker', 'placeholder' => '08:00']) !!}
        </div>
        
        <div class="form-group">
            <label>{{ trans('admin::view.type_subscribe') }}</label>
            {!! Form::select('type', $listTypes, old('type') ? old('type') : $item->type, ['class' => 'form-control']) !!}
        </div>
        
        <a href="{{ route('admin::subs.index') }}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{ trans('admin::view.back') }}</a>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('admin::view.save') }}</button>
        
        {!! Form::close() !!}
    </div>
</div>

@stop
