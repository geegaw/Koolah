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
 * @package koolah\website
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


define( 'SITE_URL', 'http://www.vaugeoisphotography.local');
define( 'FM_URL', 'http://files.koolah.local');

/***
 * FOLDER LOCATIONS
 */ 

//CONF
define( 'CONF', WEBSITE_DIR.'/conf');


//TOOLS FOLDER
define('LOCAL_TOOLS_PATH', API_PATH.'/tools');

//TOOLS FOLDER
define('LOCAL_TYPES_PATH', API_PATH.'/types');


//VIEWS FOLDER
define('VIEWS_PATH', WEBSITE_DIR.'/views');

//HTTP ERRORS Folder
define('HTTP_ERRORS_PATH', VIEWS_PATH.'/HTTP_ERRORS'); 

//HTTP ERRORS Folder
define('PAGES_PATH', VIEWS_PATH.'/pages');

//ELEMENTS Folder
define('ELEMENTS_PATH', VIEWS_PATH.'/elements');

//AJAX Folder
define('AJAX_PATH', VIEWS_PATH.'/ajax');

//public
define( 'PUBLIC_PATH', WEBSITE_DIR.'/public' );

//CSS FOlDER
define( 'CSS_PATH', PUBLIC_PATH.'/css' );

//LESS FOlDER
define( 'LESS_PATH', PUBLIC_PATH.'/less' );

//JS FOlDER
define( 'JS_PATH', PUBLIC_PATH.'/js' );

//IMAGES FOlDER
define( 'IMAGES_PATH', PUBLIC_PATH.'/images' ); 

define( 'FOUR_O_FOUR', 404 );

define( 'AJAX_CALL', 'ajax' );

/****
 * STYLE SHEET TYPE 
 */
define( 'STYLE_SHEET_TYPE',  'less' ); 
/***/

/**
  * 
  */
 define('PREVIEW_SECRET_KEY', 'AJ8gy&$f45');
