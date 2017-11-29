@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_tags'))

<?php
use Admin\Facades\AdConst;
?>

@section('content')

<div class="row">
    <div class="col-sm-6">

        {!! showMessage() !!}
        
        @include('admin::parts.lang_edit_tabs', ['route' => 'admin::tag.edit'])

        {!! Form::open(['method' => 'put', 'route' => ['admin::tag.update', $item->id]]) !!}

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
            <label>{{trans('admin::view.status')}}</label>
            {!! Form::select('status', AdView::getStatusLabel(false), $item->status, ['class' => 'form-control']) !!}
        </div>

        <input type="hidden" name="lang" value="{{$lang}}">
        {!! errorField('lang') !!}
        
        <a href="{{ route('admin::tag.index', ['status' => AdConst::STT_PUBLISH]) }}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{trans('admin::view.back')}}</a>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('admin::view.update')}}</button>

        {!! Form::close() !!}

    </div>
</div>

@stop

