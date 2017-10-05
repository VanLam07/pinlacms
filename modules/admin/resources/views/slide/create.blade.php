@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_slides'))

@section('content')

{!! showMessage() !!}

<p><strong>{{ trans('admin::view.sliders') }}: {{ $slider->name }}</strong></p>

{!! Form::open(['method' => 'slide', 'route' => 'admin::slide.store']) !!}

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

            </div>
            @endforeach
        </div>
        
        <div class="form-group">
            <label>{{trans('admin::view.target')}}</label>
            {!! Form::text('target', old('target'), ['class' => 'form-control']) !!}
        </div>
        
    </div>

    <div class="col-sm-4">

        <div class="form-group thumb_box" >
            <label>{{trans('admin::view.thumbnail')}}</label>
            <div class="thumb_group">
            </div>
            {!! errorField('file_ids') !!}
            <div><button type="button" class="btn btn-default btn-files-modal" data-href="{{route('admin::file.dialog')}}">{{trans('admin::view.add_image')}}</button></div>
        </div>
        
        {!! Form::hidden('slider_id', $slider_id) !!}
        
        <div class="form-group">
            <a href="{{route('admin::slide.index', ['slider_id' => $slider_id])}}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{trans('admin::view.back')}}</a>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('admin::view.create')}}</button>
        </div>

    </div>
</div>

{!! Form::close() !!}

@stop

@section('foot')

@include('admin::file.manager')

@stop

