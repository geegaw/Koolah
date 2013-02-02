<?php
/***
 * TIMEZONE
 */
define('TIMEZONE', 'America/New_York');  
/***/
 
/****
 * DEBUG 
 */
define( 'DEBUG', true ); 
define( 'ENV', 'dev' );
/***/

/***
 * COLLECTIONS
 */	
define('USER_COLLECTION', 'users');
define('ROLES_COLLECTION', 'roles');
define('PERMISSIONS_COLLECTION', 'permissions');
define('USER_HISTORY_COLLECTION', 'user_history');
define('FIELD_COLLECTION', 'fields');
define('TEMPLATE_COLLECTION', 'templates');
define('FOLDER_COLLECTION', 'folders');
define('MENU_COLLECTION', 'menus');
define('ALIAS_COLLECTION', 'alias');
define('PAGES_COLLECTION', 'pages');
define('TAGS_COLLECTION', 'tags');
define('UPLOADS_COLLECTION', 'uploads');
define('IMAGES_COLLECTION', 'images');
define('RATIOS_COLLECTION', 'ratios');
//***/



/***
 * FOLDER LOCATIONS
 */	

//CONF
define( 'CONF', ROOT_DIR.'/conf');
 
//SYSTEM FOLDER
define('SYSTEM_PATH', ROOT_DIR.'/system');

//OBJECTS FOLDER
define('OBJECTS_PATH', SYSTEM_PATH.'/objects');

//CORE OBJECTS FOLDER
define('CORE_OBJECTS_PATH', OBJECTS_PATH.'/core');

//DB OBJECTS FOLDER
define('DB_OBJECTS_PATH', OBJECTS_PATH.'/db');

//TYPES OBJECTS FOLDER
define('TYPES_OBJECTS_PATH', OBJECTS_PATH.'/types');

//KOOLAH OBJECTS FOLDER
define('KOOLAH_OBJECTS_PATH', OBJECTS_PATH.'/koolahObjects');

//AJAX ACCESS OBJECTS FOLDER
define('AJAX_ACCESS_OBJECTS_PATH', OBJECTS_PATH.'/AjaxAccessObjects');

//TOOLS FOLDER
define('TOOLS_PATH', SYSTEM_PATH.'/tools');


//VIEWS FOLDER
define('VIEWS_PATH', ROOT_DIR.'/views'); 

//HTTP ERRORS Folder
define('HTTP_ERRORS_PATH', VIEWS_PATH.'/HTTP_ERRORS'); 

//AJAX Folder
define('AJAX_PATH', VIEWS_PATH.'/private/ajax'); 

//FRONT END Folder
define('FE_PATH', VIEWS_PATH.'/private/fe'); 

//ELEMENTS Folder
define('ELEMENTS_PATH', VIEWS_PATH.'/private/elements'); 

//ELEMENTS Folder
define('META_PATH', VIEWS_PATH.'/private/meta'); 


//NAVS Folder
define('NAVS_PATH', ELEMENTS_PATH.'/navs'); 
 
//public
define( 'PUBLIC_PATH', ROOT_DIR.'/public' );

//CSS FOlDER
define( 'CSS_PATH', PUBLIC_PATH.'/css' );

//LESS FOlDER
define( 'LESS_PATH', PUBLIC_PATH.'/less' );

//JS FOlDER
define( 'JS_PATH', PUBLIC_PATH.'/js' );

//IMAGES FOlDER
define( 'IMAGES_PATH', PUBLIC_PATH.'/images' );

//TMP FOLDER
define( 'TMP_PATH', ROOT_DIR.'/tmp');

//UPLOADS FOLDER
define( 'UPLOADS_PATH',  '/uploads');
define( 'UPLOADS_DIR',  ROOT_DIR.'/uploads');

/**
 * File upload director structure will work off
 * of the time the file is uploaded using this order
 * of paramaters
 */
define( 'UPLOAD_FOLDER_STRUCTURE', 'YmdH' );
 
//MAX FILE SIZE for uploads in bytes
define( 'MAX_FILE_SIZE',  102400000 ); //10M

//MAX DIRS maximum number of directories allowed in a folder
define( 'MAX_DIR_SIZE',  100 );
/***/


/***
 * 
 */	

//
//define('REQ_PARSER', '/koolah_cms/');
//efine('REQ_PARSER', '/');

define( 'FOUR_O_FOUR', 404 );

define( 'AJAX_CALL', 'ajax' );
define( 'SETUP_CALL', 'setup' );
/***/


/***
 * PAGES 
 */	

//
define('HOME', '/home');
define('SIGNIN', '/signin');
define('SIGNOUT', '/signout');
/***/

/***
 * LOGGIND 
 */ 

//
define('TIME_FORMAT', 'His');
define('DATE_FORMAT', 'Ymd');
define('TIMESTAMP_FORMAT', DATE_FORMAT.'-'.TIME_FORMAT);
/***/


/***
 * AJAX 
 */	

//
define( 'AJAX_GET_URL', '/'.AJAX_CALL.'/get' );
define( 'AJAX_GET_ONE_URL', '/'.AJAX_CALL.'/getOne' );
define( 'AJAX_SAVE_URL', '/'.AJAX_CALL.'/save' );
define( 'AJAX_UPLOAD_URL', '/'.AJAX_CALL.'/uploadFile' );
define( 'AJAX_DEL_URL', '/'.AJAX_CALL.'/del' );
define( 'AJAX_DEACTIVATE', '/'.AJAX_CALL.'/deactivate' );
define( 'AJAX_REACTIVATE', '/'.AJAX_CALL.'/reactivate' );
/***/


/***
 * FIRST TIME LOGIN
 */	
define('SETUP', ROOT_DIR.'/setup');
define('FIRST_TIME_LOGIN', '/'.SETUP_CALL.'/firstTimeSignIn');
define('FIRST_TIME_LOGIN_UP', 'koolah@cms.com:abc123');
/***/

/***
 * 
 */
 
define( 'FOLDER_COLLECTION_ROOT', MD5('rootFolder') );
 
/***/
 
 /****
  * Valid File Types
  */
$VALID_IMAGES = array(
    'jpg', 'jpeg',
    'png',
    'gif'
);
$VALID_DOCS = array(
    'doc', 'docx',
    'xls', 'xlsx',
    'ppt', 'pptx',
    'pdf', 
);
$VALID_VIDS = array(
    'flv', 
    'mp4',
    'ogg',
    'webm' 
);
$VALID_AUDIO = array(
    'mp3', 
    'wav'
);  

$VALID_FILES = array_merge($VALID_IMAGES, $VALID_DOCS, $VALID_VIDS, $VALID_AUDIO);  
/***/
?>