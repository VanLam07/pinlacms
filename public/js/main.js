(function ($) {

    $('.navbar li.active').each(function () {
        $(this).closest('li.dropdown').addClass('active');
    });

    $('.comment-body').each(function () {
        var _this = $(this);
        var url = _this.data('url');
        var commentList = $('.comment-lists');
        var iconLoading = $('.icon-load-comment');
        var btnLoadBox = $('#more_comment_parent');

        loadMoreComments(url, commentList, iconLoading, btnLoadBox);
    });

    $('body').on('click', '.comment-reply', function (e) {
        e.preventDefault();
        var _this = $(this);
//        var count = parseInt(_this.find('.count-reply').text());
//        if (count === 0) {
//            return;
//        }
        var commentItem = $(this).closest('.comment-item');
        var parentId = commentItem.data('id');

        var commentBox = commentItem.find('.comment-box');
        $('.comment-item .comment-box').not(commentBox).remove();
        if (commentBox.length < 1) {
            commentBox = $('#comment_template').clone().removeAttr('id');
            commentBox.find('input[name="parent_id"]').val(parentId);
            commentBox.appendTo(commentItem);
        }
        
        if (_this.hasClass('loaded')) {
            return;
        }
        _this.addClass('loaded');
        var url = $(this).attr('href');
        var commentList = commentItem.find('.comment-childs');
        var iconLoading = commentItem.find('.icon-load-comment');
        var btnLoadBox = commentItem.find('.more-comment-box');
        
        loadMoreComments(url, commentList, iconLoading, btnLoadBox);
    });
    
    $('body').on('click', '.more-comment-box a', function (e) {
        e.preventDefault();
        var commentItem = $(this).closest('.comment-item');
        var url = $(this).attr('href'), commentList, iconLoading, btnLoadBox;
        if (commentItem.length > 0) {
            commentList = commentItem.find('>.comment-childs');
            iconLoading = commentItem.find('>.icon-load-comment');
            btnLoadBox = commentItem.find('>.more-comment-box');
        } else {
            commentList = $('.comment-lists');
            iconLoading = $('#icon_load_parent');
            btnLoadBox = $('#more_comment_parent');
        }
        
        loadMoreComments(url, commentList, iconLoading, btnLoadBox);
    });
    
    $('body').on('submit', '.comment-box .form-add-comment', function () {
        var form = $(this);
        if (form.find('.comment-content').val().trim() == ''){
            form.find('.comment-content').val('');
            return false;
        }
        var btn = $(this).find('button[type="submit"]');
        if (btn.is(':disabled')) {
            return false;
        }
        
        var commentItem = form.closest('.comment-item');
        var commentList = $('.comment-lists');
        if (commentItem.length > 0) {
            commentList = commentItem.find('>.comment-childs');
        }
        
        var formError = form.find('.form-error');
        formError.addClass('hidden');
        btn.prop('disabled', true);
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function (results) {
                form.find('.comment-content').val('');
                commentList.append(results.comment);
                if (commentItem.length > 0) {
                    var reply = commentItem.find('.count-reply');
                    reply.text(parseInt(reply.text()) + 1);
                }
            },
            error: function () {
                formError.text('Error! try again laster!').removeClass('hidden');
            },
            complete: function () {
                btn.prop('disabled', false);
            }
        });
        return false;
    });
    
    $('body').on('click', '.del-comment-btn', function (e) {
        e.preventDefault();
        var btn = $(this);
        bootbox.confirm({
            message: globMess.confirm,
            className: 'modal-warning',
            callback: function (result) {
                if (result) {
                    btn.prop('disabled', true);
                    var commentItem = btn.closest('.comment-item');
                    commentItem.addClass('processing');
                    $.ajax({
                        type: 'delete',
                        url: btn.data('url'),
                        data: {
                            _token: _token
                        },
                        success: function () {
                            commentItem.remove();
                            var parentId = commentItem.data('parent');
                            if (typeof parentId != 'undefined') {
                                var parentItem = $('.comment-item[data-id="'+ parentId +'"]');
                                if (parentItem.length > 0) {
                                    var reply = parentItem.find('.count-reply');
                                    reply.text(parseInt(reply.text()) - 1);
                                }
                            }
                        },
                        complete: function () {
                            btn.prop('disabled', false);
                            commentItem.removeClass('processing');
                        }
                    });
                }
            }
        });
    });
    
    $('body').on('click', '.edit-comment-btn', function (e) {
        e.preventDefault();
        var btn = $(this);
        if (btn.is(':disabled')) {
            return;
        }
        if (btn.hasClass('loaded')) {
            return;
        }
        btn.prop('disabled', true);
        var commentItem = btn.closest('.comment-item');
        var commentId = commentItem.attr('data-id');
        var commentEdit = commentItem.find('div[data-id="'+ commentId +'"] .comment-item-edit');
        if (commentEdit.length < 1) {
            return;
        }
        btn.closest('.comment-container').find('.comment-item-edit').html('');
        btn.closest('.comment-container').find('.edit-comment-btn').removeClass('loaded');
        btn.closest('.comment-container').find('.comment-item').removeClass('comment-editting');
        var commentBody = commentEdit.closest('.modal-body');
        commentBody.addClass('processing');
        $.ajax({
            type: 'GET',
            url: btn.data('url'),
            success: function (comment) {
                if (!comment) {
                    return;
                }
                var formEdit = $('#comment_edit_template').clone().removeAttr('id');
                formEdit.find('.comment-content').text(comment.content);
                formEdit.find('input[name="comment_id"]').val(comment.id);
                commentEdit.html(formEdit[0].outerHTML);
                setTimeout(function () {
                    commentEdit.find('.comment-box').removeClass('hidden');
                    commentItem.addClass('comment-editting');
                }, 100);
                btn.addClass('loaded');
            },
            error: function (error) {
                bootbox.alert({
                   message: error.responseJSON || 'Error!',
                   className: 'modal-danger'
                });
            },
            complete: function () {
                btn.prop('disabled', false);
                commentBody.removeClass('processing');
            }
        });
    });
    
    $('body').on('click', '.cancel-edit-comment-btn', function (e) {
        e.preventDefault();
        $(this).closest('.media-body').find('.edit-comment-btn').removeClass('loaded');
        $(this).closest('.comment-box').remove();
    });
    
    $('body').on('submit', '.comment-box .form-edit-comment', function () {
        var form = $(this);
        var btn = form.find('button[type="submit"]');
        if (btn.is(':disabled')) {
            return;
        }
        var commentItem = form.closest('.comment-item');
        commentItem.addClass('processing');
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function (comment) {
                commentItem.removeClass('comment-editting');
                form.closest('.comment-item-content').find('.comment-item-show').text(comment.content);
                form.closest('.media-body').find('.edit-comment-btn').removeClass('loaded');
            },
            error: function (error) {
                bootbox.alert({
                    message: error.responseJSON || 'Error!',
                    className: 'modal-danger'
                });
            },
            complete: function () {
                btn.prop('disabled', false);
                commentItem.removeClass('processing');
            }
        });
        return false;
    });
    
    function loadMoreComments(url, commentList, iconLoading, btnLoadBox)
    {
        iconLoading.removeClass('hidden');
        $.ajax({
            type: 'GET',
            url: url,
            success: function (data) {
                var commentHtml = data.comments;
                var hasMore = data.has_more;
                commentList.removeClass('hidden');
                commentList.append(commentHtml);
                
                if (hasMore) {
                    btnLoadBox.removeClass('hidden');
                    btnLoadBox.find('a').attr('href', data.next_page_url);
                } else {
                    btnLoadBox.addClass('hidden');
                }
                iconLoading.addClass('hidden');
            }
        });
    }
    
    $(document).ready(function() {
        var elVisitor = $('#count_visitor');
        setTimeout(function () {
            $.ajax({
                type: 'GET',
                url: elVisitor.data('url'),
                success: function (count) {
                    elVisitor.text(count);
                }
            });
        }, 3000);
    });

    var $allVideos = $("iframe[src^='//www.youtube.com']");
    var $fluidEl = $(".post-content");

    $allVideos.each(function() {
      $(this).data('aspectRatio', this.height / this.width)
        // and remove the hard coded width/height
        .removeAttr('height')
        .removeAttr('width');
    });

    $(window).resize(function() {
      var newWidth = $fluidEl.width();
      $allVideos.each(function() {
        var $el = $(this);
        $el.width(newWidth).height(newWidth * $el.data('aspectRatio'));
      });
    }).resize();
    
    $('#form_make_rand_word').submit(function () {
        var form = $(this);
        var btn = form.find('button[type="submit"]');
        var loading = btn.find('.loading');
        var wordBox = form.find('.word-box');
        if (btn.is(':disabled')) {
            return false;
        }
        loading.removeClass('hidden');
        btn.prop('disabled', true);
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
            success: function (word) {
                if (!word) {
                    alert('Something went wrong!');
                    return;
                }
                wordBox.find('.main-word').text(word.word);
                var wordDesc = wordBox.find('.word-desc');
                var type = wordBox.find('.type');
                if (type.length < 1) {
                    wordDesc.prepend('<span class="type">'+ word.type +'</span>');
                } else {
                    type.text(word.type);
                }
                var pronun = wordBox.find('.pronun');
                if (pronun.length < 1) {
                    wordDesc.prepend('<span class="pronun">'+ word.pronun +'</span>');
                } else {
                    pronun.text(word.pronun);
                }
                $('#mean_box .card-body').html(word.mean);
                $('#input_word_id').val(word.id);
                var hrefCheck = $('#check_sentence_link').data('href');
                $('#check_sentence_link').attr('href', hrefCheck + '/' + word.word + '?direct_search_result=yes');
                $('#view_word_link').attr('href', word.link);
            },
            error: function (error) {
                bootbox.alert({
                    className: 'modal-danger',
                    message: 'Something was wrong!'
                });
            },
            complete: function () {
                btn.prop('disabled', false);
                loading.addClass('hidden');
            }
        });
        return false;
    });
    
    $('a[href="#mean_box"]').click(function () {
        var _this = $(this);
        setTimeout(function () {
            if (!_this.hasClass('collapsed')) {
                _this.text(_this.attr('text-hide'));
            } else {
                _this.text(_this.attr('text-show'));
            }
        }, 100);
    });

})(jQuery);
