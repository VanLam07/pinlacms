@extends('admin::layouts.manage')

@section('title', trans('admin::view.subscribe'))

<?php 
use Admin\Facades\AdConst;

$status = request()->get('status');
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
                <th>
                    <input type="checkbox" name="massdel" class="check_all">
                </th>
                <th>No.</th>
                <th>{{ trans('admin::view.fullname') }} {!! linkOrder('name') !!}</th>
                <th>{{ trans('admin::view.email') }} {!! linkOrder('email') !!}</th>
                <th>{{ trans('admin::view.time_receive') }} {!! linkOrder('time') !!}</th>
                <th>{{ trans('admin::view.status') }} {!! linkOrder('status') !!}</th>
                <th>{{ trans('admin::view.subscribed_at') }} {!! linkOrder('created_at') !!}</th>
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
                <td></td>
            </tr>
            @if (!$items->isEmpty())
                <?php
                $perPage = $items->perPage();
                $currPage = $items->currentPage();
                ?>
                @foreach($items as $order => $item)
                <tr>
                    <td>
                        <input type="checkbox" class="check_item" name="check_items[]" value="{{ $item->id }}">
                    </td>
                    <td>{{ $order + 1 + ($currPage - 1) * $perPage }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->time }}</td>
                    <td>{{ $item->getStatusLabel() }}</td>
                    <td>{{ $item->created_at }}</td>
                    <td>
                        <a href="{{ route('admin::subs.edit', ['id' => $item->id]) }}"
                           class="btn btn-sm btn-info" title="{{ trans('admin::view.edit') }}"><i class="fa fa-edit"></i></a>
                    </td>
                </tr>
                @endforeach
            @else
            <tr>
                <td colspan="7"><h4 class="text-center">{{ trans('admin::message.not_found_items') }}</h4></td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

@include('admin::parts.paginate')

@stop

