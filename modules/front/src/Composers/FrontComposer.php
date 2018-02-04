<?php

namespace Front\Composers;

use PlLocale;
use PlOption;
use Front\Helper\FtConst;

class FrontComposer{
    
    public function compose($view){
        $langs = PlLocale::all();
        $currentLang = PlLocale::getCurrent();
        
        $frontPerPage = PlOption::get('front_per_page', FtConst::PER_PAGE);
        
        $currentUser = auth()->user();
        
        $view->with('langs', $langs)
                ->with('currentLang', $currentLang)
                ->with('currentUser', $currentUser);
    }
    
}