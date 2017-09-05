<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>@yield('title', 'Account')</title>
        
        <link rel="stylesheet" href="/public/css/bootstrap.min.css">
        <link rel="stylesheet" href="/public/css/font-awesome.min.css">
        
        <script src="/public/js/jquery.min.js"></script>
    </head>
    <body>
        <header>
            
        </header>
        
        <section id="main_body">
            <div class="container">
                <br />
                <br />
                <br />
                @yield('content')
            </div>
        </section>
        
        <footer>
            
        </footer>
        
        <script src="/public/js/tether.min.js"></script>
        <script src="/public/js/popper.min.js"></script>
        <script src="/public/js/bootstrap.min.js"></script>
    </body>
</html>
