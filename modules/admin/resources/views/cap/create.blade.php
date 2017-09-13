@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_caps'))


@section('content')

<div class="row">
    <div class="col-sm-6">
        
        {!! showMessage() !!}
        
        {!! Form::open(['method' => 'post', 'route' => 'admin::cap.store']) !!}
        
        <div class="form-group">
            <label>{{ trans('admin::view.name') }} (*)</label>
            {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => trans('admin::view.name')]) !!}
            {!! errorField('name') !!}
        </div>
        
        <div class="form-group">
            <label>{{ trans('admin::view.description') }}</label>
            {!! Form::text('label', old('label'), ['class' => 'form-control', 'placeholder' => trans('admin::view.description')]) !!}
            {!! errorField('label') !!}
        </div>
        
        <a href="{{ route('admin::cap.index') }}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{ trans('admin::view.back') }}</a>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('admin::view.create') }}</button>
        
        {!! Form::close() !!}
    </div>
</div>

@stop

