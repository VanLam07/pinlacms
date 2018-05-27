<?php

namespace Front\Composers;

class FrontComposer{

    public function compose($view){
        $currentUser = auth()->user();
        $view->with('currentUser', $currentUser);
    }

}