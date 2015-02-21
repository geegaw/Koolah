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
 * @package koolah\fileManager\conf
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


/***
 * FOLDER LOCATIONS
 */ 

//CONF
define( 'CONF', FILE_MANAGER_DIR.'/conf');

/**
 * File upload director structure will work off
 * of the time the file is uploaded using this order
 * of paramaters
 */
define( 'UPLOAD_FOLDER_STRUCTURE', 'YmdH' );


//SYSTEM FOLDER
define('OBJECTS_PATH', FILE_MANAGER_DIR.'/objects');

//TOOLS FOLDER
define('LOCAL_TOOLS_PATH', FILE_MANAGER_DIR.'/tools');

//VIEWS FOLDER
define('VIEWS_PATH', FILE_MANAGER_DIR.'/views');
