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


define( 'SITE_URL', 'http://www.vaugeoisphotography.local');
define( 'FM_URL', 'http://files.koolah.local');

/***
 * FOLDER LOCATIONS
 */ 

//CONF
define( 'CONF', ROOT_DIR.'/conf');

//API FOLDER
define( 'API_PATH', ROOT_DIR.'/api' );

//DB OBJECTS FOLDER
define('DB_OBJECTS_PATH', API_PATH.'/db');

//NODE OBJECTS FOLDER
define('NODE_OBJECTS_PATH', API_PATH.'/Node');

//CORE OBJECTS FOLDER
define('CORE_OBJECTS_PATH', API_PATH.'/core');

//TOOLS FOLDER
define('TOOLS_PATH', API_PATH.'/tools');


//VIEWS FOLDER
define('VIEWS_PATH', ROOT_DIR.'/views');

//HTTP ERRORS Folder
define('HTTP_ERRORS_PATH', VIEWS_PATH.'/HTTP_ERRORS'); 

//HTTP ERRORS Folder
define('PAGES_PATH', VIEWS_PATH.'/pages');

//ELEMENTS Folder
define('ELEMENTS_PATH', VIEWS_PATH.'/elements');

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

define( 'FOUR_O_FOUR', 404 );

define( 'AJAX_CALL', 'ajax' );


/***
 * COLLECTIONS
 */ 
define('MENU_COLLECTION', 'menus');
define('ALIAS_COLLECTION', 'alias');
define('PAGES_COLLECTION', 'pages');
define('TEMPLATE_COLLECTION', 'templates');
/***/