<?php

class FilesTYPE extends Nodes{
    //CONSTRUCT 
    public function __construct( $db=null, $collection = UPLOADS_COLLECTION ){
        parent::__construct( $db, $collection );    
    }
    
    //GETTERS
    public function files(){ return $this->nodes; }
    
    //GETTERS
    public function get( $q=null, $fields=null, $orderBy=null, $distinct=null  ){
        $bsonArray = parent::get( $q, $fields , $orderBy);
        if ( count($bsonArray) ){
            foreach ( $bsonArray as $bson ){
                $file = new FileTYPE();
                $file->read( $bson );
                $this->append( $file );
            }
        }   
    }
}
