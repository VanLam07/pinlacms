@extends('admin::layouts.manage')

<?php
$search = request()->get('search');
$title = trans('admin::view.search');
if ($search) {
    $title = trans('admin::view.search_for', ['search' => $search]);
}
?>
@section('title', $title)

@section('content')

<div class="box box-primary">
    <div class="box-body">
        <h3 class="box-title">{{ trans('admin::view.posts') }}</h3>
        @if (!$posts->isEmpty())
        <ul>
            @foreach ($posts as $post)
            <li><a href="{{ route('admin::post.edit', ['id' => $post->id]) }}">{{ $post->title }}</a></li>
            @endforeach
        </ul>
        {!! $posts->links() !!}
        @else
        <p>{{ trans('admin::message.no_items') }}</p>
        @endif
    </div>
</div>

<div class="box box-primary">
    <div class="box-body">
        <h3 class="box-title">{{ trans('admin::view.pages') }}</h3>
        @if (!$pages->isEmpty())
        <ul>
            @foreach ($pages as $page)
            <li><a href="{{ route('admin::page.edit', ['id' => $page->id]) }}">{{ $page->title }}</a></li>
            @endforeach
        </ul>
        {!! $pages->links() !!}
        @else
        <p>{{ trans('admin::message.no_items') }}</p>
        @endif
    </div>
</div>

<div class="box box-primary">
    <div class="box-body">
        <h3 class="box-title">{{ trans('admin::view.categories') }}</h3>
        @if (!$cats->isEmpty())
        <ul>
            @foreach ($cats as $cat)
            <li><a href="{{ route('admin::cat.edit', ['id' => $cat->id]) }}">{{ $cat->name }}</a></li>
            @endforeach
        </ul>
        {!! $cats->links() !!}
        @else
        <p>{{ trans('admin::message.no_items') }}</p>
        @endif
    </div>
</div>

<div class="box box-primary">
    <div class="box-body">
        <h3 class="box-title">{{ trans('admin::view.tags') }}</h3>
        @if (!$tags->isEmpty())
        <ul>
            @foreach ($tags as $tag)
            <li><a href="{{ route('admin::tag.edit', ['id' => $tag->id]) }}">{{ $tag->name }}</a></li>
            @endforeach
        </ul>
        {!! $tags->links() !!}
        @else
        <p>{{ trans('admin::message.no_items') }}</p>
        @endif
    </div>
</div>

@stop

