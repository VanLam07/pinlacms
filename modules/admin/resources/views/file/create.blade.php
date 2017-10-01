<?php
use Admin\Facades\AdConst;
?>

@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_files'))

@section('content')

<div class="row">
    <div class="col-sm-6">
        
        {!! showMessage() !!}
        
        {!! Form::open(['method' => 'post', 'route' => 'admin::file.store', 'files' => true]) !!}
        
        <div class="form-group">
            <!--<button class="btn-choose-files btn btn-default" type="button">-->
                <!--<i class="fa fa-upload"></i> {{trans('admin::view.choose_files')}}-->
                {!! Form::file('files[]', ['class' => 'file-input-field', 'multiple']) !!}
            <!--</button>-->
            {!! errorField('file') !!}
            <div id="selected_files">
                
            </div>
        </div>
        
        <a href="{{route('admin::file.index', ['status' => AdConst::STT_PUBLISH])}}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{trans('admin::view.back')}}</a>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('admin::view.create')}}</button>
        
        {!! Form::close() !!}

    </div>
</div>

@stop

