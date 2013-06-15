<?php
/**
 * StyleTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * StyleTYPE
 * 
 * allows you to add a style to a tag
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Tags
 */
class StyleTYPE extends Node{
        
    /**
     * width
     * @var int
     * @access public
     */
    public $width = 0;
    
    /**
     * height
     * @var int
     * @access public
     */
    public $height = 0;
    
    //PRIVATE
    
    /**
     * constructor
     * initiates db to the tags collection
     * @param customMongo $db
     */    
    public function __construct( $db=null ){
        parent::__construct( $db, TAGS_COLLECTION );    
    }
    
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
        $bson = array( 
           'width' =>$this->width,
           'height' =>$this->height,
         );
        return parent::prepare() + $bson;
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
    
    /**
     * readAssoc
     * converts assocArray into Node
     * @access  public
     * @param assocArray $bson
     */
    public function readAssoc( $bson ){
        if (isset($bson['width']))   
            $this->width = $bson['width'];
        if (isset($bson['height']))   
            $this->height = $bson['height'];
    }
    
    /**
     * readObj
     * converts object into Node
     * @access  public
     * @param object $obj
     */
    public function readObj( $obj ){
        if ( $obj ){
            $this->width = $obj->width;    
            $this->height = $obj->height;    
        }    
    }
}