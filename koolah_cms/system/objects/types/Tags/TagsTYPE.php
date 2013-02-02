<?php

class TagsTYPE extends Nodes{
    //CONSTRUCT 
    public function __construct( $db=null, $collection = TAGS_COLLECTION ){
        parent::__construct( $db, $collection );    
    }
    
    //GETTERS
    public function tags(){ return $this->nodes; }
    
    //GETTERS
    public function get( $q=null, $fields=null, $orderBy=array('label'=>1), $distinct=null  ){
        $bsonArray = parent::get( $q, $fields , $orderBy);
        if ( count($bsonArray) ){
            foreach ( $bsonArray as $bson ){
                $tag = new TagTYPE();
                $tag->read( $bson );
                $this->append( $tag );
            }
        }   
    }
}
