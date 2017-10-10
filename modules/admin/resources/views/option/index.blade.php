@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_options'))

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
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th width="30"><input type="checkbox" name="massdel" class="check_all"/></th>
                <th>{{trans('admin::view.name')}} {!! linkOrder('option_key') !!}</th>
                <th>{{trans('admin::view.value')}} {!! linkOrder('value') !!}</th>
                <th>{{trans('admin::view.language')}} {!! linkOrder('lang_code') !!}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if(!$items->isEmpty())
                @foreach($items as $item)
                <tr>
                    <td><input type="checkbox" name="check_items[]" class="check_item" value="{{ $item->option_id }}" /></td>
                    <td>{{ $item->option_key }}</td>
                    <td>
                        <span class="value">{{$item->value}}</span>
                    </td>
                    <td>{{ $item->lang_code ? $item->lang_code : 'NULL' }}</td>
                    <td>
                        <a href="{{route('admin::option.edit', ['id' => $item->option_id])}}" 
                           class="btn btn-sm btn-info" 
                           data-toggle="tooltip" 
                           title="{{trans('admin::view.edit')}}"><i class="fa fa-edit"></i></a>
                    </td>
                </tr>
                @endforeach
            @else
            <tr>
                <td colspan="5"><h4 class="text-center">{{trans('admin::message.no_item')}}</h4></td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

@stop


