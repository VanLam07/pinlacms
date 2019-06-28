@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_caps'))

<?php 
$multiActions = ['delete'];
$statuses = [];
$actionCaps = [];
?>

@section('nav_status')
    @include('admin::parts.actions-nav')
@stop

@section('content')

{!! showMessage() !!}

<div class="table-responsive">
    
    <table class="table table-hover table-striped table-bordered">
        <thead>
            <tr>
                <th width="30"><input type="checkbox" name="massaction" class="check_all"/></th>
                <th>{{ trans('admin::view.name') }} {!! linkOrder('name') !!}</th>
                <th>{{ trans('admin::view.description') }} {!! linkOrder('label') !!}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td>
                    <input type="text" name="filters[name]" value="{{ getRequestParam('filters', 'name') }}" 
                           placeholder="@lang('admin::view.search')" class="form-control filter-data">
                </td>
                <td></td>
                <td></td>
            </tr>
            @if (!$items->isEmpty())
                @foreach($items as $item)
                <tr>
                    <td><input type="checkbox" name="checked[]" class="check_item" value="{{ $item->name }}" /></td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->label }}</td>
                    <td>
                        <a href="{{route('admin::cap.edit', ['id' => $item->name])}}" 
                           class="btn btn-sm btn-info" data-toggle="tooltip" 
                           data-placement="top" title="{{ trans('admin::view.edit') }}"><i class="fa fa-edit"></i></a>
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center"><h4>@lang('admin::message.not_found_items')</h4></td>
                </tr>
            @endif
        </tbody>
    </table>
    
</div>
    
@include('admin::parts.paginate')

@stop

