@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_comments'))

<?php 
use Admin\Facades\AdConst;

$status = request()->get('status');
$multiActions = ['delete', 'trash', 'draft'];
$statuses = [AdConst::STT_PUBLISH, AdConst::STT_TRASH, AdConst::STT_DRAFT];
$actionCaps = [
    'create' => 'publish_comment',
    'edit' => 'edit_comment',
    'remove' => 'remove_comment'
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
                <th>{{trans('admin::view.content')}} {!! linkOrder('content') !!}</th>
                <th>{{trans('admin::view.author')}} {!! linkOrder('author_name') !!}</th>
                <th>{{trans('admin::view.author_id')}} {!! linkOrder('author_id') !!}</th>
                <th>{{trans('admin::view.posts')}} {!! linkOrder('post_id') !!}</th>
                <th>{{trans('admin::view.parent')}}</th>
                <th>{{trans('admin::view.created_at')}} {!! linkOrder('created_at') !!}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td>
                    <input type="text" name="filters[content]" value="{{ getRequestParam('filters', 'content') }}"
                           class="form-control filter-data" placeholder="{{ trans('admin::view.search') }}...">
                </td>
                <td></td>
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
                    <td>{{ $item->content }}</td>
                    <td>{{ $item->author_name }}</td>
                    <td>{{ $item->author_id }}</td>
                    <td>{{ $item->getPost->title }}</td>
                    <td>{{ $item->parent_id }}</td>
                    <td>{{ $item->created_at }}</td>
                    <td>
                        @if (canDo('edit_comment', $item->author_id)
                                && $status && $status != AdConst::STT_TRASH)
                            <a href="{{route('admin::comment.edit', ['id' => $item->id])}}" 
                               class="btn btn-sm btn-info" title="{{trans('admin::view.edit')}}"><i class="fa fa-edit"></i></a>
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

@stop

