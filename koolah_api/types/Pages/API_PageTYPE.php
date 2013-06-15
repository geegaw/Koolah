<?php
/**
 * API_PageTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * API_PageTYPE
 * 
 * Extends PageTYPE to work with api
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\api\types\Pages
 */
class API_PageTYPE extends PageTYPE{
    /**
     * __get
     * overloads default ->[var]
     * uses special for url
     * @access public
     * @param string $suspect          
     * @return mixed        
     */    
    public function __get( $suspect ){
        if ( $suspect == 'url' )
            return $this->getUrl();
        
        $data = $this->getData();
        if (isset( $data[$suspect] ))    
            return $data[$suspect];
        return null;
    }
        
    /**
     * getTemplateFile
     * gets the appropriate php file for a template
     * @access public          
     * @return string        
     */    
    public function getTemplateFile(){
        $file = '';    
        $template = new TemplateTYPE();
        $template->getByID( $this->getTemplateID() );
        if ( $template->getID() )
            $file = $template->label->getRef();
        return $file;
    }
    
    /**
     * getUrl
     * gets the seo url
     * @access private   
     * @return string
     */    
    private function getUrl(){
        $aliases = $this->getAliases();
        return $aliases[0]->getAlias();
    }
    
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
