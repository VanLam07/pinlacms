@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_contact'))

<?php 
use Admin\Facades\AdConst;

$status = request()->get('status');
$multiActions = ['delete'];
$statuses = [AdConst::STT_PUBLISH];
$actionCaps = [
    'create' => 'publish_contact',
    'edit' => 'edit_contact',
    'remove' => 'remove_contact'
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
                <th width="30"><input type="checkbox" class="check_all"/></th>
                <th>ID {!! linkOrder('id') !!}</th>
                <th>{{trans('admin::view.name')}} {!! linkOrder('fullname') !!}</th>
                <th>{{trans('admin::view.email')}} {!! linkOrder('email') !!}</th>
                <th>{{trans('admin::view.subject')}} {!! linkOrder('subject') !!}</th>
                <th>{{trans('admin::view.content')}} {!! linkOrder('content') !!}</th>
                <th>{{trans('admin::view.ip')}} {!! linkOrder('ip') !!}</th>
                <th>{{trans('admin::view.created_at')}} {!! linkOrder('created_at') !!}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td>
                    <input type="text" name="filters[name]" value="{{ getRequestParam('filters', 'name') }}"
                           class="form-control filter-data" placeholder="{{ trans('admin::view.search') }}...">
                </td>
                <td>
                    <input type="text" name="filters[email]" value="{{ getRequestParam('filters', 'email') }}"
                           class="form-control filter-data" placeholder="{{ trans('admin::view.search') }}...">
                </td>
                <td></td>
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
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->fullname }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->subject }}</td>
                    <td>{{ $item->content }}</td>
                    <td>{{ $item->ip }}</td>
                    <td>{{ $item->created_at }}</td>
                    <td>
                        @if (canDo('edit_contact', $item->author_id))
                            <a href="{{route('admin::contact.edit', ['id' => $item->id])}}" 
                               class="btn btn-sm btn-info" title="{{trans('admin::view.view')}}"><i class="fa fa-edit"></i></a>
                        @endif
                    </td>
                </tr>
                @endforeach
            @else
            <tr>
                <td colspan="9" class="text-center"><h4>{{ trans('admin::message.not_found_items') }}</h4></td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

@include('admin::parts.paginate')

@stop

