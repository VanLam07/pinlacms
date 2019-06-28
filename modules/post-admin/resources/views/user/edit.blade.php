<?php
use Admin\Facades\AdConst;
?>

@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_users'))

@section('content')

<div class="row">
    <div class="col-sm-6">
        
        {!! showMessage() !!}
        
        {!! Form::open(['method' => 'put', 'route' => ['admin::user.update', $item->id]]) !!}
        
        <div class="form-group">
            <label>{{ trans('admin::view.name') }}</label>
            {!! Form::text('name', $item->name, ['class' => 'form-control', 'placeholder' => trans('admin::view.name')]) !!}
            {!! errorField('name') !!}
        </div>
        
        <div class="form-group">
            <label>{{ trans('admin::view.email') }} (*)</label>
            {!! Form::email('email', $item->email, ['class' => 'form-control', 'placeholder' => trans('admin::view.email')]) !!}
            {!! errorField('email') !!}
        </div>
        
<!--        <div class="form-group">
            <label>{{ trans('admin::view.password') }}</label>
            {!! Form::password('password', ['class' => 'form-control']) !!}
            {!! errorField('password') !!}
        </div>-->
        
        <div class="form-group">
            <label>{{ trans('admin::view.role') }}</label>
            @if (canDo('edit_user', null, AdConst::CAP_OTHER))
                {!! Form::select('role_ids[]', $roles, $item->roles->pluck('id')->toArray(), ['class' => 'form-control', 'multiple']) !!}
            @else
            <div>
                {{ implode(', ', $item->roles->pluck('name')->toArray()) }}
            </div>
            @endif
        </div>
        
        <div class="form-group">
            <label>{{ trans('admin::view.status') }}</label>
            {!! Form::select('status', AdView::getStatusLabel(), $item->status, ['class' => 'form-control']) !!}
        </div>
        
        <a href="{{ route('admin::user.index', ['status' => 1]) }}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{trans('admin::view.back')}}</a>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('admin::view.update')}}</button>
        
        {!! Form::close() !!}
        
    </div>
</div>

@stop

