(function ($) {
    
     //file modal popup
    $('.btn-files-modal').click(function (e) {
        e.preventDefault();
        $('#files-modal').modal('show');
        var href = $(this).data('href');
        var files_content = $('#files-modal .modal-body');
        if (!files_content.hasClass('loaded')) {
            files_content.html('<iframe class="files-frame" frameborder="0" src="' + href + '"></iframe>');
            files_content.addClass('loaded');
        } else {
            var oldHref = files_content.find('iframe').attr('src');
            if (href !== oldHref) {
                files_content.find('iframe').attr('src', href);
            }
        }
    });

    window.closeFileModal = function () {
        $('#files-modal').modal('hide');
    };

    window.submitSelectFiles = function (files, el_preview, thumbSize, fileName, append) {
        if (typeof thumbSize == 'undefined') {
            thumbSize = 0;
        }
        if (typeof fileName == 'undefined') {
            fileName = 'file_ids';
        }
        if (typeof append == 'undefined') {
            append = 0;
        }
        var preview_html = '';
        for (var i in files) {
            var file = files[i];
            var fileUrl = file.url;
            if (thumbSize) {
                fileUrl = file.thumb_url;
            }
            if (append && $(el_preview).find('.file_item[data-id="'+ file.id +'"]').length > 0) {
                continue;
            }
            preview_html += '<p class="file_item" data-id="'+ file.id +'">' +
                    '<img src="' + fileUrl + '" class="img-responsive" alt="" title="">' +
                    '<a class="f_close"></a>' +
                    '<input type="hidden" name="'+ fileName +'['+ i +']" value="' + file.id + '">' +
                    '</p>';
        }
        if (!append) {
            $(el_preview).html(preview_html);
        } else {
            $(el_preview).append(preview_html);
        }
    };
    
    window.setOptionValue = function (files) {
        var file = files[0];
        $('.option-value').html(file.url);
    };

    $('body').on('click', '.file_item .f_close', function (e) {
        e.preventDefault();
        $(this).closest('.file_item').remove();
    });

    $('.file-input-field').change(function () {
        var files = $(this)[0].files;
        var html = '';
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            html += '<p>' + file.name + '</p>';
        }
        $('#selected_files').html(html);
    });
    
})(jQuery);
