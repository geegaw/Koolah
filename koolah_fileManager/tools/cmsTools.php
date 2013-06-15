<?php
/**
 * cmsToolKit
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * cmsToolKit
 * 
 * CMS tools
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\fileManager\tools
 */
class cmsToolKit{
	    
	/**
     * permissionDenied
     * set status to permission denier
     * @access public
     * @return StatusTYPE
     */    
    public static function permissionDenied(){
		$status = new StatusTYPE(PERMISSION_DENIED, false);
		return $status;
	}
	
    /**
     * checkDir
     * if directory doesn't already exist create it
     * @access public
     * @param string $dirname
     * @return bool
     */    
    public static function checkDir( $dirname ){
        if ( !is_dir( $dirname ) )
            return mkdir($dirname);
        return true; 
    }
}

?>
