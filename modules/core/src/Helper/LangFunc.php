<?php

// Languages

if (!function_exists('langCodes')) {
    function langCodes() {
        return PlLocale::allCodes();
    }
}

if (!function_exists('hasLang')) {
    function hasLang($code) {
        return PlLocale::has($code);
    }
}

if (!function_exists('switchLangUrl')) {
    function switchLangUrl($locale) {
        $request = request();
        if (!hasLang($locale)) {
            $locale = config('app.fallback_locale');
        }
        app()->setLocale($locale);
        $segments = $request->segments();
        $segments[0] = $locale;
        return '/'.implode('/', $segments);
    }
}

if (!function_exists('localeActive')) {
    function localeActive($code, $active = 'active') {
        $current_code = app()->getLocale();
        if ($code == $current_code) {
            return $active;
        }
        return null;
    }
}

if (!function_exists('langActive')) {
    function langActive($code, $active = 'active'){
        $lang = currentLocale();
        $request = request();
        if($request->has('lang')){
            $lang = $request->get('lang');
        }
        if($code == $lang){
            return $active;
        }
        return null;
    }
}

if (!function_exists('getLangs')) {
    function getLangs($args = []) {
        if (!$args) {
            $args = [
                'fields' => ['code', 'name', 'icon'],
                'per_page' => -1
            ];
        }
        return PlLocale::all($args);
    }
}

function current_lang() {
    return Local::getCurrent();
}

function current_lang_id() {
    return Local::getCurrent()->id;
}

if (!function_exists('currentLocale')) {
    function currentLocale() {
        return app()->getLocale();
    }
}

function get_lang_id($code){
    return Local::getId($code);
}

function get_lang($code, $fields=['*']){
    return Local::findById($code, $fields);
}

