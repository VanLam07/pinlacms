<?php 

namespace Admin\Facades;

use Storage;
use Session;

class AdConst {
    
    const ADMIN_ID = 1;
    /*
     * gender
     */
    const GD_UNDEFINED = 0;
    const GD_MALE = 1;
    const GD_FEMALE = 2;
    /*
     * cap
     */
    const CAP_NONE = 0;
    const CAP_SELF = 1;
    const CAP_OTHER = 2;
    const KEY_USER_CAPS = 'user_caps';
    /*
     * status
     */
    const STT_PUBLISH = 1;
    const STT_DRAFT = 2;
    const STT_TRASH = 10;
    const AC_DELETE = 20;
    
    const FORMAT_POST = 1;
    const FORMAT_NOTIFY = 2;
    const FORMAT_QUOTE = 3;
    const FORMAT_DICT = 4;
    const FORMAT_DIARY = 10;
    
    const PER_PAGE = 20;
    const SUB_PER_PAGE = 5;
    const FILE_PER_PAGE = 30;
    
    /*
     * comment status
     */
    const CM_STT_OPEN = 1;
    const CM_STT_CLOSE = 0;
    
    /*
     * menu type 
     */
    const MENU_TYPE_ALBUM = 5;
    const MENU_TYPE_TAX = 4;
    const MENU_TYPE_CAT = 3;
    const MENU_TYPE_POST = 2;
    const MENU_TYPE_PAGE = 1;
    const MENU_TYPE_CUSTOM = 0;
    
    const BG_COLOURS = ['#74a8fc', '#ffa75b', '#f7d86a', '#63c159', '#75d5d8', '#ad143d', '#037267', '#18ba3d', '#1a2499'];
    
    /*
     * custom page
     */
    const QUOTE_PAGE = 'quote';
    const GEN_WORD_PAGE = 'generate-word';
    
    public static function getFileSrc($fileUrl, $size = 'thumbnail')
    {
        $imageSizes = config('image.image_sizes');
        if(!isset($imageSizes[$size])){
            $size = 'full';
        }
        $uploadDir = trim(config('image.upload_dir'), '/');
        $srcFile = $uploadDir .'/'. $size. '/' . $fileUrl;
        $fullSrc = $uploadDir . '/full/' . $fileUrl;
        if (!Storage::disk()->exists($srcFile)){
            if (Storage::disk()->exists($fullSrc)) {
                return Storage::disk()->url($fullSrc);
            }
        }
        return Storage::disk()->url($srcFile);
    }

    public static function listPostFormats()
    {
        return [
            self::FORMAT_POST => 'Post',
            self::FORMAT_NOTIFY => 'Notify',
            self::FORMAT_QUOTE => 'Quote',
            self::FORMAT_DIARY => 'Diary',
            self::FORMAT_DICT => 'Word sentence'
        ];
    }

    public static function getPostFormatLabel($format, $listFormats)
    {
        if (isset($listFormats[$format])) {
            return $listFormats[$format];
        }
        return $format;
    }

    public static function randBgPost()
    {
        $idx = array_rand(self::BG_COLOURS);
        return self::BG_COLOURS[$idx];
    }

    public static function putUserCaps($user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }
        $listUserCaps = Session::get(self::KEY_USER_CAPS);
        if (!$listUserCaps) {
            $listUserCaps = [];
        }
        if (!isset($listUserCaps[$user->id])) {
            $listUserCaps[$user->id] = [];
        }
        $listUserCaps[$user->id] = $user->getDBCaps();
        Session::put(self::KEY_USER_CAPS, $listUserCaps);
    }
    
    public static function getUserCaps($user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }
        $listUserCaps = Session::get(self::KEY_USER_CAPS);
        if (!$listUserCaps) {
            $listUserCaps = [];
        }
        if (isset($listUserCaps[$user->id])) {
            return $listUserCaps[$user->id];
        }
        $listUserCaps[$user->id] = $user->getDBCaps();
        Session::put(self::KEY_USER_CAPS, $listUserCaps);
        return $listUserCaps[$user->id];
    }
    
    public static function forgetAllUserCaps()
    {
        Session::forget(self::KEY_USER_CAPS);
    }
}