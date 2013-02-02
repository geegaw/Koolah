<?php

class RatiosTYPE extends Nodes{
    //CONSTRUCT 
    public function __construct( $db=null, $collection = RATIOS_COLLECTION ){
        parent::__construct( $db, $collection );    
    }
    
    //GETTERS
    public function ratios(){ return $this->nodes; }
    
    //GETTERS
    public function get( $q=null, $fields=null, $orderBy=array('label'=>1), $distinct=null  ){
        $bsonArray = parent::get( $q, $fields , $orderBy);
        if ( count($bsonArray) ){
            foreach ( $bsonArray as $bson ){
                $ratio = new RatioTYPE();
                $ratio->read( $bson );
                $this->append( $ratio );
            }
        }   
    }
}
