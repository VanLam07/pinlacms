<?php

namespace App\Facades\Access;

use App\Models\Cap;

class Access {

    protected $cap;

    public function __construct(Cap $cap) {
        $this->cap = $cap;
    }

    public function can($cap) {
        if (!auth()->check()) {
            return false;
        }
        $user = auth()->user();
        $user_id = $user->id;

        //switch caps
        
        $args = array_slice(func_get_args(), 1);
        $author = $args ? $args[0] : null;
        if ($user->hasCaps($cap)) {
            if ($author && $user_id == $author) { 
                return true;
            }
            return true;
        }
        return $user->hasCaps(str_replace('_my_', '_other_', $cap.'s'));
    }

    public function check() {
        $args = func_get_args();   
        $can = call_user_func_array([$this, 'can'], $args);
        if (!$can) {
            abort(403, trans('auth.authorize'));
        }
    }

}
