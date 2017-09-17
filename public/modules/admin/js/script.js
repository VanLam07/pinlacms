(function ($) {
    
    $('body').on('submit', 'form.form-confirm', function (e) {
        e.preventDefault();
        var button = $(this).find('button[type="submit"]');
        var orginalForm = this;
        var form = $(this);
        if (button.length > 0) {
            var message = 'Are you sure?';
            if (typeof button.attr('data-confirm') != 'undefined') {
                message = button.data('confirm');
            }
            bootbox.confirm(message, function (result) {
                if (result) {
                    if (form.find('.checked_ids').length > 0) {
                        var inputHtml = '';
                        $('.check_item').each(function () {
                            if ($(this).is(':checked')) {
                                inputHtml += '<input type="hidden" name="item_ids[]" value="'+ $(this).val() +'">'
                            }
                        });
                        form.find('.checked_ids').html(inputHtml);
                    }
                   orginalForm.submit();
               }
            });
        }
    });
    
    $('body').on('click', '.check_all', function () {
        $('.check_item').prop('checked', $(this).is(':checked'));
    });
    
    $('body').on('click', '.check_item', function () {
        var checkedLen = $('.check_item:checked').length;
        var itemLen = $('.check_item').length;
        $('.check_all').prop('checked', (checkedLen === itemLen));
    });
    
    $('.filter-data').on('keypress', function (e) {
        if (e.keyCode == $.ui.keyCode.ENTER) {
            $('.btn-search-filter').trigger('click');
        }
    });
    
    $('.btn-search-filter').on('click', function (e) {
        e.preventDefault();
        var elThis = $(this);
        var originPath;
        if (typeof elThis.attr('data-url') != 'undefined' && elThis.data('url')) {
            originPath = elThis.data('url');
        } else {
            var location = window.location;
            originPath = location.origin + location.pathname;
        }
        var form = $('<form method="get" action="'+ originPath +'"></form>');
        $('.filter-data').each(function () {
            if ($(this).val()) {
                form.append($(this).clone());
            }
        });
        if (typeof elThis.attr('data-status') != 'undefined' && elThis.data('status')) {
            form.append('<input type="hidden" name="status" value="'+ elThis.data('status') +'">');
        }
        $('body').append(form);
        form.submit();
        form.remove();
    });
    
    
//    $('.new_tags').select2({
//        tags: true
//    });
//    $('.av_tags').select2();

    $('.lang-tabs li a').click(function (e) {
        var mce_iframe = $('.mce-edit-area iframe');
        var height = mce_iframe.height();
        mce_iframe.css('height', height);
    });

    $('.value').click(function () {
        $(this).addClass('hidden-xs-up');
        $(this).next('input').removeClass('hidden-xs-up');
    });

    //file modal popup
    $('.btn-files-modal').click(function (e) {
        e.preventDefault();
        $('#files-modal').modal('show');
        var href = $(this).data('href');
        var files_content = $('#files-modal .modal-body');
        if (!files_content.hasClass('loaded')) {
            files_content.html('<iframe class="files-frame" frameborder="0" src="' + href + '"></iframe>');
            files_content.addClass('loaded');
        }
    });

    window.closeFileModal = function () {
        $('#files-modal').modal('hide');
    };

    window.submitSelectFiles = function (files, el_preview) {
        var preview_html = '';
        for (var i in files) {
            var file = files[i];
            preview_html += '<p class="file_item">' +
                    '<img src="' + file.url + '" class="img-fluid" alt="" title="">' +
                    '<a class="f_close"></a>' +
                    '<input type="hidden" name="file_ids[]" value="' + file.id + '">' +
                    '</p>';
        }
        $(el_preview).html(preview_html);
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

