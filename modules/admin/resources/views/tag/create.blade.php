@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_tags'))

<?php
use Admin\Facades\AdConst;
?>

@section('content')

<div class="row">
    <div class="col-sm-6">

        {!! showMessage() !!}

        {!! Form::open(['method' => 'post', 'route' => 'admin::tag.store']) !!}

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

                <div class="form-group">
                    <label>{{trans('admin::view.slug')}}</label>
                    {!! Form::text($code.'[slug]', old($code.'.slug'), ['class' => 'form-control', 'placeholder' => trans('admin::view.slug')]) !!}
                </div>

                <div class="form-group">
                    <label>{{trans('admin::view.description')}}</label>
                    {!! Form::textarea($code.'[description]', old($code.'.description'), ['class' => 'form-control', 'rows' => 2, 'placeholder' => trans('admin::view.description')]) !!}
                </div>

                <div class="form-group">
                    <label>{{trans('admin::view.meta_keyword')}}</label>
                    {!! Form::text($code.'[meta_keyword]', old($code.'.meta_keyword'), ['class' => 'form-control', 'placeholder' => trans('admin::view.meta_keyword')]) !!}
                </div>

                <div class="form-group">
                    <label>{{trans('admin::view.meta_desc')}}</label>
                    {!! Form::textarea($code.'[meta_desc]', old($code.'.meta_desc'), ['class' => 'form-control', 'rows' => 2, 'placeholder' => trans('admin::view.meta_desc')]) !!}
                </div>

            </div>
            @endforeach
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.status')}}</label>
            {!! Form::select('status', AdView::getStatusLabel(false), old('status'), ['class' => 'form-control']) !!}
        </div>

        <a href="{{ route('admin::tag.index', ['status' => AdConst::STT_PUBLISH]) }}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{trans('admin::view.back')}}</a>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('admin::view.create')}}</button>

        {!! Form::close() !!}
    </div>
</div>

@stop

