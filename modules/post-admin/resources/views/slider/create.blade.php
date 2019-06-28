@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_sliders'))

<?php
use Admin\Facades\AdConst;
?>

@section('content')

<div class="row">
    <div class="col-sm-6">

        {!! showMessage() !!}

        {!! Form::open(['method' => 'post', 'route' => 'admin::slider.store']) !!}


        @include('admin::parts.lang_tabs')

        <div class="tab-content">
            @foreach($langs as $lang)
            <?php $code = $lang->code; ?>
            <div class="tab-pane fade in {{ localeActive($code) }}" id="tab-{{$lang->code}}">

                <div class="form-group">
                    <label>{{trans('admin::view.name')}} (*)</label>
                    {!! Form::text($code.'[name]', old($code.'.name'), ['class' => 'form-control', 'placeholder' => trans('admin::view.name')]) !!}
                    {!! errorField($code.'.name') !!}
                </div>

            </div>
            @endforeach
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.status')}}</label>
            {!! Form::select('status', AdView::getStatusLabel(false), old('status'), ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            <a href="{{route('admin::slider.index', ['status' => AdConst::STT_PUBLISH])}}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{trans('admin::view.back')}}</a>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('admin::view.create')}}</button>
        </div>


        {!! Form::close() !!}

    </div>
</div>

@stop

