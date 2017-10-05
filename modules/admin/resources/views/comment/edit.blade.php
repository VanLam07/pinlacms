@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_comments'))

<?php
use Admin\Facades\AdConst;
?>

@section('content')

<div class="row">
    <div class="col-sm-6">
        
        {!! showMessage() !!}

        {!! Form::open(['method' => 'put', 'route' => ['admin::comment.update', $item->id]]) !!}
        
        <div class="form-group">
            <label>{{trans('admin::view.posts')}} (*)</label>
            {!! Form::text('post_id', $item->post_id, ['class' => 'form-control', 'placeholder' => trans('admin::view.posts')]) !!}
            {!! errorField('post_id') !!}
        </div>
        
        <div class="form-group">
            <label>{{trans('admin::view.author')}}</label>
            {!! Form::text('author_id', $item->author_id, ['class' => 'form-control', 'placeholder' => trans('admin::view.author')]) !!}
        </div>
        
        <div class="form-group">
            <label>{{trans('admin::view.content')}} (*)</label>
            {!! Form::textarea('content', $item->content, ['class' => 'form-control', 'rows' => 5, 'placeholder' => trans('admin::view.content')]) !!}
            {!! errorField('content') !!}
        </div>
        
        <div class="form-group">
            <label>{{trans('admin::view.parent')}}</label>
            <select name="parent_id" class="form-control">
                <option value="">{{trans('admin::view.selection')}}</option>
                @if($parents)
                    @foreach($parents as $cm)
                    <option value="{{$cm->id}}" @if($cm->id == $item->parent_id) selected @endif>{{$cm->id}}</option>
                    @endforeach
                @endif
            </select>
        </div>
        
        <div class="form-group">
            <label>{{trans('admin::view.status')}}</label>
            {!! Form::select('status', AdView::getStatusLabel(), $item->status, ['class' => 'form-control']) !!}
        </div>
        
        <a href="{{route('admin::comment.index', ['status' => AdConst::STT_PUBLISH])}}" class="btn btn-warning">
            <i class="fa fa-long-arrow-left"></i> {{trans('admin::view.back')}}</a>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('admin::view.update')}}</button>
        
        {!! Form::close() !!}
        
    </div>
</div>

@stop

