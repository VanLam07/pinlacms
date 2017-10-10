<?php 

namespace Admin\Facades;

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
    /*
     * status
     */
    const STT_PUBLISH = 1;
    const STT_DRAFT = 2;
    const STT_TRASH = 10;
    const AC_DELETE = 20;
    
    const PER_PAGE = 20;
    
    /*
     * comment status
     */
    const CM_STT_OPEN = 1;
    const CM_STT_CLOSE = 0;
    
    /*
     * menu type 
     */
    const MENU_TYPE_TAX = 4;
    const MENU_TYPE_CAT = 3;
    const MENU_TYPE_POST = 2;
    const MENU_TYPE_PAGE = 1;
    const MENU_TYPE_CUSTOM = 0;
    
}