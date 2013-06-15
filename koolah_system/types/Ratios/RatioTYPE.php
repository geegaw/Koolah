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
class RatioTYPE extends Node{
    
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
     * array of sizes
     * @var array
     * @access public
     */
    public $sizes;
    
    /**
     * constructor
     * initiates db to the ratios collection
     * @param customMongo $db
     */    
    public function __construct( $db=null ){
        parent::__construct( $db, RATIOS_COLLECTION );
        $this->label = new LabelTYPE( $db, RATIOS_COLLECTION );
        $this->sizes = new RatioSizesTYPE();
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
           'sizes'=>$this->sizes->prepare(),
         );
         return parent::prepare() + $bson + $this->label->prepare();
    }
    
    /**
     * read
     * reads from db - clears and handles children's reading
     * calls appropriate method based on $bson type
     * @access  public
     * @param assocArray|object|string $bson
     */
    public function read( $bson ){
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
    
    /**
     * readAssoc
     * converts assocArray into Node
     * @access  public
     * @param assocArray $bson
     */
    public function readAssoc( $bson ){
        $this->label->read($bson);
        if (isset($bson['h']))
            $this->h = $bson['h'];
        if (isset($bson['w']))
            $this->w = $bson['w'];
        if (isset($bson['sizes']))
            $this->sizes->read( $bson['sizes'] );
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
            $this->w = $bson->w;
            $this->h = $bson->h;
            $this->sizes->read( $bson->sizes );
        }    
    }
   
}