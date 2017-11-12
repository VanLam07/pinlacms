@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_options'))

@section('content')

<div class="row">
    <div class="col-sm-6">
        
        {!! showMessage() !!}
        
        {!! Form::open(['method' => 'put', 'route' => ['admin::option.update', $item->option_id]]) !!}

        <div class="form-group">
            <label>{{trans('admin::view.name')}} (*)</label>
            <input type="text" name="option_key" value="{{ $item->option_key }}"
                   class="form-control" placeholder="{{trans('admin::view.name')}}">
            {!! errorField('option_key') !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.value')}}</label>
            <textarea name="value" id="file_url" 
                      rows="2" class="form-control option-value" 
                      placeholder="{{trans('admin::view.value')}}">{{ $item->value }}</textarea>
            {!! errorField('value') !!}
            <br />
            <div class="thumb_group"></div>
            <div>
                <button type="button" 
                        class="btn btn-default btn-files-modal" 
                        data-href="{{route('admin::file.dialog', ['callback' => 'setOptionValue'])}}">
                    {{trans('admin::view.add_image')}}
                </button>
            </div>
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.language')}}</label>
            <select name="lang_code" class="form-control">
                <option value="">&nbsp;</option>
                @foreach($langs as $lang)
                    <option value="{{$lang->code}}" {{ $lang->code == $item->lang_code ? 'selected' : '' }}>{{$lang->code}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <a href="{{ route('admin::option.index') }}" class="btn btn-warning">
                <i class="fa fa-long-arrow-left"></i> {{ trans('admin::view.back') }}
            </a>
            <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> {{ trans('admin::view.update') }}</button>
        </div>
        
        {!! Form::close() !!}
    </div>
</div>

@stop

@section('foot')

@include('admin::file.manager')

@stop