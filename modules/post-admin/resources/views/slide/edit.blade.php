@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_slides'))

@section('content')

{!! showMessage() !!}

<p><strong>{{ trans('admin::view.sliders') }}: {{ $slider->name }}</strong></p>

{!! Form::open(['method' => 'put', 'route' => ['admin::slide.update', $item->id]]) !!}

<div class="row">
    <div class="col-sm-8">
        
        @include('admin::parts.lang_edit_tabs', ['route' => 'admin::slide.edit'])

        <div class="form-group">
            <label>{{trans('admin::view.name')}} (*)</label>
            {!! Form::text('locale[name]', $item->name, ['class' => 'form-control', 'placeholder' => trans('admin::view.name')]) !!}
            {!! errorField('locale.name') !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.target')}}</label>
            {!! Form::text('target', $item->target, ['class' => 'form-control']) !!}
        </div>

    </div>
    <div class="col-sm-4">

        <div class="form-group thumb_box" >
            <label>{{trans('admin::view.thumbnail')}}</label>
            <div class="thumb_group">
                @if ($item->thumb_id)
                <p class="file_item">
                    {!! $item->getThumbnail('full') !!}
                    <a class="f_close"></a>
                    <input type="hidden" name="file_ids[]" value="{{$item->thumb_id}}">
                </p>
                @endif
            </div>
            {!! errorField('file_ids') !!}
            <div><button type="button" class="btn btn-default btn-files-modal" data-href="{{route('admin::file.dialog')}}">{{trans('admin::view.add_image')}}</button></div>
        </div>
        
        <div class="form-group">
            <input type="hidden" name="lang" value="{{$lang}}">
            <a href="{{route('admin::slide.index', ['slider_id' => $item->slider_id])}}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{trans('admin::view.back')}}</a>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('admin::view.update')}}</button>
        </div>

    </div>
</div>

{!! Form::close() !!}

@stop

@section('foot')

@include('admin::file.manager')

@stop

