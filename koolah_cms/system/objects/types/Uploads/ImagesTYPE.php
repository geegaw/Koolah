<?php

class ImagesTYPE extends Nodes{
    //CONSTRUCT 
    public function __construct( $db=null, $collection = IMAGES_COLLECTION ){
        parent::__construct( $db, $collection );    
    }
    
    //GETTERS
    public function images(){ return $this->nodes; }
    
    //GETTERS
    public function get( $q=null, $fields=null, $orderBy=null, $distinct=null  ){
        $bsonArray = parent::get( $q, $fields , $orderBy);
        if ( count($bsonArray) ){
            foreach ( $bsonArray as $bson ){
                $image = new ImageTYPE();
                $image->read( $bson );
                $this->append( $image );
            }
        }   
    }
}
