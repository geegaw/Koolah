<?php
/**
 * CropTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * CropTYPE
 * 
 * class to deal with a crop and all of its data
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Uploads
 */
class  CropTYPE{
        
    /**
     * array of selected crop coordinates 
     * @var array
     * @access public
     */
    public $coords = array();
    
    /**
     * width
     * @var int
     * @access public
     */
    public $w = 0;
    
    /**
     * height
     * @var int
     * @access public
     */
    public $h = 0;
    
    /**
     * equals
     * check a suspect is the same as this
     * @access public   
     * @param CropTYPE $suspect
     * @return bool     
     */    
    public function equals( $suspect ){
        return( $suspect instanceof CropTYPE
            && ( ($this->w != $suspect->w) || ($this->h != $suspect->h)) 
            && ( implode(' ', $this->coords) !=  implode(' ', $suspect->coords))
            );
    }
    
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
         $bson = array(
            'coords' => $this->coords,
            'w' => $this->w,
            'h' => $this->h,
         );
         return $bson;
    }
    
    /**
     * read
     * reads from db - clears and handles children's reading
     * calls appropriate method based on $bson type
     * @access  public
     * @param assocArray|object|string $bson
     */
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
    
    /**
     * readAssoc
     * converts assocArray into Node
     * @access  public
     * @param assocArray $bson
     */
    public function readAssoc( $bson ){
        if (isset($bson['coords']))
            $this->coords = json_decode(json_encode($bson['coords']));
        if (isset($bson['w']))
            $this->w = $bson['w'];
        if (isset($bson['h']))
            $this->h = $bson['h'];
    }
    
    /**
     * readObj
     * converts object into Node
     * @access  public
     * @param object $obj
     */
    public function readObj( $obj ){
        if ( $obj ){
            $this->coords = $obj->coords;
            $this->w = $obj->w;
            $this->h = $obj->h;
        }    
    }
}