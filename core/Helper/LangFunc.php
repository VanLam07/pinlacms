<?php

// Languages
function has_lang($code) {
    return Local::has($code);
}

function switch_lang_url($locale) {
    $request = request();
    if (!has_lang($locale)) {
        $locale = config('app.fallback_locale');
    }
    app()->setLocale($locale);
    $segments = $request->segments();
    $segments[0] = $locale;
    return '/'.implode('/', $segments);
}

function locale_active($code, $active = 'active') {
    $current_code = app()->getLocale();
    if ($code == $current_code) {
        return $active;
    }
    return null;
}

function lang_active($code, $active='active'){
    $lang = current_locale();
    $request = request();
    if($request->has('lang')){
        $lang = $request->get('lang');
    }
    if($code == $lang){
        return $active;
    }
    return null;
}

function get_langs($args=['fields' => ['id', 'code', 'name', 'icon']]) {
    return Local::all($args);
}

function current_lang() {
    return Local::getCurrent();
}

function current_lang_id() {
    return Local::getCurrent()->id;
}

function current_locale() {
    return app()->getLocale();
}

function get_lang_id($code){
    return Local::getId($code);
}

function get_lang($code, $fields=['*']){
    return Local::findById($code, $fields);
}

