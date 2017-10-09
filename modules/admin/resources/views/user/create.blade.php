<?php
use Admin\Facades\AdConst;
?>

@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_users'))

@section('content')

<div class="row">
    <div class="col-sm-6">
        
        {!! showMessage() !!}
        
        {!! Form::open(['method' => 'post', 'route' => 'admin::user.store']) !!}
        
        <div class="form-group">
            <label>{{trans('admin::view.name')}} (*)</label>
            {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => trans('admin::view.name')]) !!}
            {!! errorField('name') !!}
        </div>
        
        <div class="form-group">
            <label>{{trans('admin::view.email')}} (*)</label>
            {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => trans('admin::view.email')]) !!}
            {!! errorField('email') !!}
        </div>
        
        <div class="form-group">
            <label>{{trans('admin::view.password')}} (*)</label>
            {!! Form::password('password', ['class' => 'form-control']) !!}
            {!! errorField('password') !!}
        </div>
        
        <div class="form-group">
            <label>{{trans('admin::view.repassword')}} (*)</label>
            {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
        </div>
        
        <div class="form-group">
            <label>{{trans('admin::view.role')}}</label>
            {!! Form::select('role_ids[]', $roles, old('role_ids'), ['class' => 'form-control', 'multiple']) !!}
        </div>
        
        <div class="form-group">
            <label>{{trans('admin::view.status')}}</label>
            {!! Form::select('status', AdView::getStatusLabel(), old('status'), ['class' => 'form-control']) !!}
        </div>
        
        <div class="">
            <a href="{{route('admin::user.index', ['status' => AdConst::STT_PUBLISH])}}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{trans('admin::view.back')}}</a>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('admin::view.create')}}</button>
        </div>
        
        {!! Form::close() !!}
    </div>
</div>

@stop

