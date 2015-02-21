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
 * @package koolah\system\tools
 */
class cmsToolKit{
	
	/**
     * permissionDenied
     * set status to permission denier
     * @access public
     * @return StatusTYPE
     */    
    public static function permissionDenied($msg=''){
    	$msg = trim(PERMISSION_DENIED.' '.$msg);	 
		$status = new StatusTYPE($msg, false);
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
					$status = cmsToolKit::rmDirDashR( $el );
				elseif (!unlink($el)){
					$status->setFalse('could not delete '.$el);
					return $status; 
				}
			}
        }
		return $status;
    }
    
    /**
     * getUser
     * get a user by an id, returns null if not found
     * @access public
     * @param string $id
     * @return UserTYPE|null
     */    
    public static function getUser( $id ){
        if ( $id ){
            $user = new UserTYPE();
            $user->getByID( $id );
            if ( $user->getID() )
                return $user;    
        }
        return null; 
    }
    
    /**
     * displayUser
     * get a user by an id, returns null if not found
     * @access public
     * @param UserTYPE $user
     * @return string|null
     */    
    public static function displayUser( $user ){
        if ( $user ){
            return $user->getName();        
        }
        return null; 
    }
    
}

?>
