<?php
/**
 * MenusTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * MenusTYPE
 * 
 * Extends Nodes to work with MenuTYPE 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Menus
 */
class MenusTYPE extends Nodes{
        
    /**
     * constructor
     * initiates db to the menu collection     
     * @param customMongo $db
     * @param string $collection     
     */    
    public function __construct( $db=null, $collection = MENU_COLLECTION ){
        parent::__construct( $db, $collection );    
    }
    
    /**
     * menus
     * shortcut to access parent nodes
     * @access public     
     * @return array     
     */    
    public function menus(){ return $this->nodes; }
    
    
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
                $menu = new MenuTYPE();
                $menu->read( $bson );
                $this->append( $menu );
            }
        }   
    }
    
    
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
        return array( 'Menus'=>parent::prepare() );
    }
    
    /**
     * read
     * reads from db - clears object ahead of time
     * @access  public
     * @param assocArray $bson
     */
    public function read( $bson ){
        if ( $bson && isset($bson['fields']) ){
            $this->clear();         
            foreach ( $bson['fields'] as $node )
                $this->append($node);
        }                       
    }
} 