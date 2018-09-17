<div class="inner-sidebar">
    <?php
    $quotePage = PlOption::get('quote_page');
    ?>
    @if ($quotePage)
    <a href="{{ route('front::page.view', ['id' => $quotePage, 'slug' => 'quote-daily']) }}" class="btn-reg btn-reg-primary mgb-20">
        {{ trans('front::view.one_day_one_proverb') }}
    </a>
    @endif
    
    <?php
    $genWordPage = PlPost::getTemplatePage('generate-word');
    ?>
    @if ($genWordPage)
    <a href="{{ route('front::page.view', ['id' => $genWordPage->id, 'slug' => $genWordPage->slug]) }}" class="btn-reg btn-reg-primary mgb-20">
        {{ trans('front::view.make_english_sentence') }}
    </a>
    @endif

    <?php
    $cats = PlTax::listCategories(1);
    $currUrl = request()->url();
    ?>

    @if (!$cats->isEmpty())
    <div class="wrap-header bd-pattern">
        <span>{{ trans('front::view.category') }}</span>
    </div>
    <div class="wrap">
        <ul class="list-categories">
            @foreach ($cats as $cat)
            <?php $catLink = $cat->getlink(); ?>
            <li {!! $currUrl == $catLink ? 'class="active"' : null !!}><a href="{{ $catLink }}" title="{{ $cat->name }}">{{ $cat->name }}</a></li>
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
            <?php $tagLink = $tag->getLink('tag'); ?>
            <a {!! $currUrl == $tagLink ? 'class="active"' : null !!} href="{{ $tagLink }}" title="{{ $tag->name }}">{{ $tag->name }}</a>
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