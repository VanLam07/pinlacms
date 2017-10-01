@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_roles'))

<?php
$multiActions = ['delete'];
$statuses = [];
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
                <th width="30"><input type="checkbox" class="check_all"/></th>
                <th>ID {!! linkOrder('id') !!}</th>
                <th>{{ trans('admin::view.name') }} {!! linkOrder('id') !!}</th>
                <th>{{ trans('admin::view.description') }} {!! linkOrder('id') !!}</th>
                <th>{{ trans('admin::view.default') }} {!! linkOrder('id') !!}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td>
                    <input type="text" name="filters[name]" 
                           value="{{ getRequestParam('filters', 'name') }}" 
                           placeholder="{{ trans('admin::view.search') }}"
                           class="form-control filter-data">
                </td>
                <td>
                    <input type="text" name="filters[label]" 
                           value="{{ getRequestParam('filters', 'label') }}" 
                           placeholder="{{ trans('admin::view.search') }}"
                           class="form-control filter-data">
                </td>
                <td></td>
            </tr>
            @if (!$items->isEmpty())
                @foreach($items as $item)
                <tr>
                    <td><input type="checkbox" name="check_items[]" class="check_item" value="{{ $item->id }}" /></td>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->label }}</td>
                    <td>{{ $item->strDefault() }}</td>
                    <td>
                        <a href="{{ route('admin::role.edit', ['id' => $item->id]) }}" 
                           class="btn btn-sm btn-info" title="{{ trans('admin::view.edit') }}"><i class="fa fa-edit"></i></a>
                    </td>
                </tr>
                @endforeach
            @else
            <tr>
                <td class="text-center" colspan="6"><h4>@lang('admin::message.not_found_items')</h4></td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

@include('admin::parts.paginate')

@stop

