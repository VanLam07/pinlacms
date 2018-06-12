<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/font-awesome.min.css">
        <link rel="stylesheet" href="/css/filemanager.css">
        
        <script src="/js/jquery.min.js"></script>
    </head>

    <body class="file_dialog">
        
        <div id="main_dialog">
            <div class="container-fluid">
                
                <ul class="nav nav-tabs files-tab" role="tablist">
                    <li class="active"><a class="nav-link tab-upload-files" href="#upload-files-tab" role="tab" data-toggle="tab">{{trans('file.upload')}}</a></li>
                    <li class=""><a class="nav-link tab-select-files" href="#select-files-tab" role="tab" data-toggle="tab">{{trans('file.select_files')}}</a></li>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="upload-files-tab">
                        {!! Form::open(['method' => 'post', 'route' => 'admin::file.store', 'files' => true]) !!}
                        <div class="form-group">
                            <button type="button" class="btn-choose-files btn btn-default">
                                <i class="fa fa-upload"></i> {{trans('admin::view.choose_files')}}
                                <i class="loading hidden fa fa-spin fa-refresh"></i>
                                {!! Form::file('files[]', ['id' => "files-input", 'multiple']) !!}
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <div role="tabpanel" class="tab-pane" id="select-files-tab">
                        <ul class="list-inline files-list">

                        </ul>
                        <div class="file-paginate text-center">
                            <a class="btn-more-files" href="">{{ trans('file.load_more') }} <i class="fa fa-spin fa-refresh hidden"></i></a>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        <div id="footer_dialog">
            <span class="btn btn-default"><span class="num_selected">0</span> {{trans('file.is_selected')}}</span>
            <button type="button" class="btn btn-default btn-close-dialog"><i class="fa fa-close"></i> {{trans('file.close')}}</button>
            <button type="button" class="btn btn-primary btn-editor-submit-files"><i class="fa fa-check"></i> {{trans('file.submit_selected')}}</button>
        </div>
        
        <script src="/js/bootstrap.min.js"></script>

        <script>
            
            var _token = "{{csrf_token()}}";
            var multi_select = 0;
            var file_type = '_all';
            var el_preview = '.thumb_group';
            var thumbSize = 0;
            var fileName = 'file_ids';
            var isAppend = 0;
            var callback_function = null;
            @if(isset($params['callback']))
                callback_function = "{{ $params['callback'] }}";
            @endif
            @if(isset($params['multiple']))
                multi_select = "{{$params['multiple']}}";
            @endif
            @if(isset($params['file_type']))
                file_type = "{{$params['file_type']}}";
            @endif
            @if(isset($params['el_preview']))
                el_preview = "{{$params['el_preview']}}";
            @endif
            @if (isset($params['thumb_size']))
                thumbSize = 1;
            @endif
            @if (isset($params['file_name']))
                fileName = "{{ $params['file_name'] }}";
            @endif
            @if (isset($params['append']))
                isAppend = "{{ $params['append'] }}";
            @endif
        
            var _all_files_url = "{{route('admin::api.ajax_action')}}";
            
            var file_tabs = $('.files-tab');
            var el_files_list = $('.files-list');
            var files_selected_count = $('.num_selected');
            var files_selected = [];
            var btnMoreFile = $('.btn-more-files');
            var iconLoadFile = btnMoreFile.find('i');
            
            (function ($) {
                
                if (el_files_list.data('loaded') != true) {
                    loadFiles(_all_files_url);
                }
                
                function loadFiles(url){
                    if (el_files_list.hasClass('loading')) {
                        return;
                    }
                    el_files_list.addClass('loading');
                    iconLoadFile.removeClass('hidden');
                    $.ajax({
                        url: url,
                        type: 'GET',
                        data: {
                            action: 'load_files',
                            type: file_type
                        },
                        success: function (data) {
                            el_files_list.append(data.html);
                            btnMoreFile.attr('href', data.next_page_url);
                            if (!data.next_page_url) {
                                btnMoreFile.addClass('hidden');
                            }
                            el_files_list.attr('data-loaded', true);
                        },
                        complete: function () {
                            el_files_list.removeClass('loading');
                            iconLoadFile.addClass('hidden');
                        }
                    });
                };
                
                btnMoreFile.click(function(e) {
                    e.preventDefault();
                    var href = $(this).attr('href');
                    if (!href) {
                        return;
                    }
                    loadFiles(href);
                });
                
                $('#files-input').click(function () {
                    $(this).val('');
                });
                $('#files-input').change(function () {
                    var loading = $('.loading');
                    loading.removeClass('hidden'); 
                    var formData = new FormData();
                    var files = $(this)[0].files;
                    for (var i = 0; i < files.length; i++) {
                        formData.append('files[]', files[i]);
                    }
                    var form = $(this).closest('form');
                    formData.append('_token', _token);
                    var _this = $(this);
                    _this.prop('disabled', true);
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            if (data.length > 0) {
                                file_tabs.find('.tab-select-files').click();
                                files_selected = [];
                                el_files_list.find('li a').removeClass('selected');
                                for (var i = 0; i < data.length; i++) {
                                    var file = data[i];
                                    el_files_list.prepend(
                                                '<li>' + 
                                                    '<a href="' + file.full_url + '" data-id="' + file.id + '">' +
                                                        '<img class="img-responsive" src="' + file.thumb_url + '" alt="' + file.name + '">' +
                                                    '</a>' +
                                                '</li>'
                                            );
                                    el_files_list.find('li a[data-id="'+ file.id +'"]').trigger('click');
                                }
                            }
                            $('#files-input').val('');
                        },
                        error: function (err) {
                            console.log(err);
                        },
                        complete: function () {
                            loading.addClass('hidden');
                            _this.prop('disabled', false);
                        }
                    });
                });

                $('body').on('click', '.files-list li a', function (e) {
                    e.preventDefault();
                    var file_id = $(this).data('id');
                    var file_url = $(this).attr('href');
                    var thumbUrl = $(this).find('img').attr('src');
                    var file = {id: file_id, url: file_url, thumb_url: thumbUrl};
                    if (multi_select == 1) {
                        var index = check_selected(file, files_selected);
                        if ($(this).hasClass('selected')) {
                            $(this).removeClass('selected');
                            if (index > -1) {
                                files_selected.splice(index, 1);
                            }
                        } else {
                            $(this).addClass('selected');
                            if (index === -1) {
                                files_selected.push(file);
                            }
                        }
                    } else {
                        if ($(this).hasClass('selected')) {
                            $(this).removeClass('selected');
                            files_selected = [];
                        } else {
                            el_files_list.find('li a').removeClass('selected');
                            $(this).addClass('selected');
                            files_selected = [file];
                        }
                    }
                    files_selected_count.text(files_selected.length);
                });
                
                var args = null;
                if (typeof top.tinymce != "undefined" && top.tinymce.activeEditor != null) {
                    args = top.tinymce.activeEditor.windowManager.getParams();
                }
                $('.btn-editor-submit-files').click(function (e) {
                    e.preventDefault();
                    if (args) {
                        var editor = args.editor;
                        for (var i in files_selected) {
                            var file = files_selected[i];
                            var img_content = '<p><img src="'+file.url+'" alt="image" style="max-width: 100%;"></p>';
                            editor.insertContent(img_content);
                        }
                        top.tinymce.activeEditor.windowManager.close();
                    } else {
                        if (callback_function) {
                            window.parent[callback_function](files_selected);
                        } else {
                            window.parent.submitSelectFiles(files_selected, el_preview, thumbSize, fileName, isAppend);
                        }
                        window.parent.closeFileModal();
                    }
                });

                $('.btn-close-dialog').click(function (e) {
                    e.preventDefault();
                    if (args) {
                        top.tinymce.activeEditor.windowManager.close();
                    } else {
                        window.parent.closeFileModal();
                    }
                });
                
            })(jQuery);
            
            function check_selected(file, files) {
                for (var i in files) {
                    if (files[i].id === file.id) {
                        return i;
                    }
                }
                return -1;
            }
        </script>

    </body>
</html>
