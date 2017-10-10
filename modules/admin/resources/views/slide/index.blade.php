@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_slides'))

<?php 
use Admin\Facades\AdConst;

$multiActions = ['delete'];
$statuses = [];
$actionCaps = [];
$page_title = ($slider) ? $slider->name. ' / '. trans('admin::view.man_slides') : trans('admin::view.man_slides');
?>

@section('nav_status')
    @include('admin::parts.actions-nav')
@stop

@section('content')

{!! showMessage() !!}

<p><strong>{{ trans('admin::view.sliders') }}: {{ $page_title }}</strong></p>

<div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th width="30"><input type="checkbox" name="massdel" class="check_all"/></th>
                <th>ID {!! linkOrder('id') !!}</th>
                <th>{{trans('admin::view.thumbnail')}}</th>
                <th>{{trans('admin::view.name')}} {!! linkOrder('thumb_url') !!}</th>
                <th>{{trans('admin::view.created_at')}} {!! linkOrder('created_at') !!}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if (!$items->isEmpty())
                @foreach($items as $item)
                <tr>
                    <td><input type="checkbox" name="checked[]" class="check_item" value="{{ $item->id }}" /></td>
                    <td>{{$item->id}}</td>
                    <td><img width="50" src="{{$item->getThumbnailSrc('thumbnail')}}"></td>
                    <td>{{$item->name}}</td>
                    <td>{{$item->created_at}}</td>
                    <td>
                        <a href="{{route('admin::slide.edit', ['id' => $item->id, 'slider_id' => $slider_id])}}" 
                           class="btn btn-sm btn-info" title="{{trans('admin::view.edit')}}"><i class="fa fa-edit"></i></a>
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

