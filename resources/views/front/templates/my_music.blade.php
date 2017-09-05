@extends('layouts.frontend')

@section('keyword', $page->meta_keyword)
@section('description', $page->meta_desc)

@section('title', $page->title)

@section('head')
<link rel="stylesheet" href="/public/css/player.css">
@stop

@section('body_attr', 'ng-app="ngMusic"')

@section('content_col3')

<div class="_wrapper _music_box">

    <h2 class="_page_title">{{trans('front.music')}}</h2>
    
    <section ng-controller="musicController" class="ui_content" ui-view>
        
    </section>

</div>

@stop

@section('foot')

@include('front.includes.script_vars')

<script src="/public/js/jquery-ui.min.js"></script>
<script src="/public/plugins/angular/angular.min.js"></script>
<script src="/public/plugins/angular/angular-route.min.js"></script>
<script src="/public/plugins/angular/angular-ui-router.min.js"></script>
<script src="/public/ngapp/controllers/music_controller.js"></script>
<script src="/public/ngapp/services/ondrive_service.js"></script>
<script src="/public/ngapp/music.js"></script>
<script>

//    (function($) {
//        $.ajax({
//           url: 'https://api.onedrive.com/v1.0/shares/s!Aipl9SsCBTTtiMYmwsgC_dTZaCo-fw/root?expand=children',
//           type: 'GET',
//           success: function (data) {
//               var childrens = data.children;
//               for (var i in childrens) {
//                   var item = childrens[i];
//                   $('.list_folder').append('<p><a href="https://api.onedrive.com/v1.0/shares/s!Aipl9SsCBTTtiMYmwsgC_dTZaCo-fw/items/'+item.id+'/children">' + item.name + '</a></p>');
//               }
//               console.log(data);
//           }
//        });
//    })(jQuery);

</script>

@stop

