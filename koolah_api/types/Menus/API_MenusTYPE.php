<?php
/**
 * API_MenusTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * API_MenusTYPE
 * 
 * Extends MenusTYPE to work with api 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\api\types\Menus
 */
class API_MenusTYPE extends MenusTYPE{
    
   /**
     * get
     * gets from parent and reads response
     * @access public          
     * @param assocArray $q -- query
     * @param array $fields
     * @param array $orderBy -- defaul by label asc
     * @param bool $distinct        
     */    
    public function get( $q=null, $fields=null, $orderBy=array('label'=>1), $distinct=null  ){
        $bsonArray = parent::get( $q, $fields , $orderBy);
        if ( count($bsonArray) ){
            foreach ( $bsonArray as $bson ){
                $menu = new API_MenuTYPE();
                $menu->read( $bson );
                $this->append( $menu );
            }
        }   
    }

} 