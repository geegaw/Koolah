<?php
/**
 * conf
 * 
 * @ignore
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
 /**
 * conf
 * 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\cms
 */ 
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

/****
 * STYLE SHEET TYPE 
 */
define( 'STYLE_SHEET_TYPE',  'less' ); 
/***/

/***
 * FOLDER LOCATIONS
 */	

//CONF
define( 'CONF', CMS_DIR.'/conf');
 

//AJAX ACCESS OBJECTS FOLDER
define('AJAX_ACCESS_OBJECTS_PATH', CMS_DIR.'/AjaxAccessObjects');

//TOOLS FOLDER
define('LOCAL_TOOLS_PATH', CMS_DIR.'/tools');


//VIEWS FOLDER
define('VIEWS_PATH', CMS_DIR.'/views'); 

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
define( 'PUBLIC_PATH', CMS_DIR.'/public' );

//CSS FOlDER
define( 'CSS_PATH', PUBLIC_PATH.'/css' );

//LESS FOlDER
define( 'LESS_PATH', PUBLIC_PATH.'/less' );

//JS FOlDER
define( 'JS_PATH', PUBLIC_PATH.'/js' );

//IMAGES FOlDER
define( 'IMAGES_PATH', PUBLIC_PATH.'/images' );

//TMP FOLDER
define( 'TMP_PATH', CMS_DIR.'/tmp');

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
 * LOGGING 
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
define('SETUP', CMS_DIR.'/setup');
define('FIRST_TIME_LOGIN', '/'.SETUP_CALL.'/firstTimeSignIn');
define('FIRST_TIME_LOGIN_UP', 'koolah@cms.com:abc123');
/***/

define( 'FM_URL', 'http://files.koolah.local');

/***
 * 
 */
 
define( 'FOLDER_COLLECTION_ROOT', MD5('rootFolder') );
 
/***/

/**
 * 
 */
 define( 'MAX_PAGE_HISTORY', '100' );
?>