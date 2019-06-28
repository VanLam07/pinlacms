@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_roles'))

@section('content')

<div class="row">
    <div class="col-sm-6">
        
        {!! showMessage() !!}
        
        {!! Form::open(['method' => 'post', 'route' => 'admin::role.store']) !!}
        
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
        
        <div class="form-group">
            <label>{{ trans('admin::view.default') }}</label>
            {!! Form::select('default', [0 => 'No', 1 => 'Yes'], 0, ['class' => 'form-control']) !!}
        </div>
        
        <a href="{{ route('admin::role.index') }}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{ trans('admin::view.back') }}</a>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('admin::view.create') }}</button>
        
        {!! Form::close() !!}
    </div>
</div>

@stop

