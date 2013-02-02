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
 * FOLDER LOCATIONS
 */ 

//CONF
define( 'CONF', ROOT_DIR.'/conf');


//SYSTEM FOLDER
define('SYSTEM_PATH', ROOT_DIR.'/system');

//DB OBJECTS FOLDER
define('DB_OBJECTS_PATH', SYSTEM_PATH.'/db');

//NODE OBJECTS FOLDER
define('NODE_OBJECTS_PATH', SYSTEM_PATH.'/Node');

//TOOLS FOLDER
define('TOOLS_PATH', SYSTEM_PATH.'/tools');

//OBJECTS FOLDER
define('OBJECTS_PATH', SYSTEM_PATH.'/objects');


//VIEWS FOLDER
define('VIEWS_PATH', ROOT_DIR.'/views');




/***
 * COLLECTIONS
 */ 
define('UPLOADS_COLLECTION', 'uploads');
define('IMAGES_COLLECTION', 'images');
define('RATIOS_COLLECTION', 'ratios');
define('TAGS_COLLECTION', 'tags');
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
