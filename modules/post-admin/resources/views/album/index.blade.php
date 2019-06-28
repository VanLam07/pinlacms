@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_albums'))

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
                <th width="30"><input type="checkbox" name="massdel" class="check_all"/></th>
                <th>ID {!! linkOrder('taxs.id') !!}</th>
                <th>{{trans('admin::view.icon')}}</th>
                <th>{{trans('admin::view.name')}} {!! linkOrder('td.name') !!}</th>
                <th>{{trans('admin::view.slug')}}</th>
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
                           class="form-control filter-data" placeholder="{{ trans('admin::view.search') }}...">
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @if (!$items->isEmpty())
                @foreach($items as $item)
                <tr>
                    <td><input type="checkbox" name="check_items[]" class="check_item" value="{{ $item->id }}" /></td>
                    <td>{{$item->id}}</td>
                    <td>
                        @if ($item->thumbnail)
                            <img width="50" src="{{ $item->getThumbnailSrc('thumbnail') }}" alt="Thumbnail">
                        @endif
                    </td>
                    <td>{{$item->name}}</td>
                    <td>{{$item->slug}}</td>
                    <td><a href="{{route('admin::media.index', ['albums' => [$item->id], 'status' => 1])}}">{{$item->count}}</a></td>
                    <td>
                        <a href="{{route('admin::album.edit', ['id' => $item->id])}}" 
                           class="btn btn-sm btn-info" title="{{trans('admin::view.edit')}}"><i class="fa fa-edit"></i></a>
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center"><h4>@lang('admin::message.not_found_items')</h4></td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

@include('admin::parts.paginate')

@stop

