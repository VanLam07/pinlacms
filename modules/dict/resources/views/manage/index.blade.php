@extends('admin::layouts.manage')

@section('title', trans('dict::view.man_dictionaries'))

<?php 
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
                <th width="30"><input type="checkbox" class="check_all"/></th>
                <th class="ws-nowrap">ID {!! linkOrder('id') !!}</th>
                <th style="min-width: 120px;">{{ trans('dict::view.word') }} {!! linkOrder('word') !!}</th>
                <th>{{ trans('dict::view.type') }} {!! linkOrder('type') !!}</th>
                <th>{{ trans('dict::view.origin') }} {!! linkOrder('origin') !!}</th>
                <th class="ws-nowrap">{{ trans('dict::view.pronun') }} {!! linkOrder('pronun') !!}</th>
                <th>{{ trans('dict::view.mean') }} {!! linkOrder('mean') !!}</th>
                <th>{{ trans('dict::view.detail') }}</th>
                <th>{{ trans('dict::view.detail_origin') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td>
                    <input type="text" name="filters[word]" value="{{ getRequestParam('filters', 'word') }}"
                           class="form-control filter-data" placeholder="{{ trans('dict::view.search') }}">
                </td>
                <td>
                    <input type="text" name="filters[type]" value="{{ getRequestParam('filters', 'type') }}"
                           class="form-control filter-data" placeholder="{{ trans('dict::view.search') }}">
                </td>
                <td>
                    <input type="text" name="filters[origin]" value="{{ getRequestParam('filters', 'origin') }}"
                           class="form-control filter-data" placeholder="{{ trans('dict::view.search') }}">
                </td>
                <td></td>
                <td>
                    <input type="text" name="filters[mean]" value="{{ getRequestParam('filters', 'mean') }}"
                           class="form-control filter-data" placeholder="{{ trans('dict::view.search') }}">
                </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @if (!$items->isEmpty())
                <?php
                $currentPage = $items->currentPage();
                $perPage = $items->perPage();
                ?>
                @foreach($items as $order => $item)
                <tr>
                    <td>
                        <input type="checkbox" name="check_items[]" class="check_item" value="{{ $item->id }}" />
                    </td>
                    <td>{{ $order + 1 + ($currentPage - 1) * $perPage }}</td>
                    <td>{{ $item->word }}</td>
                    <td>{{ $item->type }}</td>
                    <td>{{ $item->origin }}</td>
                    <td>{{ $item->pronun }}</td>
                    <td>{{ $item->mean }}</td>
                    <td>{!! $item->detail !!}</td>
                    <td>{!! $item->detail_origin !!}</td>
                    <td>
                        <a href="{{ route('dict::admin.edit', ['id' => $item->id]) }}" 
                           class="btn btn-sm btn-info" 
                           title="{{ trans('dict::view.edit') }}"><i class="fa fa-edit"></i></a>
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10" class="text-center"><h4>@lang('admin::message.not_found_items')</h4></td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

@include('admin::parts.paginate')

@stop

