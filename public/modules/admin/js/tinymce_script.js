(function ($) {
    if(typeof tinymce != "undefined"){
        tinymce.init({
            selector: '.editor_content',
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor codesample ex_loadfile"
            ],
            image_advtab: true,
            relative_urls : 0,
            remove_script_host : 0,
            toolbar: "insertfile undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor | codesample ex_loadfile",
            ex_loadfile: {
                title: "Danh sách tệp tin",
                filepath: file_dialog_url
            }
        });
    }
    
    $('.btn-popup-files').click(function(e){
        $('#files-frame').attr('src', $(this).attr('frame-url'));
    });
    
    $('body').on('click', '.btn-remove-file', function(e){
        e.preventDefault();
        $(this).closest('.thumb_item').find('.img_box').html('');
        $(this).closest('.thumb_item').find('input, textarea').val('');
        $(this).remove();
    });

})(jQuery);


