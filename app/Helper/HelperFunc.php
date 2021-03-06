<?php

use Admin\Facades\AdConst;

define('DEFAULT_ROLE', 3);


/**
 * Check permission
 * @return type
 */
if (!function_exists('canDo')) {

    function canDo() {
        $args = func_get_args();
        return call_user_func_array(['Access', 'can'], $args);
    }

}

if (!function_exists('canAccess')) {

    function canAccess() {
        $args = func_get_args();
        return call_user_func_array(['Access', 'check'], $args);
    }

}

if (!function_exists('hasActionItem')) {
    
    function hasActionItem ($actionCaps, $item, $status = null) {
        if (!$status) {
            return false;
        }
        if ($status == AdConst::STT_TRASH) {
            return canDo($actionCaps['remove'], $item->authorId());
        }
        if ($status == AdConst::STT_DRAFT) {
            return canDo($actionCaps['edit'], $item->authorId());
        }
        return canDo($actionCaps['edit'], $item->authorId())
                || canDo($actionCaps['remove'], $item->authorId());
    }
    
}

if (!function_exists('checkAuth')) {

    function checkAuth() {
        if (!auth()->check()) {
            return redirect()->route('get_login');
        }
    }

}

if (!function_exists('rsNames')) {
    function rsNames($name) {
        return [
            'names' => [
                'index' => $name . ".index",
                'create' => $name . ".create",
                'store' => $name . ".store",
                'show' => $name . ".show",
                'edit' => $name . ".edit",
                'update' => $name . ".update",
                'destroy' => $name . ".destroy"
            ]
        ];
    }
}

if (!function_exists('requestValue')) {
    function getRequestParam($key, $keyElement = null) {
        $data = request()->get($key);
        if (!$keyElement) {
            return $data;
        }
        if (is_array($data) && isset($data[$keyElement])) {
            return $data[$keyElement];
        }
        return null;
    }
}

if (!function_exists('getDefaultAvatar')) {
    
    function getDefaultAvatar($size = 32)
    {
        return '<img src="/images/icon/user-icon.png" size="'. $size .'" class="img-responsive">';
    }
    
}

if (!function_exists('showMessage')) {

    function showMessage($txt_class = null, $box_class = null) {
        $result = '';
        if (Session::has('errors')) {
            $result = '<div class="alert alert-warning alert-dismissible border_box ' . $box_class . '">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
                    . '<div class="error_mess ' . $txt_class . '">' . trans('admin::message.error_occurred') . '</div></div>';
        }
        if (Session::has('error_mess')) {
            $result = '<div class="alert alert-warning alert-dismissible border_box ' . $box_class . '">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
                    . '<div class="error_mess ' . $txt_class . '">' . Session::get('error_mess') . '</div></div>';
            Session::forget('error_mess');
        }
        if (Session::has('succ_mess')) {
            $result = '<div class="alert alert-success alert-dismissible border_box ' . $box_class . '">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
                    . '<div class="succ_mess ' . $txt_class . '">' . Session::get('succ_mess') . '</div></div>';
            Session::forget('succ_mess');
        }
        return $result;
    }

}

/**
 * 
 * @param type $field field name
 * @param type $errors array errors
 * @return type
 */
if (!function_exists('errorField')) {

    function errorField($field) {
        $errors = Session::get('errors');
        if (count($errors) > 0) {
            if ($errors->has($field)) {
                return '<div class="help-block alert alert-danger">' . $errors->first($field) . '</div>';
            }
        }
        return '';
    }

}

/**
 * show validate message
 * @return string
 */
function validateMessage() {
    $errors = Session::get('errors');
    $resutl = '';
    if (count($errors) > 0) {
        $resutl .= '<div class="error_messes">';
        foreach ($errors->all() as $err) {
            $resutl .= '<div class="alert alert-danger">' . $err . '</div>';
        }
        $resutl .= '</div>';
    }
    return $resutl;
}

/**
 * 
 * @param type $length
 * @param type $model
 * @return type
 */
if (!function_exists('makeToken')) {
    function makeToken($length = 17, $model = null) {
        $str = str_random($length);
        if ($model) {
            $token = $model::where('resetPasswdToken', $str)->first();
            if ($token) {
                $str = makeToken($length, $model);
            }
        }
        return $str;
    }
}

if (!function_exists('linkOrder')) {
    function linkOrder($orderby) {
        $request = request();
        $route = $request->route()->getName();
        $order = 'asc';
        if ($request->has('order') && $request->has('orderby') 
                && $request->get('orderby') == $orderby) {
            $order = ($request->get('order') == 'asc') ? 'desc' : 'asc';
        }
        $args = array_merge($request->all(), ['orderby' => $orderby, 'order' => $order]);
        echo '<a href="' . route($route, $args) . '"><i class="fa fa-sort"></i></a>';
    }
}

if (!function_exists('selected')) {
    function selected($current, $values, $echo = true, $selected = "checked") {
        $result = false;
        if ($values) {
            if (is_object($values)) {
                foreach ($values as $item) {
                    if ($item->id == $current) {
                        $result = true;
                        break;
                    }
                }
            } elseif (is_array($values)) {
                if (in_array($current, $values)) {
                    $result = true;
                }
            } else {
                if ($current == $values) {
                    $result = true;
                }
            }
        }
        if ($result) {
            if ($echo)
                echo $selected;
            else
                return true;
        }else {
            return false;
        }
    }
}

