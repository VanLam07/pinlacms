<?php
use Admin\Facades\AdConst;
?>

@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_medias'))

@section('content')

{!! showMessage() !!}

{!! Form::open(['method' => 'media', 'route' => 'admin::media.store']) !!}

<div class="row">
    <div class="col-sm-8">

        @include('admin::parts.lang_tabs')

        <div class="tab-content">
            @foreach($langs as $lang)
            <?php $code = $lang->code; ?>
            <div class="tab-pane fade in {{ localeActive($code) }}" id="tab-{{$lang->code}}">

                <div class="form-group">
                    <label>{{trans('admin::view.name')}} (*)</label>
                    {!! Form::text($code.'[name]', old($code.'.name'), ['class' => 'form-control', 'placeholder' => trans('admin::view.title')]) !!}
                    {!! errorField($code.'.name') !!}
                </div>

                <div class="form-group">
                    <label>{{trans('admin::view.slug')}}</label>
                    {!! Form::text($code.'[slug]', old($code.'.slug'), ['class' => 'form-control', 'placeholder' => trans('admin::view.slug')]) !!}
                </div>

                <div class="form-group">
                    <label>{{trans('admin::view.description')}}</label>
                    {!! Form::textarea($code.'[description]', old($code.'.description'), ['class' => 'form-control editor_content', 'rows' => 15, 'placeholder' => trans('admin::view.content')]) !!}
                </div>

            </div>
            @endforeach
        </div>
        
 
        <div class="form-group">
            <label>{{trans('admin::view.created_at')}}</label>
            <div class="time_group">
                <div class="t_field">
                    <span>{{trans('admin::view.day')}}</span>
                    <select name="time[day]" class="form-control">
                        {!! rangeOptions(1, 31, date('d')) !!}
                    </select>
                </div>
                <div class="t_field">
                    <span>{{trans('admin::view.month')}}</span>
                    <select name="time[month]" class="form-control">
                        {!! rangeOptions(1, 12, date('m')) !!}
                    </select>
                </div>
                <div class="t_field">
                    <span>{{trans('admin::view.year')}}</span>
                    <select name="time[year]" class="form-control">
                        {!! rangeOptions(2010, 2030, date('Y')) !!}
                    </select>
                </div>
            </div>
        </div>


    </div>

    <div class="col-sm-4">
        
        <div class="form-group thumb_box" >
            <label>{{trans('admin::view.thumbnail')}}</label>
            <div class="thumb_group">
            </div>
            {!! errorField('file_ids') !!}
            <div><button type="button" class="btn btn-default btn-files-modal" 
                         data-href="{{route('admin::file.dialog')}}">{{trans('admin::view.add_image')}}</button></div>
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.status')}}</label>
            {!! Form::select('status', AdView::getStatusLabel(false), old('status'), ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.albums')}}</label>
            <ul class="cat-check-lists">
                @if($albums)
                    @foreach($albums as $al)
                    <li><label>{!! Form::checkbox('cat_ids[]', $al->id) !!} {{$al->name}}</label></li>
                    @endforeach
                @endif
            </ul>
        </div>
        
        <div class="form-group">
            <a href="{{route('admin::media.index', ['status' => 1])}}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{trans('admin::view.back')}}</a>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('admin::view.create')}}</button>
        </div>

    </div>
</div>

{!! Form::close() !!}

@stop

@section('foot')

@include('admin::file.manager')

@stop

