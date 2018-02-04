@extends('front::layouts.default')

@section('title', $tax->name)

@section('content_col')

<?php
$pageTitle = trans('front::view.category');
if ($tax->isTag()) {
    $pageTitle = trans('front::view.tag');
}
?>

<h2 class="page-title bd-title mgb-30">
    <span class="text-uppercase">{{ $pageTitle . ': ' . $tax->name }}</span>
</h2>

@if (!$posts->isEmpty())
<div class="posts">
    @foreach ($posts as $post)
    <div class="post post-row">
        <div class="row">
            @include('front::includes.post-row')
        </div>
    </div>
    @endforeach
</div>
@endif

<?php
$postPaginate = $posts->links();
?>
@if ($postPaginate)
<div class="paginate-box">
    {!! $postPaginate !!}
</div>
@endif

@stop

