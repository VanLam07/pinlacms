<?php

namespace App\Facades\Access;

use Admin\Facades\AdConst;
use Illuminate\Support\Facades\Auth;

class Access {

    public function can($cap) {
        if (!Auth::check()) {
            return false;
        }
        $user = Auth::user();
        $userId = $user->id;
        
        //switch caps
        $args = array_slice(func_get_args(), 1);
        $author = $args ? $args[0] : null;
        $levelParam = isset($args[1]) ? $args[1] : null;
        $userCaps = $user->caps();
        if (!array_key_exists($cap, $userCaps) 
                || $userCaps[$cap] === AdConst::CAP_NONE) {
            return false;
        }
        $level = $userCaps[$cap];
        if ($author) {
            if ($levelParam) {
                if ($levelParam == AdConst::CAP_OTHER) {
                    return true;
                } else if ($levelParam == AdConst::CAP_SELF) {
                    return ($userId == $author);
                }
            } else if ($level == AdConst::CAP_SELF) {
                return ($userId == $author);
            }
        }
        if ($levelParam) {
            return ($levelParam <= $level);
        }
        return true;
    }

    public function check() {
        $args = func_get_args();
        if (!call_user_func_array([$this, 'can'], $args)) {
            if (request()->ajax() || request()->wantsJson()) {
                echo trans('admin::view.authorize');
                die();
            }
            abort(403, trans('admin::view.authorize'));
        }
    }

}
