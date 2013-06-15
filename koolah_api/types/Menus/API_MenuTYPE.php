<?php
/**
 * API_MenuTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * API_MenuTYPE
 * 
 * Extends MenuTYPE to work with api
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\api\types\Menus
 */
class API_MenuTYPE extends MenuTYPE {
    
    
    
    /**
     * setParentID
     * set parentID
     * @access public   
     * @param string $id     
     */    
    public function setParentID( $id ){ return cmsToolKit::permissionDenied(); }
    
    
   /**
     * save
     * set label ref if insert
     * @access public   
     * @param assocArray $bson
     * @return StatusTYPE
     */    
    final public function save($bson=null ){ return cmsToolKit::permissionDenied(); }
    
    /**
     * del
     * deletes self from db and children
     * @access  public
     * @return StatusTYPE
     */
     final public function del(){ return cmsToolKit::permissionDenied(); }
}
?>
    