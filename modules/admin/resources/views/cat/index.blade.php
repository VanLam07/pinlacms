@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_cats'))

<?php 
use Admin\Facades\AdConst;

$multiActions = ['delete', 'draft'];
$statuses = [AdConst::STT_PUBLISH, AdConst::STT_DRAFT];
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
                <th>ID {!! linkOrder('taxs.id') !!}</th>
                <th>{{trans('admin::view.name')}} {!! linkOrder('td.name') !!}</th>
                <th>{{trans('admin::view.slug')}}</th>
                <th>{{trans('admin::view.parent')}} {!! linkOrder('parent_id') !!}</th>
                <th>{{trans('admin::view.order')}} {!! linkOrder('order') !!}</th>
                <th>{{trans('admin::view.count')}} {!! linkOrder('count') !!}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td>
                    <input type="text" name="filters[td.name]" value="{{ getRequestParam('filters', 'td.name') }}"
                           class="form-control filter-data" placeholder="{{ trans('admin::view.search') }}">
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @if(!$items->isEmpty())
            {!! $tableCats !!}
            @else
            <tr>
                <td colspan="8"><h4 class="text-center">{{ trans('admin::message.not_found_items') }}</h4></td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

@stop

