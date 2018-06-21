<div class="inner-sidebar">
    <?php
    $cats = PlTax::listCategories(1);
    ?>

    @if (!$cats->isEmpty())
    <div class="wrap-header bd-pattern">
        <span>{{ trans('front::view.category') }}</span>
    </div>
    <div class="wrap">
        <ul class="list-categories">
            @foreach ($cats as $cat)
            <li><a href="{{ $cat->getlink() }}" title="{{ $cat->name }}">{{ $cat->name }}</a></li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <?php
    $listTags = PlTax::listTagsCloud();
    ?>
    @if (!$listTags->isEmpty())
    <div class="tags-box mgb-15">
        <h3 class="sub-title bd-title"><span class="text-uppercase">Tags</span></h3>
        <div class="tags-list">
            @foreach ($listTags as $tag)
            <a href="{{ $tag->getLink() }}" title="{{ $tag->name }}">{{ $tag->name }}</a>
            @endforeach
        </div>
    </div>
    @endif

    <?php
    $postViews = PlPost::getMostViews(5);
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

    @include('front::includes.social', ['imageIcon' => true])
</div>