<?php

namespace App\Facades\Access;

use App\Models\Cap;
use Admin\Facades\AdConst;
use Illuminate\Support\Facades\Auth;

class Access {

    protected $cap;

    public function __construct(Cap $cap) {
        $this->cap = $cap;
    }

    public function can($cap) {
        if (!Auth::check()) {
            return false;
        }
        $user = Auth::user();
        $userId = $user->id;

        //switch caps
        $args = array_slice(func_get_args(), 1);
        $author = $args ? $args[0] : null;
        $userCaps = $user->caps();
        if (!array_key_exists($cap, $userCaps)) {
            return false;
        }
        $level = $userCaps[$cap];
        if ($author && $level == AdConst::CAP_SELF) {
            return $userId == $author;
        }
        return true;
    }

    public function check() {
        $args = func_get_args();
        if (!call_user_func_array([$this, 'can'], $args)) {
            abort(403, trans('admin::view.authorize'));
        }
    }

}
