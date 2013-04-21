<?php

class PagesTYPE extends Nodes{
    //CONSTRUCT 
    public function __construct( $db=null, $collection = PAGES_COLLECTION ){
        parent::__construct( $db, $collection );    
    }
    
    //GETTERS
    public function pages(){ return $this->nodes; }
    
    
    //GETTERS
    public function get( $q=array(), $fields=null, $orderBy=null, $distinct=null, $offline=false  ){
        if (!$offline)
            $q = array_merge( array('publicationStatus'=>'published'), $q );
            
        $bsonArray = parent::get( $q, $fields , $orderBy);
        if ( count($bsonArray) ){
            foreach ( $bsonArray as $bson ){
                $page = new PageTYPE( $this->db, $this->collection );
                $page->read( $bson );
                $this->append( $page );
            }
        }   
    }
    
    /***
     * MONGO FUNCTIONS
     */
    
    public function read( $bson ){
        if ( $bson && isset($bson['fields']) ){
            $this->clear();         
            foreach ( $bson['fields'] as $node )
                $this->append($node);
        }                       
    }
    /***/ 
               
} 