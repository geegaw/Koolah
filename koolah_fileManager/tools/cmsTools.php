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
	
	/**
     * rmDirDashR
     * functoins as a rm -R dir
     * @access public
     * @param string dir
     * @return bool
     */    
    public static function rmDirDashR( $dir ){
    	$status = new StatusTYPE();
        if (is_dir($dir)){
        	$interior = glob("$dir/*");
			foreach ($interior as $el){
				if (is_dir($el))
					$status = rmDirDashR( $el );
				elseif (!unlink($el)){
					$status->setFalse('could not delete '.$el);
					return $status; 
				}
			}
        }
		return $status;
    }
}

?>
