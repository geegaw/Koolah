<?php

class TagTYPE extends Node{
    //PUBLIC
    public $label;
    public $style;
    
    //PRIVATE
    private $templateType;
    
    //CONSTRUCT
    public function __construct( $db=null ){
        parent::__construct( $db, TAGS_COLLECTION );    
        
        $this->label = new LabelTYPE( $db, TAGS_COLLECTION );
        $this->style = new StyleTYPE( $db, TAGS_COLLECTION );
    }
    
    //fetchers
    
    
    /***
     * MONGO FUNCTIONS
     */
    public function prepare(){
        $bson = array( 
           'style'=>$this->style->prepare(),
         );
        return parent::prepare() + $bson + $this->label->prepare();
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
        $this->label->read($bson);
        if (isset($bson['style']))
            $this->style->read($bson['style']);
    }
    
    public function readObj( $obj ){
debug::vardump($obj, 1);        
        if ( $obj ){
            $this->label->read($obj->label);
            $this->style->read($obj->style);
        }    
    }
}