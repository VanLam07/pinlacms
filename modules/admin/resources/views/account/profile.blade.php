@extends('admin::layouts.manage')

@section('title', trans('admin::view.account_info'))

@section('content')

{!! showMessage() !!}

<div class="row">
    <div class="col-sm-8">
        {!! Form::open(['method' => 'post', 'route' => 'admin::account.update_profile']) !!}
        <?php $user = auth()->user(); ?>
        
        <div class="form-group row">
            <label class="col-sm-4">{{ trans('admin::view.name') }}</label>
            <div class="col-sm-8">
                {!! Form::text('name', $user->name, ['class' => 'form-control']) !!}
                {!! errorField('name') !!}
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4">{{ trans('admin::view.email') }}</label>
            <div class="col-sm-8">
                {!! Form::email('email', $user->email, ['class' => 'form-control', 'disabled']) !!}
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4">{{ trans('admin::view.role') }}</label>
            <div class="col-sm-8">
                @if(canDo('edit_my_user'))
                    @if ($roles)
                    <ul class="list-unstyled">
                        <?php
                        $userRoles = $user->roles()->pluck('id')->toArray();
                        ?>
                        @foreach ($roles as $role)
                        <li><label>{!! Form::checkbox('role_ids[]', $role->id, in_array($role->id, $userRoles)) !!} {{ $role->label }}</label></li>
                        @endforeach
                    </ul>
                    @endif
                @else
                    {!! Form::text('', $user->getRoles(), ['class' => 'form-control', 'disabled']) !!}
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4">{{trans('admin::view.gender')}}</label>
            <div class="col-sm-8">
                {!! Form::select('gender', AdView::getGendersList(), $user->gender, ['class' => 'form-control']) !!}
            </div>
        </div>
        
        <div class="form-group row">
            <label class="col-sm-4">{{ trans('admin::view.avatar') }}</label>
            <div class="col-sm-8">
                <div class="thumb_group">
                    <div class="thumb_item">
                        @if ($user->avatar)
                        <p class="file_item">
                            {!! $user->getAvatar('thumbnail') !!}
                            <a class="f_close"></a>
                            <input type="hidden" name="file_ids[]" value="{{$user->image_id}}">
                        </p>
                        @endif
                    </div>
                </div>
                <div>
                    <button type="button" class="btn btn-default btn-files-modal" data-href="{{ route('admin::file.dialog') }}">
                        {{ trans('admin::view.add_image') }}
                    </button>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4">{{ trans('admin::view.birth') }}</label>
            <div class="col-sm-8">
                <div class="row">
                    <div class="col-xs-4">
                        <select name="birth[day]" class="form-control">
                            {!! rangeOptions(1, 31, $user->birth ? $user->birth->format('d') : null) !!}
                        </select>
                    </div>
                    <div class="col-xs-4">
                        <select name="birth[month]" class="form-control">
                            {!! rangeOptions(1, 12, $user->birth ? $user->birth->format('m') : null) !!}
                        </select>
                    </div>
                    <div class="col-xs-4">
                        <select name="birth[year]" class="form-control">
                            {!! rangeOptions(1972, 2030, $user->birth ? $user->birth->format('Y') : null) !!}
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-group row">
            <label class="col-sm-4"></label>
            <div class="col-sm-8">
                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> {{ trans('admin::view.save') }}</button>
                <a href="{{ route('admin::account.change_pass') }}" class="btn btn-info"><i class="fa fa-lock"></i> {{ trans('admin::view.change_pass') }}</a>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
</div>

@stop

@section('foot')

<script src="/plugins/tinymce/tinymce.min.js"></script>
<script src="/admin_src/js/tinymce_script.js"></script>

@include('files.manager')

@stop

