<?php
use Admin\Facades\AdConst;

$status = request()->get('status');
$route = request()->route()->getName();
$routePrefix = explode('.', $route)[0];
$routeActions = route($routePrefix.'.actions');

if (!isset($hasAddNew)) {
    $hasAddNew = true;
}
if (!isset($multiActions)) {
    $multiActions = ['delete'];
}
if (!isset($statuses)) {
    $statuses = [AdConst::STT_PUBLISH];
}
?>
<div class="actions-nav">
    
    @if (count($statuses) > 0)
    <div class="status-nav">
        <ul class="list-inline items-link">
            @if (in_array(AdConst::STT_PUBLISH, $statuses))
            <li class="{{ linkClassActive($route, AdConst::STT_PUBLISH) }}">
                <a href="{{ route($route, ['status' => AdConst::STT_PUBLISH]) }}">{{ trans('admin::view.publish') }}</a>
            </li>
            @endif
            @if (in_array(AdConst::STT_DRAFT, $statuses))
            <li class="{{ linkClassActive($route, AdConst::STT_DRAFT) }}">
                <a href="{{ route($route, ['status' => AdConst::STT_DRAFT]) }}">{{ trans('admin::view.label_draft') }}</a>
            </li>
            @endif
            @if (in_array(AdConst::STT_TRASH, $statuses))
            <li class="{{ linkClassActive($route, AdConst::STT_TRASH) }}">
                <a href="{{ route($route, ['status' => AdConst::STT_TRASH]) }}">{{ trans('admin::view.label_trash') }}</a>
            </li>
            @endif
        </ul>
    </div>
    @endif

    <div class="row">
    
        <div class="btn-actions col-sm-6 col-md-4">

            @if ($hasAddNew)
                <a href="{{ route($routePrefix.'.create', request()->all()) }}" class="create-btn btn btn-sm btn-success m-b-1" 
                   data-toggle="tooltip" title="{{ trans('admin::view.create') }}">
                    <i class="fa fa-plus"></i> <span class="">{{ trans('admin::view.create') }}</span>
                </a>
            @endif
            
            @if (in_array('draft', $multiActions) && $status == AdConst::STT_PUBLISH)
                <form method="post" action="{{ $routeActions }}" class="inline form-confirm">
                    {!! csrf_field() !!}
                    <input type="hidden" name="action" value="draft">
                    <button type="submit" class="m_action_btn btn btn-sm btn-default m-b-1"
                            data-toggle="tooltip" title="{{ trans('admin::view.draft') }}">
                        <i class="fa fa-file-text"></i> <span class="">{{ trans('admin::view.draft') }}</span>
                    </button>
                    <div class="checked_ids"></div>
                </form>
            @endif
            
            @if (in_array('draft', $multiActions) && $status == AdConst::STT_DRAFT)
                <form method="post" action="{{ $routeActions }}" class="inline form-confirm">
                    {!! csrf_field() !!}
                    <input type="hidden" name="action" value="publish">
                    <button type="submit" class="m_action_btn btn btn-sm btn-info m-b-1"
                            data-toggle="tooltip" title="{{ trans('admin::view.publish') }}">
                        <i class="fa fa-file-text"></i> <span class="">{{ trans('admin::view.publish') }}</span>
                    </button>
                    <div class="checked_ids"></div>
                </form>
            @endif
            
            @if (in_array('trash', $multiActions) && $status != AdConst::STT_TRASH)
                <form method="post" action="{{ $routeActions }}" class="inline form-confirm">
                    {!! csrf_field() !!}
                    <input type="hidden" name="action" value="trash">
                    <button type="submit" class="m_action_btn btn btn-sm btn-warning m-b-1" 
                            data-confirm="{{ trans('admin::message.are_you_sure_trash') }}" 
                            data-toggle="tooltip" title="{{ trans('admin::view.trash') }}">
                        <i class="fa fa-trash"></i> <span class="">{{trans('admin::view.delete')}}</span>
                    </button>
                    <div class="checked_ids"></div>
                </form>
            @endif

            @if (in_array('delete', $multiActions) && (!in_array('trash', $multiActions) || $status == AdConst::STT_TRASH))
                <form method="post" action="{{ $routeActions }}" class="inline form-confirm">
                    {!! csrf_field() !!}
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" class="m_action_btn btn btn-sm btn-danger m-b-1" 
                            data-confirm="{{ trans('admin::message.are_you_sure_delete_forever') }}"
                            data-toggle="tooltip" title="{{ trans('admin::view.delete') }}">
                        <i class="fa fa-trash"></i> <span class="">{{ trans('admin::view.delete_forever') }}</span>
                    </button>
                    <div class="checked_ids"></div>
                </form>
            @endif

            @if (in_array('trash', $multiActions) && $status == AdConst::STT_TRASH)
                <form method="post" action="{{ $routeActions }}" class="inline form-confirm">
                    {!! csrf_field() !!}
                    <input type="hidden" name="action" value="restore">
                    <button type="submit" class="m_action_btn btn btn-sm btn-primary m-b-1" 
                            data-confirm="{{ trans('admin::message.are_you_sure_restore') }}"
                            data-toggle="tooltip" title="{{ trans('admin::view.restore') }}">
                        <i class="fa fa-mail-reply"></i> <span class="">{{ trans('admin::view.restore') }}</span>
                    </button>
                    <div class="checked_ids"></div>
                </form>
            @endif

        </div>
        
        <div class="col-sm-6 col-md-8 text-right btn-filters">
            <form method="get" action="{{ request()->url() }}" class="inline">
                @if ($status)
                <input type="hidden" name="status" value="{{ $status }}">
                @endif
                <button type="submit" class="btn-reset-filter btn btn-primary"><i class="fa fa-refresh"></i> {{ trans('admin::view.reset_filter') }}</button>
            </form>
            <button type="button" class="btn-search-filter btn btn-primary" 
                    data-status="{{ $status }}"
                    data-url="{{ request()->url() }}">
                <i class="fa fa-filter"></i> {{ trans('admin::view.search') }}
            </button>
        </div>
        
    </div>
        
</div>

