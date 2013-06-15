<?php
/**
 * API_PagesTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * API_PagesTYPE
 * 
 * Extends PagesTYPE to work with api
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\api\types\Pages
 */
class API_PagesTYPE extends PagesTYPE{
        
    /**
     * get
     * gets from parent and reads response
     * @access public          
     * @param assocArray $q -- query
     * @param array $fields
     * @param array $orderBy -- defaul by label asc
     * @param bool $distinct        
     */    
    public function get( $q=null, $fields=null, $orderBy=null, $distinct=null  ){
        parent::get( $q, $fields , $orderBy, $distinct);
        $pages = $this->pages();
        $this->clear();
        if ( $pages ){
            foreach ( $pages as $page ){
                $bson = $page->prepare();
                $api_page = new API_PageTYPE( $this->db, $this->collection );
                $api_page->read( $bson );
                $this->append( $api_page );
            }
        }
    }
    
} 