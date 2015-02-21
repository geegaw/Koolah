<?php
/**
 * TaxonomyTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * TaxonomyTYPE
 * 
 * Extends Nodes to work with TermTYPE
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Terms
 */
class TaxonomyTYPE extends Nodes{
        
    /**
     * constructor
     * initiates db to the terms collection     
     * @param customMongo $db
     * @param string $collection     
     */    
    public function __construct( $db=null, $collection = TAXONOMY_COLLECTION ){
        parent::__construct( $db, $collection );    
    }
    
    /**
     * terms
     * shortcut to access parent nodes
     * @access public     
     * @return array     
     */    
    public function terms(){ return $this->nodes; }
    
    /**
     * get
     * gets from parent and reads response
     * @access public          
     * @param assocArray $q -- query
     * @param array $fields
     * @param array $orderBy -- defaul by label asc
     * @param bool $distinct        
     */    
    public function get( $q=null, $fields=null, $orderBy=null, $offset=0, $limit=null, $distinct=null  ){
    	$orderBy = $orderBy ? $orderBY : array('order'=>1);
    	$bsonArray = parent::get( $q, $fields , $orderBy, $offset, $limit, $distinct);
        if ( count($bsonArray) ){
            foreach ( $bsonArray as $bson ){
                $term = new TermTYPE();
                $term->read( $bson );
				$this->append( $term );
            }
        }   
    }
	
	/**
     * read
     * reads from db
     * clears self and then adds newly read items
     * @access  public
     * @param assocArray $bson
     */
    public function read( $bson ){
        if ( $bson ){
            $this->clear();         
            foreach ( $bson as $node ){
            	$term = new TermTYPE();
				$term->read( $node );
                $this->append($term);
			}
        }                       
    }
}
