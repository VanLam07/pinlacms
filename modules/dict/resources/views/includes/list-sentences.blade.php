@if (isset($sentences) && !$sentences->isEmpty())
<div class="comment-body">
    <ul class="comment-lists">
    @foreach($sentences as $sentence)
    <?php
    $canEditSentence = canDo('edit_sentence', $sentence->user_id);
    ?>
    <li class="comment-item" data-id="{{ $sentence->id }}">
        <div class="inner media">
            <div class="media-left comment-avatar mr-3">
                {!! $sentence->author ? $sentence->author->getAvatar(42) : getDefaultAvatar(42) !!}
            </div>
            <div class="media-body">
                <h4 class="comment-author-name">
                    {{ $sentence->user_name ? $sentence->user_name : 'Anonymous' }}
                    <span class="comment-date">{{ $sentence->created_at->format('H:i d-m-Y') }}</span>
                    <div class="comment-actions">
                        @if ($canEditSentence)
                        <button type="button" class="edit-comment-btn btn btn-info btn-sm" title="{{ trans('front::view.edit') }}"
                                data-url="{{ route('dict::word.edit_sentence', ['id' => $sentence->id]) }}">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button type="button" class="del-comment-btn btn btn-danger btn-sm" title="{{ trans('front::view.delete') }}"
                                data-url="{{ route('dict::word.delete_sentence', ['id' => $sentence->id]) }}">
                            <i class="fa fa-trash"></i>
                        </button>
                        @endif
                    </div>
                </h4>
                <div class="comment-item-content" data-id="{{ $sentence->id }}">
                    <div class="comment-item-show">{{ $sentence->sentence }}</div>
                    @if ($canEditSentence)
                    <div class="comment-item-edit"></div>
                    @endif
                </div>
            </div>
        </div>
    </li>
    @endforeach
    </ul>
</div>
@else
<p class="text-center">{{ trans('dict::view.none_sentence') }}</p>
@endif

@if (auth()->check())
<div class="comment-box hidden" id="comment_edit_template">
    {!! Form::open(['method' => 'put', 'route' => 'dict::word.update_sentence', 'class' => 'form-edit-comment']) !!}
        <textarea class="form-control comment-content" name="sentence" rows="2"></textarea>
        <p class="form-error hidden text-red"></p>
        <div class="comment-foot">
            <div class="row">
                <div class="col-6 comment-auhor">
                    {!! auth()->user()->getAvatar(24) !!}
                    {{ auth()->user()->name }}
                </div>
                <div class="col-6 text-right">
                    <input type="hidden" name="id" value="">
                    <button type="button" class="cancel-edit-comment-btn btn btn-secondary">{{ trans('front::view.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ trans('front::view.update') }}</button>
                </div>
            </div>
        </div>
    {!! Form::close() !!}
</div>
@endif