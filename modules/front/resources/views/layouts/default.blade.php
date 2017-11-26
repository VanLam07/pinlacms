<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>@yield('title', 'Welcome') - PlOption::get('blog_title')</title>
        
        <link rel="stylesheet" href="/bootstrap4/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/font-awesome.min.css">
    
        @yield('head')
    </head>
    
    <body class="@yield('body_class')">
        <header>
            <?php
            PlMenu::getMenuItems('primary-menu');
            ?>
            @include('front::includes.header')
        </header>
        
        <section id="body_content">
            <div id="top_page">
                @yield('top_page')
            </div>
            
            <div class="container">
                @yield('content_full')
            </div>
            
            <div class="row">
                <div class="col-sm-8">
                    @yield('content_col')
                </div>
                
                <div class="col">
                    @yield('sidebar_col')
                </div>
            </div>
        </section>
        
        <footer>
            
        </footer>
        
        <script src="/js/jquery.min.js"></script>
        <script src="/js/popper.min.js"></script>
        <script src="/bootstrap4/js/bootstrap.min.js"></script>
        
        @yield('foot')
    </body>
</html>
