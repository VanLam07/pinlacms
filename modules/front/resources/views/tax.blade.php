@extends('front::layouts.default')

@section('title', $tax->name)

@section('keywords', $tax->meta_keyword)
@section('description', $tax->meta_desc)

@section('content_col')

<?php
$pageTitle = '';
if ($tax->isTag()) {
    $pageTitle = trans('front::view.tag') . ': ';
}
?>

<h2 class="page-title bd-title mgb-30">
    <span class="text-uppercase">{{ $pageTitle . $tax->name }}</span>
</h2>

@if (!$posts->isEmpty())
<div class="posts">
    @foreach ($posts as $post)
        @include('front::includes.post-row')
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

