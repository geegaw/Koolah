<?php
/**
 * TagsTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * TagsTYPE
 * 
 * Extends Nodes to work with TagTYPE
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Tags
 */
class TagsTYPE extends Nodes{
        
    /**
     * constructor
     * initiates db to the tags collection     
     * @param customMongo $db
     * @param string $collection     
     */    
    public function __construct( $db=null, $collection = TAGS_COLLECTION ){
        parent::__construct( $db, $collection );    
    }
    
    /**
     * tags
     * shortcut to access parent nodes
     * @access public     
     * @return array     
     */    
    public function tags(){ return $this->nodes; }
    
    /**
     * get
     * gets from parent and reads response
     * @access public          
     * @param assocArray $q -- query
     * @param array $fields
     * @param array $orderBy -- defaul by label asc
     * @param bool $distinct        
     */    
    public function get( $q=null, $fields=null, $orderBy=array('label'=>1), $offset=0, $limit=null, $distinct=null  ){
        $bsonArray = parent::get( $q, $fields , $orderBy, $offset, $limit, $distinct);
        if ( count($bsonArray) ){
            foreach ( $bsonArray as $bson ){
                $tag = new TagTYPE();
                $tag->read( $bson );
                $this->append( $tag );
            }
        }   
    }
}
