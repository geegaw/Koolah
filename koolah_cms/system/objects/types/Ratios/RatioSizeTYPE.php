<?php
class RatioSizeTYPE{
    //PUBLIC
    public $label;
    public $w;  
    public $h;  
    
    //PRIVATE
    
    //CONSTRUCT
    public function __construct(){
        $this->label = new LabelTYPE( null, RATIOS_COLLECTION );
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
         );
         return $bson + $this->label->prepare();
    }
    
    public function read( $bson ){
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
            $this->h = (int)$bson['h'];
        if (isset($bson['w']))
            $this->w = (int)$bson['w'];
    }
    
    public function readObj( $obj ){
//debug::vardump($obj, 1);        
        if ( $obj ){
            $this->label->read($obj);    
            $this->w = (int)$obj->w;
            $this->h = (int)$obj->h;
        }    
    }
   
}