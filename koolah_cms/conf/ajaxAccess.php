<?php
/**
 * ajaxAccess
 * 
 * @ignore
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 *  
 * 
 *  The only objects that AJAX calls can access are files placed inside of AJAX_ACCESS_OBJECTS_PATH.
 * 
 *  This is an added level of protection. Because the delete and save Javacript functions
 *  are so powerful, a disgruntled employee could call a node.del() or any ajax function on any object. 
 *  By adding this list we force the ajax to only accept objects that envoke permission checks.
 * 
 * Note: these objects or any objects added to this list, MUST apply user permission verifications
 * 
 */
 
$ajaxAccess = null;
$files = glob( AJAX_ACCESS_OBJECTS_PATH."/*.php" );
if ( $files ){
    foreach ($files as $file){
        $file = explode( AJAX_ACCESS_OBJECTS_PATH.'/', $file );
        $file = explode( '.php', $file[1]);
        $ajaxAccess[]= $file[0];
    }
}
    
?>