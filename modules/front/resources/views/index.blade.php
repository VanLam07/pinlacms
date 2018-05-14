@extends('front::layouts.default')

@section('title', trans('front::view.home_page'))

@section('content_col')

<?php
$featurePosts = PlPost::getFeature();
?>

@if (!$featurePosts->isEmpty())

<div class="box box-feature">
    <!--<h2 class="box-title">{{ trans('front::view.feature_posts') }}</h2>-->
    <div class="posts feature-posts">
        <div class="post first-post post-col">
            @include('front::includes.post-col', ['post' => $featurePosts->first(), 'postDate' => 1, 'thumbSize' => 'large'])
        </div>
        
        <?php unset($featurePosts[0]); ?>
        <div class="row">
            @foreach ($featurePosts as $index => $post)
            <div class="col-6 post post-col">
                <div class="inner">
                    @include('front::includes.post-col')
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endif


<?php
$latestPosts = PlPost::getLatest();
?>
@if (!$latestPosts->isEmpty())

<div class="box box-latest">
    <h2 class="box-title bd-pattern"><span>{{ trans('front::view.Lastest posts') }}</span></h2>

    <div class="posts latest-posts">
        @foreach ($latestPosts as $post)
        <div class="post post-row">
            <div class="row">
                @include('front::includes.post-row')
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="paginate-box">
        {!! $latestPosts->links() !!}
    </div>
    
</div>

@endif

@stop

