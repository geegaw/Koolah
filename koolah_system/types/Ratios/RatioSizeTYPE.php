<?php
/**
 * RatioTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * RatioTYPE
 * 
 * creates a ratio with multiple different variants of the ratio
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Ratios
 */
class RatioSizeTYPE{
        
    /**
     * label
     * @var LabelTYPE
     * @access public
     */
    public $label;
    
    /**
     * width
     * @var int
     * @access public
     */
    public $w;  
    
    /**
     * height
     * @var int
     * @access public
     */
    public $h;  
    
    /**
     * constructor
     * initiates db to the ratios collection
     */    
    public function __construct(){
        $this->label = new LabelTYPE( null, RATIOS_COLLECTION );
    }
    
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
        $bson = array( 
           'w'=>$this->w,
           'h'=>$this->h,
         );
         return $bson + $this->label->prepare();
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
        $this->label->read($bson);
        if (isset($bson['h']))
            $this->h = (int)$bson['h'];
        if (isset($bson['w']))
            $this->w = (int)$bson['w'];
    }
    
    /**
     * readObj
     * converts object into Node
     * @access  public
     * @param object $obj
     */
    public function readObj( $obj ){
        if ( $obj ){
            $this->label->read($obj);    
            $this->w = (int)$obj->w;
            $this->h = (int)$obj->h;
        }    
    }
   
}