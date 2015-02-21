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
 * FOLDER LOCATIONS
 */ 

//KOOLAH_ROOT
define( 'KOOLAH_ROOT', dirname(__FILE__));

//SYSTEM FOLDER
define('SYSTEM_PATH', KOOLAH_ROOT.'/koolah_system');

//CORE OBJECTS FOLDER
define('CORE_OBJECTS_PATH', SYSTEM_PATH.'/core');

//DB OBJECTS FOLDER
define('DB_OBJECTS_PATH', SYSTEM_PATH.'/db');

//TYPES OBJECTS FOLDER
define('TYPES_OBJECTS_PATH', SYSTEM_PATH.'/types');

//KOOLAH OBJECTS FOLDER
define('SESSION_OBJECTS_PATH', SYSTEM_PATH.'/sessionObjects');

//TOOLS FOLDER
define('KOOLAH_TOOLS_PATH', KOOLAH_ROOT.'/koolah_tools');

//API FOLDER
define('API_PATH', KOOLAH_ROOT.'/koolah_api');

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
define('TAXONOMY_COLLECTION', 'taxonomy');
//***/

//UPLOADS FOLDER
define( 'UPLOADS_PATH',  '/uploads');
define( 'UPLOADS_DIR',  KOOLAH_ROOT.'/uploads');

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
