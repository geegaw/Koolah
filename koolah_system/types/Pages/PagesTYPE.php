<?php
/**
 * PagesTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * PagesTYPE
 * 
 * Extends Nodes to work with PageTYPE
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Pages
 */
class PagesTYPE extends Nodes{
        
    /**
     * constructor
     * initiates db to the pages collection     
     * @param customMongo $db
     * @param string $collection     
     */    
    public function __construct( $db=null, $collection = PAGES_COLLECTION ){
        parent::__construct( $db, $collection );    
    }
    
    /**
     * pages
     * shortcut to access parent nodes
     * @access public     
     * @return array     
     */    
    public function pages(){ return $this->nodes; }
    
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
        $bsonArray = parent::get( $q, $fields , $orderBy);
        if ( count($bsonArray) ){
            foreach ( $bsonArray as $bson ){
                $page = new PageTYPE( $this->db, $this->collection );
                $page->read( $bson );
                $this->append( $page );
            }
        }
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