<?php

namespace Admin\Composers;

use App\Models\Lang;

class LangComposer{
    
    protected $lang;

    public function __construct(Lang $lang) {
        $this->lang = $lang;
    }

    public function compose($view){
        $langs = $this->lang->getData(['fields' => ['code', 'name']]);
        $view->with('langs', $langs);
    }
    
}