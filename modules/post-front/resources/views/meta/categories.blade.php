@if (!$categories->isEmpty())
<?php
if (!isset($ulClass)) {
    $ulClass = 'cats-list';
}
?>
<div class="{{ $ulClass }}">
    @foreach ($categories as $cat)
        <a href="{{ $cat->getLink() }}"><i class="fa fa-folder"></i> <span>{{ $cat->name }}</span></a>
    @endforeach
</div>
@endif

