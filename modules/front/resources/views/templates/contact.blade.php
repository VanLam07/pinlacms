@extends('front::layouts.default')

@if ($page)

@section('title', $page->title)

@section('content_col')

<div class="page-container">
    
    <h2 class="page-title bd-title">{{ $page->title }}</h2>
    
    <div class="page-content mgt-50">
        
        {!! showMessage() !!}
        
        {!! Form::open(['method' => 'post', 'route' => 'front::contact.send']) !!}

        <div class="form-group">
            <label>{{ trans('front::view.fullname') }} <em>*</em></label>
            <input type="text" value="{{ old('fullname') }}" name="fullname" class="form-control" placeholder="{{ trans('front::view.fullname') }}">
            {!! errorField('fullname') !!}
        </div>
        
        <div class="form-group">
            <label>{{ trans('front::view.email') }} <em>*</em></label>
            <input type="email" value="{{ old('email') }}" name="email" class="form-control" placeholder="{{ trans('front::view.email') }}">
            {!! errorField('email') !!}
        </div>
        
        <div class="form-group">
            <label>{{ trans('front::view.subject') }} <em>*</em></label>
            <input type="text" value="{{ old('subject') }}" name="subject" class="form-control" placeholder="{{ trans('front::view.subject') }}">
            {!! errorField('subject') !!}
        </div>
        
        <div class="form-group">
            <label>{{ trans('front::view.content') }} <em>*</em></label>
            <textarea name="content" class="form-control" rows="3" placeholder="{{ trans('front::view.content') }}">{{ old('content') }}</textarea>
            {!! errorField('content') !!}
        </div>
        
        <div class="form-group text-center">
            <button class="btn btn-primary"><i class="fa fa-send"></i> {{ trans('front::view.send_contact') }}</button>
        </div>
        
        {!! Form::close() !!}
        
    </div>
    
</div>

@stop

@endif
