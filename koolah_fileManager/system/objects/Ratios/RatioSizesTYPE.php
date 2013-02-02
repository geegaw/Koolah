<?php

class RatioSizesTYPE{
    public $sizes = array();    
        
    //CONSTRUCT 
    
    public function clear(){ $this->sizes = array(); }
    
    public function prepare(){
        $bson = null;    
        if ( $this->sizes ){
            foreach ( $this->sizes as $size ){
                $bson[] = $size->prepare();
            }
        }   
        return $bson;
    }
    
    
    public function read( $bsonArray ){
        $this->clear();
        if ( count($bsonArray) ){
            foreach ( $bsonArray as $bson ){
                $ratio = new RatioSizeTYPE();
                $ratio->read( $bson );
                $this->sizes[] = $ratio;
            }
        }   
    }
}
