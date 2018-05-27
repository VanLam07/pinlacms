<hr />
<div class="comment-container">
    
    <ul class="nav nav-tabs comment-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active comment-title" data-toggle="tab" href="#cm_pinlaz" role="tab">{{ trans('front::view.comment') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link comment-title" data-toggle="tab" href="#cm_facebook" role="tab">Facebook</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane show active" id="cm_pinlaz" role="tabpanel">
            <div class="comment-body" data-post="{{ $post->id }}"
                data-url="{{ route('front::comment.load', ['post_id' => $post->id, 'parent_id' => 0]) }}">
               <ul class="comment-lists">

               </ul>
               <div class="icon-load-comment text-center hidden" id="icon_load_parent"><i class="fa fa-spin fa-refresh"></i></div>
               <div class="more-comment-box hidden" id="more_comment_parent">
                   <a href="#" class="btn btn-secondary btn-block">{{ trans('front::view.load_more_comment') }}</a>
               </div>
           </div>

           @if (auth()->check())

           <?php $currentUser = auth()->user(); ?>
           <div class="comment-box" id="comment_template">
               {!! Form::open(['method' => 'post', 'route' => 'front::comment.add', 'class' => 'form-add-comment']) !!}
                   <p class="form-error hidden text-red"></p>
                   <textarea class="form-control comment-content" name="content" rows="2">{{ old('content') }}</textarea>
                   <div class="comment-foot">
                       <div class="row">
                           <div class="col-6 comment-auhor">
                               {!! $currentUser->getAvatar(24) !!}
                               {{ auth()->user()->name }}
                           </div>
                           <div class="col-6 text-right">
                               <input type="hidden" name="post_id" value="{{ $post->id }}">
                               <input type="hidden" name="parent_id" value="">
                               <button type="submit" class="btn btn-primary">{{ trans('front::view.comment') }}</button>
                           </div>
                       </div>
                   </div>
               {!! Form::close() !!}
           </div>

           <div class="comment-box hidden" id="comment_edit_template">
               {!! Form::open(['method' => 'put', 'route' => 'front::comment.update', 'class' => 'form-edit-comment']) !!}
                   <textarea class="form-control comment-content" name="content" rows="2"></textarea>
                   <p class="form-error hidden text-red"></p>
                   <div class="comment-foot">
                       <div class="row">
                           <div class="col-6 comment-auhor">
                               {!! $currentUser->getAvatar(24) !!}
                               {{ auth()->user()->name }}
                           </div>
                           <div class="col-6 text-right">
                               <input type="hidden" name="comment_id" value="">
                               <button type="button" class="cancel-edit-comment-btn btn btn-secondary">{{ trans('front::view.cancel') }}</button>
                               <button type="submit" class="btn btn-primary">{{ trans('front::view.edit') }}</button>
                           </div>
                       </div>
                   </div>
               {!! Form::close() !!}
           </div>
           @else
           <p>
               <a href="{{ route('front::account.login') }}">{{ trans('front::view.login_to_comment') }}</a>
           </p>
           @endif
        </div>
        
        <div class="tab-pane" id="cm_facebook" role="tabpanel">
            <div class="fb-comments" data-width="100%" data-href="{{ $post->getLink() }}" data-numposts="10"></div>
        </div>
    </div>
    
</div>
