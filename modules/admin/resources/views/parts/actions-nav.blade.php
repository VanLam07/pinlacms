<?php
$actionBtns = isset($actionBtns) ? $actionBtns : [];
?>
<div class="actions-nav">
    <ul class="status-nav">
        @yield('options')
    </ul>

    <div class="btn-actions">
        
        @if (array_key_exists('add', $actionBtns))
            <a href="{{ $actionBtns['add'] }}" class="create-btn btn btn-sm btn-success m-b-1" 
               data-toggle="tooltip" title="{{ trans('admin::view.create') }}">
                <i class="fa fa-plus"></i> <span class="">{{ trans('admin::view.create') }}</span>
            </a>
        @endif

        @if (array_key_exists('trash', $actionBtns))
            <form method="post" action="{{ $actionBtns['trash'] }}" class="inline">
                {!! csrf_field() !!}
                <input type="hidden" name="action" value="trash">
                <button type="submit" class="m_action_btn remove-btn btn btn-sm btn-warning m-b-1" data-toggle="tooltip" 
                        title="{{ trans('admin::view.trash') }}">
                    <i class="fa fa-trash"></i> <span class="">{{trans('admin::view.trash')}}</span>
                </button>
                <div class="checked_ids"></div>
            </form>
        @endif

        @if (array_key_exists('delete', $actionBtns))
            <form method="post" action="{{ $actionBtns['delete'] }}" class="inline">
                {!! csrf_field() !!}
                <input type="hidden" name="action" value="delete">
                <button type="submit" class="m_action_btn trash-btn btn btn-sm btn-danger m-b-1" data-toggle="tooltip" 
                        title="{{ trans('admin::view.delete') }}">
                    <i class="fa fa-trash"></i> <span class="">{{ trans('admin::view.delete') }}</span>
                </button>
                <div class="checked_ids"></div>
            </form>
        @endif

        @if (array_key_exists('restore', $actionBtns))
            <form method="post" action="{{ $actionBtns['restore'] }}" class="inline">
                {!! csrf_field() !!}
                <input type="hidden" name="action" value="restore">
                <button type="submit" class="m_action_btn restore-btn btn btn-sm btn-primary m-b-1" 
                        data-toggle="tooltip" title="{{ trans('admin::view.restore') }}">
                    <i class="fa fa-mail-reply"></i> <span class="">{{ trans('admin::view.restore') }}</span>
                </button>
                <div class="checked_ids"></div>
            </form>
        @endif
        
    </div>
</div>

