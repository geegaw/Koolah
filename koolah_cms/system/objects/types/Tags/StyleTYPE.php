<?php

class StyleTYPE extends Node{
    //PUBLIC
    public $width = 0;
    public $height = 0;
    
    //PRIVATE
    
    //CONSTRUCT
    public function __construct( $db=null ){
        parent::__construct( $db, TAGS_COLLECTION );    
    }
    
    /***
     * MONGO FUNCTIONS
     */
    public function prepare(){
        $bson = array( 
           'width' =>$this->width,
           'height' =>$this->height,
         );
        return parent::prepare() + $bson;
    }

    public function read( $bson ){
        parent::read($bson);    
        $this->data = null;
        if ( is_array($bson) )
            self::readAssoc($bson);
        elseif( is_object($bson) )
            self::readObj( $bson );
        elseif( is_string($bson) )
            $this->readJSON( $bson );
        else 
            // TODO return error
            return;  
    }
    
    public function readAssoc( $bson ){
//debug::printr($bson, true);     
        if (isset($bson['width']))   
            $this->width = $bson['width'];
        if (isset($bson['height']))   
            $this->height = $bson['height'];
    }
    
    public function readObj( $obj ){
//debug::vardump($obj);        
        if ( $obj ){
            $this->width = $obj->width;    
            $this->height = $obj->height;    
        }    
    }
}