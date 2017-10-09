<?php
use Admin\Facades\AdConst;
?>

@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_users'))

<?php 
$multiActions = ['draft', 'trash', 'delete'];
$statuses = [AdConst::STT_PUBLISH, AdConst::STT_DRAFT, AdConst::STT_TRASH];
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
                <th>{{trans('admin::view.name')}} {!! linkOrder('name') !!}</th>
                <th>{{trans('admin::view.email')}} {!! linkOrder('email') !!}</th>
                <th>{{trans('admin::view.account_type')}} {!! linkOrder('type') !!}</th>
                <th>{{trans('admin::view.role')}}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td>
                    <input type="text" name="filters[name]" value="{{ getRequestParam('filters', 'name') }}"
                           class="form-control filter-data" placeholder="{{ trans('admin::view.search') }}">
                </td>
                <td>
                    <input type="text" name="filters[email]" value="{{ getRequestParam('filters', 'email') }}"
                           class="form-control filter-data" placeholder="{{ trans('admin::view.search') }}">
                </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @if (!$items->isEmpty())
                @foreach($items as $item)
                <tr>
                    <td><input type="checkbox" name="check_items[]" class="check_item" value="{{ $item->id }}" /></td>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->type }}</td>
                    <td>{{ $item->getRoles() }}</td>
                    <td>
                        <a href="{{ route('admin::user.edit', ['id' => $item->id]) }}" 
                           class="btn btn-sm btn-info" 
                           title="{{ trans('admin::view.edit') }}"><i class="fa fa-edit"></i></a>
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center"><h4>@lang('admin::message.not_found_items')</h4></td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

@include('admin::parts.paginate')

@stop

