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
        
        <meta name="keywords" content="@yield('keywords', PlOption::get('blog_keywords'))">
        <meta name="description" content="@yield('description', PlOption::get('blog_description'))">

        <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,900&amp;subset=latin-ext,vietnamese" rel="stylesheet">

        <link rel="shortcut icon" href="/favicon.png">
        
        <link rel="stylesheet" href="/bootstrap4/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/font-awesome.min.css">
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/screen.css">

        @yield('head')
    </head>

    <body class="@yield('body_class')">
        <header>
            @include('front::includes.header')
        </header>

        <section id="body_content">

            <div id="top_page">
                @yield('top_page')
            </div>

            <div class="container">
                @yield('content_full')
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-sm-8 content-col">
                        @yield('content_col')
                    </div>

                    <div class="col sidebar-col">
                        @yield('sidebar_col', view('front::includes.sidebar'))
                    </div>
                </div>
            </div>

        </section>

        <footer>
            @include('front::includes.footer')
        </footer>

        <script>
            var _token = '{{ csrf_token() }}';
            var globMess = {
                confirm: '<?php echo trans('front::message.are_you_sure'); ?>'
            };
        </script>
        
        <script src="/js/jquery.min.js"></script>
        <script src="/js/popper.min.js"></script>
        <script src="/bootstrap4/js/bootstrap.min.js"></script>
        <script src="/js/bootbox.min.js"></script>
        <script src="/js/main.js"></script>

        @yield('foot')
    </body>
</html>