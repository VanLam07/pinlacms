<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>@yield('title', 'Welcome') - {{ PlOption::get('blog_title', app()->getLocale()) }}</title>
        @yield('head')
    </head>

    <body>
        @yield('content')
        
        <hr />
        
        <p style="font-size: 13px;">
            Thực sự không muốn như thế này đâu, nhưng nễu lỡ không thích rồi thì đừng vui lòng mà hãy một chút do dự bấn nút này nhé! <a href="{{ $unsubsLink }}">Hủy đăng ký</a>
        </p>
    </body>
</html>
