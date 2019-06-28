<?php

namespace Admin\Composers;

use PlLocale;

class LangComposer{
    
    public function compose($view){
        $langs = PlLocale::all();
        $currentLang = PlLocale::getCurrent();
        $view->with('langs', $langs)
                ->with('currentLang', $currentLang);
    }
    
}