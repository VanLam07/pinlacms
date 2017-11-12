@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_langs'))

@section('page_title', trans('admin::view.man_langs'))

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
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th width="30"><input type="checkbox" name="massdel" class="check_all"/></th>
                <th>{{trans('admin::view.icon')}}</th>
                <th>{{trans('admin::view.name')}} {!! linkOrder('name') !!}</th>
                <th>{{trans('admin::view.code')}} {!! linkOrder('code') !!}</th>
                <th>{{trans('admin::view.order')}} {!! linkOrder('order') !!}</th>
                <th>{{trans('admin::view.status')}} {!! linkOrder('status') !!}</th>
                <th>{{trans('admin::view.default')}}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td>
                    <input type="text" name="filters[name]" value="{{ getRequestParam('filters', 'name') }}"
                           placeholder="{{ trans('admin::view.search') }}..." class="form-control filter-data">
                </td>
                <td>
                    <input type="text" name="filters[code]" value="{{ getRequestParam('filters', 'code') }}"
                           placeholder="{{ trans('admin::view.search') }}..." class="form-control filter-data">
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @if (!$items->isEmpty())
                @foreach($items as $item)
                <tr>
                    <td><input type="checkbox" name="checked[]" class="check_item" value="{{ $item->code }}" /></td>
                    <td>{!! $item->icon() !!}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->order }}</td>
                    <td>{{ $item->status() }}</td>
                    <td>{{ $item->strDefault() }}</td>
                    <td>
                        <a href="{{route('admin::lang.edit', ['code' => $item->code])}}" 
                           class="btn btn-sm btn-info" title="{{trans('admin::view.edit')}}"><i class="fa fa-edit"></i></a>
                    </td>
                </tr>
                @endforeach
            @else
            <tr>
                <td colspan="8" class="text-center"><h4>{{ trans('admin::message.not_found_items') }}</h4></td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

@stop

