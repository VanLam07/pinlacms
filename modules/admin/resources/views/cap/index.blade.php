@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_caps'))

@section('content')

{!! showMessage() !!}

@include('admin::parts.actions-nav', [
    'actionBtns' => [
                    'add' => route('admin::cap.create'),
                    'delete' => route('admin::cap.actions')
                ]
    ])

<div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th width="30"><input type="checkbox" name="massaction" class="check_all"/></th>
                <th>{{ trans('admin::view.name') }}</th>
                <th>{{ trans('admin::view.description') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td>
                    <input type="text" name="filters[]" placeholder="@lang('admin::view.search')" class="form-control">
                </td>
                <td></td>
                <td></td>
            </tr>
            @if (!$items->isEmpty())
                @foreach($items as $item)
                <tr>
                    <td><input type="checkbox" name="checked[]" class="check_item" value="{{ $item->name }}" /></td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->label }}</td>
                    <td>
                        <a href="{{route('admin::cap.edit', ['id' => $item->name])}}" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="{{trans('manage.edit')}}"><i class="fa fa-edit"></i></a>
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4">@lang('admin::message.not_found_items')</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

@stop

