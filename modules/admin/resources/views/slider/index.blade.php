@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_sliders'))

<?php 
use Admin\Facades\AdConst;

$multiActions = ['delete', 'draft'];
$statuses = [AdConst::STT_PUBLISH, AdConst::STT_DRAFT];
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
                <th>ID {!! linkOrder('taxs.id') !!}</th>
                <th>{{trans('admin::view.name')}} {!! linkOrder('td.name') !!}</th>
                <th>{{trans('admin::view.slug')}}</th>
                <th>{{trans('admin::view.man_childs')}}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td>
                    <input type="text" name="filters[td.name]" value="{{ getRequestParam('filters', 'td.name') }}"
                           class="form-control filter-data" placeholder="{{ trans('admin::view.search') }}...">
                </td>
                <td></td>
                <td></td>
            </tr>
            @if (!$items->isEmpty())
                @foreach($items as $item)
                <tr>
                    <td><input type="checkbox" name="check_items[]" class="check_item" value="{{ $item->id }}" /></td>
                    <td>{{$item->id}}</td>
                    <td>{{$item->name}}</td>
                    <td>{{$item->slug}}</td>
                    <td><a href="{{route('admin::slide.index', ['slider_id' => $item->id])}}" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-right"></i></a></td>
                    <td>
                        <a href="{{route('admin::slider.edit', ['id' => $item->id])}}" class="btn btn-sm btn-info" title="{{trans('admin::view.edit')}}"><i class="fa fa-edit"></i></a>
                    </td>
                </tr>
                @endforeach
            @else
            <tr>
                <td colspan="6"><h4 class="text-center">{{ trans('admin::message.not_found_items') }}</h4></td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

@stop

