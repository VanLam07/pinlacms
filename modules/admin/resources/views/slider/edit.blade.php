@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_sliders'))

<?php
use Admin\Facades\AdConst;
?>

@section('content')

{!! showMessage() !!}

@include('admin::parts.lang_edit_tabs', ['route' => 'admin::slider.edit'])

{!! Form::open(['method' => 'put', 'route' => ['admin::slider.update', $item->id]]) !!}

<div class="row">
    <div class="col-sm-6">

        <div class="form-group">
            <label>{{trans('admin::view.name')}} (*)</label>
            {!! Form::text('locale[name]', $item->name, ['class' => 'form-control', 'placeholder' => trans('admin::view.name')]) !!}
            {!! errorField('locale.name') !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.status')}}</label>
            {!! Form::select('status', AdView::getStatusLabel(false), $item->status, ['class' => 'form-control']) !!}
        </div>

        <input type="hidden" name="lang" value="{{$lang}}">
        {!! errorField('lang') !!}

        <div class="form-group">
            <a href="{{route('admin::slider.index', ['status' => AdConst::STT_PUBLISH])}}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{trans('admin::view.back')}}</a>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('admin::view.update')}}</button>
        </div>

    </div>

</div>

{!! Form::close() !!}

@stop


