@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_pages'))

<?php 
use Admin\Facades\AdConst;

$status = request()->get('status');
$multiActions = ['draft', 'trash', 'delete'];
$statuses = [AdConst::STT_PUBLISH, AdConst::STT_DRAFT, AdConst::STT_TRASH];
$actionCaps = [
    'create' => 'publish_post',
    'edit' => 'edit_post',
    'remove' => 'remove_post'
];
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
                <th>ID {!! linkOrder('id') !!}</th>
                <th>{{trans('admin::view.name')}} {!! linkOrder('pd.title') !!}</th>
                <th>{{trans('admin::view.slug')}}</th>
                <th>{{trans('admin::view.views')}} {!! linkOrder('views') !!}</th>
                <th>{{trans('admin::view.created_at')}} {!! linkOrder('created_at') !!}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td>
                    <input type="text" name="filters[pd.title]" value="{{ getRequestParam('filters', 'pd.title') }}"
                           class="form-control filter-data" placeholder="{{ trans('admin::view.search') }}">
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @if (!$items->isEmpty())
                @foreach($items as $item)
                <tr>
                    <td>
                        @if (hasActionItem($actionCaps, $item, $status))
                        <input type="checkbox" name="check_items[]" class="check_item" value="{{ $item->id }}" />
                        @endif
                    </td>
                    <td>{{$item->id}}</td>
                    <td>{{$item->title}}</td>
                    <td>{{$item->slug}}</td>
                    <td>{{$item->views}}</td>
                    <td>{{$item->created_at}}</td>
                    <td>
                        @if (canDo('edit_post', $item->author_id) &&
                            $status && $status != AdConst::STT_TRASH)
                            <a href="{{route('admin::page.edit', ['id' => $item->id])}}" 
                               class="btn btn-sm btn-info" title="{{trans('admin::view.edit')}}"><i class="fa fa-edit"></i></a>
                        @endif
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

