<?php
use Admin\Facades\AdConst;
?>

@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_cats'))

@section('content')

<div class="row">
    <div class="col-sm-6">

        {!! showMessage() !!}

        {!! Form::open(['method' => 'put', 'route' => ['admin::cat.update', $item->id]]) !!}

        @include('admin::parts.lang_edit_tabs', ['route' => 'admin::cat.edit'])

        <div class="form-group">
            <label>{{trans('admin::view.name')}} (*)</label>
            {!! Form::text('locale[name]', $item->name, ['class' => 'form-control', 'placeholder' => trans('admin::view.name')]) !!}
            {!! errorField('locale.name') !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.slug')}}</label>
            {!! Form::text('locale[slug]', $item->slug, ['class' => 'form-control', 'placeholder' => trans('admin::view.slug')]) !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.description')}}</label>
            {!! Form::textarea('locale[description]', $item->description, ['class' => 'form-control', 'rows' => 2, 'placeholder' => trans('admin::view.description')]) !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.meta_keyword')}}</label>
            {!! Form::text('locale[meta_keyword]', $item->meta_keyword, ['class' => 'form-control', 'placeholder' => trans('admin::view.meta_keyword')]) !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.meta_desc')}}</label>
            {!! Form::textarea('locale[meta_desc]', $item->meta_desc, ['class' => 'form-control', 'rows' => 2, 'placeholder' => trans('admin::view.meta_desc')]) !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.parent')}}</label>
            <select name="parent_id" class="form-control">
                <option value="0">{{trans('admin::view.selection')}}</option>
                {!! nestedOption($parents, $item->parent_id, 0, 0) !!}
            </select>
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.status')}}</label>
            {!! Form::select('status', AdView::getStatusLabel(false), $item->status, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.order')}}</label>
            {!! Form::number('order', $item->order, ['class' => 'form-control']) !!}
        </div>
        
        <div class="form-group">
            <label>{{ trans('admin::view.is_feature') }}</label>
            {!! Form::checkbox('is_feature', 1, $item->is_feature) !!}
        </div>
        
        <input type="hidden" name="lang" value="{{$lang}}">
        {!! errorField('lang') !!}

        <a href="{{route('admin::cat.index', ['status' => AdConst::STT_PUBLISH])}}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{trans('admin::view.back')}}</a>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('admin::view.update')}}</button>

        {!! Form::close() !!}

    </div>
</div>

@stop

