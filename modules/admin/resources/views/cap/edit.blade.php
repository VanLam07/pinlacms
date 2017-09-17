@extends('admin::layouts.manage')

@section('title', trans('admin::view.edit_cap'))

@section('content')

<div class="row">
    <div class="col-sm-6">
        
        {!! showMessage() !!}
        
        {!! Form::open(['method' => 'put', 'route' => ['admin::cap.update', $item->name]]) !!}

            <div class="form-group">
                <label>{{ trans('admin::view.name') }} (*)</label>
                {!! Form::text('', $item->name, ['disabled', 'class' => 'form-control', 'placeholder' => trans('admin::view.name')]) !!}
                {!! errorField('name') !!}
            </div>

            <div class="form-group">
                <label>{{ trans('admin::view.description') }}</label>
                {!! Form::text('label', $item->label, ['class' => 'form-control', 'placeholder' => trans('admin::view.description')]) !!}
                {!! errorField('label') !!}
            </div>

            <a href="{{ route('admin::cap.index') }}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{ trans('admin::view.back') }}</a>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('admin::view.update') }}</button>

        {!! Form::close() !!}
        
    </div>
</div>

@stop

