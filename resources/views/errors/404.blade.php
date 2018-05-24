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
            <div class="car-container">
                <img class="car" src="/images/animate/car.png">
                <img class="wheel-front car-wheel" src="/images/animate/wheel.png">
                <img class="wheel-behihe car-wheel" src="/images/animate/wheel.png">
            </div>
        </div>
        <p style="font-size: 30px; text-align: center; font-weight: 600;">Error 404 - page not found</p>
        <p style="text-align: center;"><a href="{{URL('/')}}">Go home</a></p>
    </body>
</html>
