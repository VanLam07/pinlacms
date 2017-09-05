<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=divice-width, initial-scale=1">
        
        <meta name="keyword" content="@yield('keyword', 'lamdev')">
        <meta name="description" content="@yield('description', 'lamdev.net')">

        <title>@yield('title', 'Home')</title>

        <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700,900&subset=latin,vietnamese,latin-ext' rel='stylesheet' type='text/css'>

        @yield('head')
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/font-awesome.min.css">
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/screen.css">

        <script src="/js/jquery-3.1.0.min.js"></script>
        <script>
            var _token = '{{ csrf_token() }}';
        </script>

    </head>
    <body @yield('body_attr')>

        <header id="header">
            @include('front.parts.header')
        </header>

        <section id="main_body">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        @yield('content_col2')
                    </div>
                    <div class="col-md-4">
                        @yield('right_col2')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 _left_col">
                        @yield('left_col3')
                    </div>
                    <div class="col-md-7 _main_content">
                        @yield('content_col3')
                    </div>
                    <div class="col-md-3 _right_col">
                        @yield('right_col3')
                    </div>
                </div>
            </div>
        </section>

        <footer id="footer">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 text-xs-right">&copy; lamdev.net</div>
                    <div class="col-sm-6">Designed by <a href="#">Pinla</a></div>
                </div>
            </div>
        </footer>

        <script src="/js/tether.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/main.js"></script>

        @yield('foot')
        
    </body>
</html>

