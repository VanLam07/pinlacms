@extends('admin::layouts.manage')

@section('title', trans('admin::view.change_pass'))

@section('content')

<div class="row">
    <div class="col-sm-8">
        {!! showMessage() !!}
            
        {!! Form::open(['method' => 'post', 'route' => 'admin::account.update_pass']) !!}
        <?php
        $user = auth()->user(); 
        ?>
        
        <div class="form-group row">
            <label class="col-sm-4">{{ trans('admin::view.old_password') }}</label>
            <div class="col-sm-8">
                {!! Form::password('old_password', ['class' => 'form-control', 'placeholder' => trans('admin::view.old_password')]) !!}
                {!! errorField('old_password') !!}
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4">{{ trans('admin::view.new_password') }}</label>
            <div class="col-sm-8">
                {!! Form::password('new_password', ['class' => 'form-control', 'placeholder' => trans('admin::view.new_password')]) !!}
                {!! errorField('new_password') !!}
            </div>
        </div>
        
        <div class="form-group row">
            <label class="col-sm-4">{{ trans('admin::view.repassword') }}</label>
            <div class="col-sm-8">
                {!! Form::password('new_password_confirmation', ['class' => 'form-control', 'placeholder' => trans('admin::view.repassword')]) !!}
            </div>
        </div>
        
        <div class="form-group row">
            <label class="col-sm-4"></label>
            <div class="col-sm-8">
                <a href="{{ route('admin::account.profile') }}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{ trans('admin::view.back') }}</a>
                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> {{ trans('admin::view.save') }}</button>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
</div>

@stop

