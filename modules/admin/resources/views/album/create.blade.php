@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_albums'))

@section('content')

{!! showMessage() !!}

{!! Form::open(['method' => 'post', 'route' => 'admin::album.store']) !!}

<div class="row">
    <div class="col-sm-8">

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
            <a href="{{route('admin::album.index')}}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{trans('admin::view.back')}}</a>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('admin::view.create')}}</button>
        </div>
        
    </div>
</div>

{!! Form::close() !!}

@stop

@section('foot')

@include('admin::file.manager')

@stop

