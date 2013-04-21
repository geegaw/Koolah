<?php
class  CropTYPE{
    //PUBLIC
    public $coords = array();
    public $w = 0;
    public $h = 0;
    
    //CONSTRUCT
    
    
    
    public function equals( $suspect ){
        return( $suspect instanceof CropTYPE
            && ( ($this->w != $suspect->w) || ($this->h != $suspect->h)) 
            && ( implode(' ', $this->coords) !=  implode(' ', $suspect->coords))
            );
    }
    
    /***
     * MONGO FUNCTIONS
     */
     public function prepare(){
         $bson = array(
            'coords' => $this->coords,
            'w' => $this->w,
            'h' => $this->h,
         );
         return $bson;
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
//debug::printr($bson, true);        
        if (isset($bson['coords']))
            $this->coords = json_decode(json_encode($bson['coords']));
        if (isset($bson['w']))
            $this->w = $bson['w'];
        if (isset($bson['h']))
            $this->h = $bson['h'];
    }
    
    public function readObj( $obj ){
//debug::vardump($obj);        
        if ( $obj ){
            $this->coords = $obj->coords;
            $this->w = $obj->w;
            $this->h = $obj->h;
        }    
    }
}