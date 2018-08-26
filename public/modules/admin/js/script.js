(function ($) {
    
    var plStorage = {
        getItem: function (key) {
            if (typeof Storage == 'undefined') {
                return null;
            }
            return localStorage.getItem(key);
        },
        setItem: function (key, value) {
            if (typeof Storage == 'undefined') {
                return null;
            }
            return localStorage.setItem(key, value);
        }
    };
    
    var keyToggleSidebar = 'is_toggle_sidebar';
    var toggleSidebar = plStorage.getItem(keyToggleSidebar);
    if (parseInt(toggleSidebar)) {
        $('body').addClass('sidebar-collapse');
    } else {
        $('body').removeClass('sidebar-collapse');
    }
    
    $('.sidebar-toggle').click(function () {
        plStorage.setItem(keyToggleSidebar, $('body').hasClass('sidebar-collapse') ? 0 : 1);
    });
    
    $('body').on('submit', 'form.form-confirm', function (e) {
        if ($('.check_item:checked').length < 1) {
            bootbox.alert('None item checked!');
            return false;
        }
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
                        $('.check_item:checked').each(function () {
                            inputHtml += '<input type="hidden" name="item_ids[]" value="'+ $(this).val() +'">'
                        });
                        form.find('.checked_ids').html(inputHtml);
                    }
                   orginalForm.submit();
               }
            });
        }
        return false;
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
    
    
    $('.new_tags').select2({
        tags: true
    });
    $('.av_tags').select2();

    $('.lang-tabs li a').click(function (e) {
        var mce_iframe = $('.mce-edit-area iframe');
        var height = mce_iframe.height();
        mce_iframe.css('height', height);
    });

    $('.value').click(function () {
        $(this).addClass('hidden-xs-up');
        $(this).next('input').removeClass('hidden-xs-up');
    });

    $('.date_picker').datetimepicker({
        viewMode: 'days',
        format: 'YYYY-MM-DD'
    });

    $('.time_picker').datetimepicker({
        format: 'HH:mm'
    });

    $('.date_time_picker').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss'
    });
    
})(jQuery);


