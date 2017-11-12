@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_langs'))

@section('content')

<?php
use Admin\Facades\AdConst;
?>

<div class="row">
    <div class="col-sm-6">
        
        {!! showMessage() !!}
        
        {!! Form::open(['method' => 'post', 'route' => 'admin::lang.store']) !!}
        
        <div class="form-group">
            <label>{{trans('admin::view.name')}} (*)</label>
            {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => trans('admin::view.name')]) !!}
            {!! errorField('name') !!}
        </div>
        
        <div class="form-group">
            <label>{{trans('admin::view.code')}} (*)</label>
            {!! Form::text('code', old('code'), ['class' => 'form-control', 'placeholder' => trans('admin::view.code')]) !!}
            {!! errorField('code') !!}
        </div>
        
        <div class="form-group">
            <label>{{trans('admin::view.icon')}} (*)</label>
            {!! Form::text('icon', old('icon'), ['class' => 'form-control', 'placeholder' => trans('admin::view.icon')]) !!}
            {!! errorField('icon') !!}
        </div>
        
        <div class="form-group">
            <label>{{trans('admin::view.folder')}} (*)</label>
            {!! Form::text('folder', old('folder'), ['class' => 'form-control', 'placeholder' => trans('admin::view.folder')]) !!}
            {!! errorField('folder') !!}
        </div>
        
        <div class="form-group">
            <label>{{trans('admin::view.default')}}</label>
            {!! Form::select('default', [0 => 'No', 1 => 'Yes'], old('default'), ['class' => 'form-control']) !!}
        </div>
        
        <div class="form-group">
            <label>{{trans('admin::view.status')}}</label>
            {!! Form::select('status', [1 => 'Active', 2 => 'Disable'], old('status'), ['class' => 'form-control']) !!}
        </div>
        
        <div class="form-group">
            <label>{{trans('admin::view.order')}}</label>
            {!! Form::number('order', old('order'), ['class' => 'form-control']) !!}
            {!! errorField('order') !!}
        </div>
        
        <div class="form-group">
            <label>{{trans('admin::view.unit')}} (*)</label>
            {!! Form::text('unit', old('unit'), ['class' => 'form-control', 'placeholder' => 'VNĐ']) !!}
            {!! errorField('unit') !!}
        </div>
        <div class="form-group">
            <label>{{trans('admin::view.ratio_currency')}} (*)</label>
            {!! Form::text('ratio_currency', old('ratio_currency'), ['class' => 'form-control', 'placeholder' => trans('admin::view.ratio_currency')]) !!}
            {!! errorField('ratio_currency') !!}
        </div>
        
        <a href="{{route('admin::lang.index', ['status' => AdConst::STT_PUBLISH])}}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{trans('admin::view.back')}}</a>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('admin::view.create')}}</button>
        
        {!! Form::close() !!}
    </div>
</div>

@stop

