<?php
/**
 * API_TemplateTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
 /**
 * API_TemplateTYPE
 * 
 * Extends TemplateTYPE to work with api
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\api\Templates
 */
class API_TemplateTYPE extends TemplateTYPE{
    
    /**
     * save
     * set label ref if insert
     * @access public   
     * @param assocArray $bson
     * @return StatusTYPE
     */    
    final public function save($bson=null ){
        return cmsToolKit::permissionDenied();
    }
    
    /**
     * del
     * deletes self from db and children
     * @access  public
     * @return StatusTYPE
     */
     final public function del(){ return cmsToolKit::permissionDenied(); }
}    
?>
