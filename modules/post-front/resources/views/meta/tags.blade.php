@if (!$tags->isEmpty())
<?php
if (!isset($tagClass)) {
    $tagClass = 'tags-list';
}
?>
<div class="{{ $tagClass }}">
    @foreach ($tags as $tag)
        <a href="{{ $tag->getLink('tag') }}">
            <i class="fa fa-tag"></i> 
            <span>{{ $tag->name }}</span>
        </a>
    @endforeach
</div>
@endif