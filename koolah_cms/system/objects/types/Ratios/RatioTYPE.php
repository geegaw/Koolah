<?php
class RatioTYPE extends Node{
    //PUBLIC
    public $label;
    public $w;  
    public $h;  
    public $sizes;
    
    //PRIVATE
    
    //CONSTRUCT
    public function __construct( $db=null ){
        parent::__construct( $db, RATIOS_COLLECTION );
        $this->label = new LabelTYPE( $db, RATIOS_COLLECTION );
        $this->sizes = new RatioSizesTYPE();
    }
    
    //fetchers
    
    //Bools
    
    //Getters
    
    /***
     * MONGO FUNCTIONS
     */
    public function prepare(){
        $bson = array( 
           'w'=>$this->w,
           'h'=>$this->h,
           'sizes'=>$this->sizes->prepare(),
         );
         return parent::prepare() + $bson + $this->label->prepare();
    }
    
    public function read( $bson ){
//debug::printr($bson);            
        parent::read($bson);    
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
//debug::printr($bson);        
        $this->label->read($bson);
        if (isset($bson['h']))
            $this->h = $bson['h'];
        if (isset($bson['w']))
            $this->w = $bson['w'];
        if (isset($bson['sizes']))
            $this->sizes->read( $bson['sizes'] );
    }
    
    public function readObj( $obj ){
//debug::vardump($obj, 1);        
        if ( $obj ){
            $this->label->read($obj);    
            $this->w = $bson->w;
            $this->h = $bson->h;
            $this->sizes->read( $bson->sizes );
        }    
    }
   
}