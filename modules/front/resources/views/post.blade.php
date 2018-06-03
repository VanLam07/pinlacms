@extends('front::layouts.default')

@section('title', $post->title)

@section('keywords', $post->meta_keyword)
@section('description', $post->meta_desc)
@section('og_image', asset($post->getThumbnailSrc('large', false)))

@section('head')
<link rel="stylesheet" href="/plugins/datetimepicker/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" href="/plugins/prism/prism.css">
@stop

@section('comment_script')
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v3.0&appId=174580299920381&autoLogAppEvents=1';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
@stop

@section('content_col')

<div class="post-container">

    <h2 class="page-title">{{ $post->title }}</h2>
    
    <div class="page-head">
        <div class="row">
            <div class="col-5">
                @include('front::meta.social-share')
            </div>
            <div class="col-7 post-meta text-right">
                <span class="date"><i class="fa fa-calendar"></i> {{ $post->created_at->format('d-m-Y') }}</span>
                <span class="author"><i class="fa fa-user"></i> {{ $post->authorName() }}</span>
                <span class="view"><i class="fa fa-eye"></i> {{ (int) $post->views }}</span>
            </div>
        </div>
    </div>

    <div class="post-content">
        {!! $post->content !!}
    </div>
    
    @if (canDo('edit_post', $post->author_id))
    <p><a href="{{route('admin::post.edit', ['id' => $post->id])}}" target="_blank"
          title="{{trans('admin::view.edit')}}"><i class="fa fa-pencil"></i> {{ trans('admin::view.edit') }}</a></p>
    @endif
    
    @if ($post->is_notify)
    <div class="card notify-content mgb-30">
        <div class="card-body">
            <h5>{{ trans('front::view.notify_calendar') }}</h5>
            
            {!! showMessage() !!}
            
            {!! Form::open(['method' => 'post', 'route' => ['front::post.save_mail_notify', $post->id]]) !!}
            <div class="form-group">
                <label>{{ trans('front::view.email') }} <em>*</em></label>
                <input name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') ? old('email') : ($dataNotify ? $dataNotify->email : null) }}">
                {!! errorField('email') !!}
            </div>
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label>{{ trans('front::view.from_date') }}</label>
                    <input type="text" name="from_date" value="{{ old('from_date') ? old('from_date') : ($dataNotify ? $dataNotify->from_date : null) }}" class="form-control date_picker">
                </div>
                <div class="col-sm-6 form-group">
                    <label>{{ trans('front::view.to_date') }}</label>
                    <input type="text" name="to_date" value="{{ old('to_date') ? old('to_date') : ($dataNotify ? $dataNotify->to_date : null) }}" class="form-control date_picker">
                </div>
                <div class="col-sm-12">
                    {!! errorField('to_date') !!}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 form-group">
                    <label>{{ trans('front::view.from_hour') }}</label>
                    <input type="text" name="from_hour" value="{{ old('from_hour') ? old('from_hour') : ($dataNotify ? $dataNotify->from_hour : null) }}" class="form-control time_picker">
                </div>
                <div class="col-sm-4 form-group">
                    <label>{{ trans('front::view.to_hour') }}</label>
                    <input type="text" name="to_hour" value="{{ old('to_hour') ? old('to_hour') : ($dataNotify ? $dataNotify->to_hour : null) }}" class="form-control time_picker">
                </div>
                <div class="col-sm-4 form-group">
                    <label>{{ trans('front::view.number_alert') }}</label>
                    <input type="number" min="0" max="90" name="number_alert" value="{{ old('number_alert') ? old('number_alert') : ($dataNotify ? $dataNotify->number_alert : null) }}" class="form-control">
                </div>
            </div>
            <div class="form-group text-center">
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('front::view.save') }}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    @endif
    
    <div class="page-foot">
        <div class="post-tax post-cats">
            @include('front::meta.categories', ['categories' => $post->getCats])
        </div>
        <div class="post-taxs post-tags">
            @include('front::meta.tags', ['tags' => $post->getTags])
        </div>
    </div>
    
</div>

<?php 
$relatedPosts = $post->getRelated();
?>
@if (!$relatedPosts->isEmpty())
<div class="box related-box">
    <h3 class="sub-title bd-title">{{ trans('front::view.view_more') }}</h3>
    <ul class="posts related-posts">
        @foreach ($relatedPosts as $post)
        <li>
            <a href="{{ $post->getLink() }}">{{ $post->title }}</a>
        </li>
        @endforeach
    </ul>
</div>
@endif

@include('front::includes.comments')

@stop

@section('foot')

<script src="/js/moment.min.js"></script>
<script src="/plugins/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script src="/plugins/prism/prism.js"></script>
<script >
    (function ($) {
        $('.date_picker').datetimepicker({
            viewMode: 'days',
            format: 'YYYY-MM-DD'
        });
        
        $('.time_picker').datetimepicker({
            format: 'HH:mm'
        });
    })(jQuery);
</script>

@stop