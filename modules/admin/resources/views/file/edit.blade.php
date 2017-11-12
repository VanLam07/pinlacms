<?php
use Admin\Facades\AdConst;
?>

@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_files'))

@section('content')

{!! showMessage() !!}

{!! Form::open(['method' => 'put', 'route' => ['admin::file.update', $item->id]]) !!}

<div class="row">
    <div class="col-sm-6">

        <div class="form-group">
            <label>{{trans('admin::view.name')}}</label>
            {!! Form::text('title', $item->title, ['class' => 'form-control', 'placeholder' => trans('admin::view.name')]) !!}
            {!! errorField('name') !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.url')}} (*)</label>
            {!! Form::text('url', $item->url, ['class' => 'form-control', 'placeholder' => trans('admin::view.url')]) !!}
            {!! errorField('url') !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.type')}}</label>
            {!! Form::text('type', $item->type, ['class' => 'form-control', 'placeholder' => trans('admin::view.type')]) !!}
            {!! errorField('type') !!}
        </div>

        @if(cando('edit_other_files'))
        <div class="form-group">
            <label>{{trans('admin::view.created_at')}}</label>
            <div class="time_group">
                <div class="t_field">
                    <span>{{trans('admin::view.day')}}</span>
                    <select name="time[day]" class="form-control">
                        {!! range_options(1, 31, $item->created_at->format('d')) !!}
                    </select>
                </div>
                <div class="t_field">
                    <span>{{trans('admin::view.month')}}</span>
                    <select name="time[month]" class="form-control">
                        {!! range_options(1, 12, $item->created_at->format('m')) !!}
                    </select>
                </div>
                <div class="t_field">
                    <span>{{trans('admin::view.year')}}</span>
                    <select name="time[year]" class="form-control">
                        {!! range_options(2015, 2030, $item->created_at->format('Y')) !!}
                    </select>
                </div>
            </div>
        </div>
        @endif

        @if(cando('manage_files'))
        <div class="form-group">
            <label>{{trans('admin::view.author')}}</label>
            {!! Form::select('author_id', $users, $item->author_id, ['class' => 'form-control']) !!}
        </div>
        @endif

        <a href="{{route('admin::file.index', ['status' => AdConst::STT_PUBLISH])}}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{trans('admin::view.back')}}</a>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('admin::view.update')}}</button>

    </div>

    <div class="col-sm-6">

        <div class="form-group">
            <label>{{trans('admin::view.thumbnail')}}</label>
            <div>
                {!! $item->getImage('full') !!}
            </div>
        </div>

    </div>
</div>
{!! Form::close() !!}

@stop


