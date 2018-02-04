<div class="inner-sidebar">
    <?php
    $cats = PlTax::listCategories();
    ?>

    @if (!$cats->isEmpty())
    <div class="wrap-header bd-pattern">
        <span>{{ trans('front::view.category') }}</span>
    </div>
    <div class="wrap">
        <ul class="list-categories">
            @foreach ($cats as $cat)
            <li><a href="{{ $cat->getlink() }}">{{ $cat->name }}</a></li>
            @endforeach
        </ul>
    </div>
    @endif

    <?php
    $postViews = PlPost::getMostViews();
    ?>

    @if (!$postViews->isEmpty())
    <div class="wrap-header bd-pattern">
        <span>{{ trans('front::view.most_view') }}</span>
    </div>
    <div class="wrap">
        <div class="posts media-posts">
            @foreach ($postViews as $post)
            <div class="post post-media">
                @include('front::includes.post-media', ['hasView' => 1])
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>