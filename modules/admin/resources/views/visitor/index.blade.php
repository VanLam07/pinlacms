@extends('admin::layouts.manage')

@section('title', trans('admin::view.visitors'))

@section('content')

{!! showMessage() !!}

<div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>ID {!! linkOrder('id') !!}</th>
                <th>IP {!! linkOrder('ip') !!}</th>
                <th>Locale {!! linkOrder('lang') !!}</th>
                <th>Agent</th>
                <th>Visited at</th>
            </tr>
        </thead>
        <tbody>
            @if (!$items->isEmpty())
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->ip }}</td>
                    <td>{{ $item->lang }}</td>
                    <td>{{ $item->agent }}</td>
                    <td>{{ $item->created_at }}</td>
                </tr>
                @endforeach
            @else
            <tr>
                <td colspan="5"><h4 class="text-center">{{ trans('admin::message.not_found_items') }}</h4></td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

@include('admin::parts.paginate')

@stop

