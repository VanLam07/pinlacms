<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{{ trans('admin::message.reset_pass_subject', ['host' => request()->getHost()]) }}</title>
    </head>
    <body>
        <h3>{{ trans('admin::message.reset_pass_subject', ['host' => request()->getHost()]) }}</h3>
        
        <p>{!! trans('admin::message.reset_pass_content', ['host' => request()->getHost(), 'route' => route('front::account.get_reset_pass', ['token' => $token])]); !!}</p>
    </body>
</html>
