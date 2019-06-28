<?php
$canEditComment = canDo('edit_comment', $comment->author_id);
?>

<li @if ($comment->parent_id)
    class="comment-item comment-child-item" data-parent="{{ $comment->parent_id }}"
    @else
    class="comment-item"
    @endif data-id="{{ $comment->id }}">
    <div class="inner media">
        <div class="media-left comment-avatar mr-3">
            {!! $comment->author ? $comment->author->getAvatar(42) : getDefaultAvatar(42) !!}
        </div>
        <div class="media-body">
            <h4 class="comment-author-name">
                {{ $comment->author ? $comment->author->name : $comment->author_name }}
                <span class="comment-date">{{ $comment->created_at->format('H:i d-m-Y') }}</span>
                <div class="comment-actions">
                    @if ($canEditComment)
                    <button type="button" class="edit-comment-btn btn btn-info btn-sm" title="{{ trans('front::view.edit') }}"
                            data-url="{{ route('front::comment.edit', ['id' => $comment->id]) }}">
                        <i class="fa fa-edit"></i>
                    </button>
                    @endif
                    @if (canDo('remove_comment', $comment->author_id))
                    <button type="button" class="del-comment-btn btn btn-danger btn-sm" title="{{ trans('front::view.delete') }}"
                            data-url="{{ route('front::comment.delete', ['id' => $comment->id]) }}">
                        <i class="fa fa-trash"></i>
                    </button>
                    @endif
                </div>
            </h4>
            <div class="comment-item-content" data-id="{{ $comment->id }}">
                <div class="comment-item-show">{{ $comment->content }}</div>
                @if ($canEditComment)
                <div class="comment-item-edit"></div>
                @endif
            </div>
            <div class="comment-item-foot">
                @if (!$comment->parent_id)
                <a href="{{ route('front::comment.load', ['post_id' => $comment->post_id, 'parent_id' => $comment->id]) }}" class="comment-reply">
                    {{ trans('front::view.reply') }} (<span class="count-reply">{{ $comment->childs_count ? $comment->childs_count : 0 }}</span>)
                </a>
                @endif
            </div>
        </div>
    </div>
    <ul class="comment-childs hidden"></ul>
    <div class="icon-load-comment text-center hidden"><i class="fa fa-spin fa-refresh"></i></div>
    <div class="more-comment-box hidden text-center">
        <a href="{{ route('front::comment.load', ['post_id' => $comment->post_id, 'parent_id' => $comment->parent_id]) }}">
            {{ trans('front::view.load_more_comment') }}
        </a>
    </div>
</li>
