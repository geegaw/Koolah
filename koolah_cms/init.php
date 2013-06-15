<?php
/**
 * init
 * 
 * @ignore
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
 /**
 * init
 * 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\cms
 */ 
define('CMS_DIR', __DIR__  );
define('CONF_PATH', CMS_DIR.'/conf' );
define('KOOLAH_ROOT_CONF', CMS_DIR.'/../conf.php' );

require ( KOOLAH_ROOT_CONF );
require ( CONF_PATH.'/conf.php' );
date_default_timezone_set( TIMEZONE);

require ( CONF_PATH.'/pagePermission.php' );
require ( CONF_PATH.'/ajaxAccess.php' );
require ( SYSTEM_PATH.'/loader.php' );

Loader::loadDir(KOOLAH_TOOLS_PATH);
Loader::loadDir( LOCAL_TOOLS_PATH );
Loader::loadDir( DB_OBJECTS_PATH );
Loader::loadFILE(CMS_DIR.'/config.php');
Loader::loadDir( CORE_OBJECTS_PATH );
Loader::loadDir( TYPES_OBJECTS_PATH, true );
Loader::loadDir( SESSION_OBJECTS_PATH );
Loader::loadDir( AJAX_ACCESS_OBJECTS_PATH );
Loader::loadFILE(CMS_DIR.'/router.php');

$cmsMongo = new customMongo('cms');

if ( !$cmsMongo->status->success() ){
	echo '<div class="error fullWidth">'.$cmsMongo->status->msg.'</div>';
	Loader::loadFile( HTTP_ERRORS_PATH."/500.php" );
}
    

?>