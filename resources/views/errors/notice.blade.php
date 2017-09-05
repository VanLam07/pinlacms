<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>
            @if (isset($title))
                {{ $title }}
            @else
                @lang('view.notice')
            @endif
        </title>

        <link rel="stylesheet" href="/public/css/bootstrap.min.css">
    </head>
    
    <body>
        <div id="app">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 ml-auto mr-auto">
                        
                        <br />
                        <br />
                        <br />
                        <h1>{{ isset($tittle) ? $title : trans('view.notice') }}</h1>
                        
                        @if (isset($message))
                        <p class="alert alert-danger">
                            {!! $message !!}
                        </p>
                        @endif
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
