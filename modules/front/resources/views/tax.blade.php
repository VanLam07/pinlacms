@extends('front::layouts.default')

@section('title', $tax->name)

@section('keywords', $tax->meta_keyword)
@section('description', $tax->meta_desc)

@section('content_full')
{!! Breadcrumb::render() !!}
@stop

@section('content_col')

<?php
use Admin\Facades\AdConst;

$pageTitle = '';
if ($tax->isTag()) {
    $pageTitle = trans('front::view.tag') . ': ';
}
?>

<h2 class="page-title bd-title mgb-30">
    <span class="text-uppercase">{{ $pageTitle . $tax->name }}</span>
</h2>

@if (!$posts->isEmpty())
<?php
$firstPost = $posts->first();
?>
<div class="posts">
    @if ($firstPost->post_format == AdConst::FORMAT_QUOTE)
    <div class="row">
        @foreach ($posts as $post)
        <div class="col-md-6">
        @include('front::includes.post-quote', ['bgColor' => AdConst::randBgPost()])
        </div>
        @endforeach
    </div>
    @else
        @foreach ($posts as $post)
            @include('front::includes.post-row')
        @endforeach
    @endif
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

