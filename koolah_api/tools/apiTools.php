<?php
/**
 * apiToolKit
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * apiToolKit
 * 
 * Api tools for easy fetcing of backend elements
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\api\tools
 */
class apiToolKit{
	
    /**
     * getPublicPropertiesKeys
     * returns a menu by reference
     * @access public
     * @param string $menuRef
     * @return API_MenusTYPE
     */    
    public static function getMenu( $menuRef ){
        $menus = new API_MenusTYPE();
        $menus->get( array( 'ref'=>$menuRef ) );
        if ( $menus->menus() ){
            $menus = $menus->menus();
            return  $menus[0]->getChildren();
        }
        return new API_MenusTYPE();
    }
    
    /**
     * getPages
     * returns a pages by template and query
     * @access public
     * @param string $templateRef
     * @param array $q
     * @param bool $offline - optional
     * @return API_PagesTYPE|null
     */    
    public static function getPages( $templateRef, $q=null, $offline=false ){
        $template = new API_TemplateTYPE();
        $template->get( array( 'ref'=>$templateRef ) );
        if ( $template->getID() ){
            $pages = new API_PagesTYPE();
            $query = array( 'templateID'=>$template->getID() );
            if (!$offline)
                $query['publicationStatus'] = 'published';
            
            if ( $q )
                $query = array_merge($query, apiToolKit::formatDataQuery($q));
            
            $pages->get( $query, $offline );
			
			return  $pages;
        }
        return null;
    }
    
    /**
     * formatDataQuery
     * formats a query to proper assoc array
     * @access public
     * @param array $q
     * @return assocArray
     */    
    public static function formatDataQuery( array $q ){
        $formatted = array();
        foreach( $q as $key => $param ){
            $formatted[ 'data.'.$key ] = $param;            
        }
        return $formatted; 
    }
}

?>
