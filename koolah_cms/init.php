<?php
define('ROOT_DIR', __DIR__  );
define('CONF_PATH', ROOT_DIR.'/conf' );

require ( CONF_PATH.'/conf.php' );
date_default_timezone_set( TIMEZONE);


require ( CONF_PATH.'/errorMessages.php' );
require ( CONF_PATH.'/pagePermission.php' );
require ( CONF_PATH.'/ajaxAccess.php' );
require ( ROOT_DIR.'/loader.php' );


Loader::loadDir( TOOLS_PATH );
Loader::loadDir( DB_OBJECTS_PATH );
Loader::loadDir( CORE_OBJECTS_PATH );
Loader::loadDir( TYPES_OBJECTS_PATH, true );
Loader::loadDir( KOOLAH_OBJECTS_PATH );
Loader::loadDir( AJAX_ACCESS_OBJECTS_PATH );

$cmsMongo = new customMongo('cms');

if ( !$cmsMongo->status->success() ){
	echo '<div class="error fullWidth">'.$cmsMongo->status->msg.'</div>';
	Loader::loadFile( HTTP_ERRORS_PATH."/500.php" );
}
    

?>