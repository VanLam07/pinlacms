@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_pages'))

<?php
use Admin\Facades\AdConst;
?>

@section('content')

{!! showMessage() !!}

{!! Form::open(['method' => 'put', 'route' => ['admin::page.update', $item->id]]) !!}

<div class="row">
    <div class="col-sm-9">

        @include('admin::parts.lang_edit_tabs', ['route' => 'admin::page.edit'])

        <div class="form-group">
            <label>{{trans('admin::view.name')}} (*)</label>
            {!! Form::text('locale[title]', $item->title, ['class' => 'form-control', 'placeholder' => trans('admin::view.name')]) !!}
            {!! errorField('locale.title') !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.slug')}}</label>
            {!! Form::text('locale[slug]', $item->slug, ['class' => 'form-control', 'placeholder' => trans('admin::view.slug')]) !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.content')}}</label>
            {!! Form::textarea('locale[content]', $item->content, ['class' => 'form-control editor_content', 'rows' => 15, 'placeholder' => trans('admin::view.content')]) !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.excerpt')}}</label>
            {!! Form::textarea('locale[excerpt]', $item->excerpt, ['class' => 'form-control editor_excerpt', 'rows' => 5, 'placeholder' => trans('admin::view.excerpt')]) !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.meta_keyword')}}</label>
            {!! Form::text('locale[meta_keyword]', $item->meta_keyword, ['class' => 'form-control', 'placeholder' => trans('admin::view.meta_keyword')]) !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.meta_desc')}}</label>
            {!! Form::textarea('locale[meta_desc]', $item->meta_desc, ['class' => 'form-control', 'rows' => 2, 'placeholder' => trans('admin::view.meta_desc')]) !!}
        </div>

    </div>
    <div class="col-sm-3">

        <div class="form-group">
            <label>{{trans('admin::view.status')}}</label>
            {!! Form::select('status', AdView::getStatusLabel(), $item->status, ['class' => 'form-control']) !!}
        </div>
        
        <div class="form-group">
            <label>{{trans('admin::view.template')}}</label>
            {!! Form::select('template', $templates, $item->template, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.comment_status')}}</label>
            {!! Form::select('comment_status', [1 => 'Open', 0 => 'Close'], $item->comment_status, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.views')}}</label>
            {!! Form::number('views', $item->views, ['class' => 'form-control']) !!}
        </div>
        
        <div class="form-group">
            <label>{{trans('admin::view.created_at')}}</label>
            <div class="time_group">
                <div class="t_field">
                    <span>{{trans('admin::view.day')}}</span>
                    <select name="time[day]" class="form-control">
                        {!! rangeOptions(1, 31, $item->created_at->format('d')) !!}
                    </select>
                </div>
                <div class="t_field">
                    <span>{{trans('admin::view.month')}}</span>
                    <select name="time[month]" class="form-control">
                        {!! rangeOptions(1, 12, $item->created_at->format('m')) !!}
                    </select>
                </div>
                <div class="t_field">
                    <span>{{trans('admin::view.year')}}</span>
                    <select name="time[year]" class="form-control">
                        {!! rangeOptions(2010, 2030, $item->created_at->format('Y')) !!}
                    </select>
                </div>
            </div>
        </div>
        
        <div class="form-group thumb_box" >
            <label>{{trans('admin::view.thumbnail')}}</label>
            <div class="thumb_group">
                @if ($item->thumbnail)
                <p class="file_item">
                    {!! $item->getThumbnail('full') !!}
                    <a class="f_close"></a>
                    <input type="hidden" name="file_ids[]" value="{{$item->thumb_id}}">
                </p>
                @endif
            </div>
            <div><button type="button" class="btn btn-default btn-files-modal" data-href="{{route('admin::file.dialog')}}">{{trans('admin::view.add_image')}}</button></div>
        </div>
        
        <input type="hidden" name="lang" value="{{$lang}}">
        {!! errorField('lang') !!}

        <div class="form-group">
            <a href="{{route('admin::page.index', ['status' => AdConst::STT_PUBLISH])}}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{trans('admin::view.back')}}</a>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('admin::view.update')}}</button>
        </div>

    </div>
</div>

{!! Form::close() !!}

@stop

@section('foot')

@include('admin::parts.tinymce-script')
@include('admin::file.manager')

@stop

