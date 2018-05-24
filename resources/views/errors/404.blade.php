<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Error 404 - Page not found</title>
        <link rel="shortcut icon" href="/favicon.png">
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/screen.css">
        <style>
            body, html {
                margin: 0;
                padding: 0;
            }
        </style>
    </head>
    
    <body>
        <div style="text-align: center; padding-top: 20vh;" class="skyline">
            <!--<img style="display: inline-block; max-width: 100%; height: auto;" src="/images/404_not_found.gif" alt="404">-->
            <div class="car-container" style="margin-right: 30%;">
                <img class="smoke" src="/images/animate/smoke.png">
                <img class="car" src="/images/animate/car.png">
                <img class="wheel-front car-wheel" src="/images/animate/wheel.png">
                <img class="wheel-behihe car-wheel" src="/images/animate/wheel.png">
            </div>
            <img class="img-minion" src="/images/animate/minion.gif">
            <div class="text-said">
                <span class="text-1">{{ trans('front::view.dead_end') }}</span>
                <span class="text-2">{{ trans('front::view.go_back') }}</span>
                <span class="arrow"></span>
            </div>
        </div>
        <p style="font-size: 30px; text-align: center; font-weight: 600; color: #d13030;">Error 404 - page not found</p>
        <p style="text-align: center;"><a class="btn-go-home" href="{{URL('/')}}">Go home</a></p>
    </body>
</html>