if (!function_exists('linkClassActive')) {
    function linkClassActive($route, $status = null, $active = 'active') {
        $request = request();
        $current_route = $request->route()->getName();
        if ($route == $current_route && $request->has('status')) {
            if ($status == $request->get('status')) {
                return $active;
            }
            return null;
        }
        return null;
    }
}

if (!function_exists('subLinkClassActive')) {
    function subLinkClassActive($route, $active = 'active') {
        if (Request::route()->getName() == $route || Request::is(trim(route($route, [], false) . '/*', '\/'))) {
            return $active;
        }
        return null;
    }
}

if (!function_exists('matchPatternUrl')) {
    function matchPatternUrl($pattern, $url = null) {
        if (!$url) {
            $url = Request::url();
        }
        $pattern = preg_quote($pattern, '/');
        $pattern = str_replace('\*', '*', $pattern);
        return preg_match('/'. $pattern .'/', $url);
    }
}

if (!function_exists('nestedOption')) {
    function nestedOption($items, $selected = 0, $parent = 0, $depth = 0) {
        $html = '';
        $intent = str_repeat('-- ', $depth);
        if (!is_array($selected)) {
            $selected = [$selected];
        }
        if ($items) {
            foreach ($items as $item) {
                if ($item->parent_id == $parent) {
                    $select = in_array($item->id, $selected) ? 'selected' : '';
                    $html .= '<option value="' . $item->id . '" ' . $select . '>' . $intent . $item->name . '</option>';
                    $html .= nestedOption($items, $selected, $item->id, $depth + 1);
                }
            }
        }
        return $html;
    }
}

function makeRandDir($length = 16, $model) {
    $dir = str_random($length);
    $item = $model->where('rand_dir', $dir)->first();
    if ($item) {
        $dir = makeRandDir($length, $model);
    }
    return $dir;
}

if (!function_exists('rangeOptions')) {
    function rangeOptions($min, $max, $selected = 0) {
        $html = '';
        for ($i = $min; $i <= $max; $i++) {
            $html .= '<option value="' . $i . '" ' . (($i == $selected) ? 'selected' : '') . '>' . $i . '</option>';
        }
        return $html;
    }
}

if (!function_exists('catCheckLists')) {
    function catCheckLists($items, $checked = [], $parent = 0, $depth = 0) {
        $html = '';
        $intent = str_repeat("--- ", $depth);
        foreach ($items as $item) {
            if ($item->parent_id == $parent) {
                $check = in_array($item->id, $checked) ? 'checked' : '';
                $html .= '<li>' . $intent . '<label><input type="checkbox" name="cat_ids[]" ' . $check . ' value="' . $item->id . '"> ' . $item->name . '</label></li>';
                $html .= catCheckLists($items, $checked, $item->id, $depth + 1);
            }
        }
        return $html;
    }
}

function cutImgPath($full_url) {
    $path = parse_url($full_url)['path'];
    $img_path = $full_url;
    if ($path) {
        $arr_path = explode('/', trim($path, '/'));
        unset($arr_path[0]);
        unset($arr_path[1]);
        $img_path = implode('/', $arr_path);
    }
    return $img_path;
}

function getImageSrc($img_path, $size = 'full') {
    $img_path = trim($img_path, '/');
    $sizes = ['thumbnail', 'medium', 'large', 'full'];
    if (!in_array($size, $sizes)) {
        $size = 'full';
    }
    $url = 'uploads/' . $size . '/' . $img_path;
    if (File::exists(trim($url))) {
        return '/' . $url;
    }
    if (File::exists('uploads/full/' . $img_path)) {
        return '/uploads/full/' . $img_path;
    }
    return '/uploads/' . $size . '/no-image.jpg';
}

function trimCh($text, $num_words = 15, $more = '...') {
    return substr($text, 0, $num_words) . $more;
}

function stripAllTags($string, $remove_breaks = false) {
    $string = preg_replace('@<(script|style)[^>]*?>.*?</\\1>@si', '', $string);
    $string = strip_tags($string);

    if ($remove_breaks)
        $string = preg_replace('/[\r\n\t ]+/', ' ', $string);

    return trim($string);
}

function trimWords($text, $num_words = 55, $more = '...') {
    if (null === $more) {
        $more = __('&hellip;');
    }

    $original_text = $text;
    $text = stripAllTags($text);

//	if ( strpos( _x( 'words', 'Word count type. Do not translate!' ), 'characters' ) === 0 && preg_match( '/^utf\-?8$/i', get_option( 'blog_charset' ) ) ) {
//		$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $text ), ' ' );
//		preg_match_all( '/./u', $text, $words_array );
//		$words_array = array_slice( $words_array[0], 0, $num_words + 1 );
//		$sep = '';
//	} else {
    $words_array = preg_split("/[\n\r\t ]+/", $text, $num_words + 1, PREG_SPLIT_NO_EMPTY);
    $sep = ' ';
//	}

    if (count($words_array) > $num_words) {
        array_pop($words_array);
        $text = implode($sep, $words_array);
        $text = $text . ' ' . $more;
    } else {
        $text = implode($sep, $words_array);
    }

    return $text;
}
