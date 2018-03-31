<div class="thumb">
    <?php
    $thumbnail = $post->getThumbnail(isset($thumbSize) ? $thumbSize : 'medium');
    $postLink = $post->getLink();
    ?>
    <a href="{{ $postLink }}">
        {!! $thumbnail !!}
    </a>
</div>

<div class="post-content">
    <div class="media">
        @if (isset($postDate))
        <div class="media-left mr-3">
            <div class="post-date">
                <span class="day">{{ $post->created_at->day }}</span>
                <span class="month">{{ $post->created_at->format('M') }}</span>
            </div>
        </div>
        @endif
        
        <div class="media-body">
            <h3 class="post-title"><a href="{{ $postLink }}">{{ $post->title }}</a></h3>
            <div class="post-meta">
                @if (isset($postDate))
                    <span class="date"><i class="fa fa-calendar"> {{ $post->created_at->format('Y') }}</i></span>
                @else
                    <span class="date"><i class="fa fa-calendar"> {{ $post->created_at->format('d-m-Y') }}</i></span>
                @endif
                <span class="author"><i class="fa fa-user"></i> {{ $post->authorName() }}</span>
                <span class="view"><i class="fa fa-eye"></i> {{ (int) $post->views }}</span>
            </div>
            <div class="post-excerpt">
                {!! $post->getExcerpt(isset($postDate) ? 30 : 15) !!}
            </div>
            <div class="post-foot">
            </div>
        </div>
    </div>
</div>