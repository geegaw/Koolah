<?php
/**
 * TagTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * TagTYPE
 * 
 * class tp work with a tag
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Tags
 */
class TagTYPE extends Node{
        
    /**
     * label
     * @var LabelTYPE
     * @access public
     */
    public $label;
    
    /**
     * style associated wtih label
     * @var StyleTYPE
     * @access public
     */
    public $style;
    
    /**
     * constructor
     * initiates db to the tags collection
     * @param customMongo $db
     */    
    public function __construct( $db=null ){
        parent::__construct( $db, TAGS_COLLECTION );    
        
        $this->label = new LabelTYPE( $db, TAGS_COLLECTION );
        $this->style = new StyleTYPE( $db, TAGS_COLLECTION );
    }
    
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
        $bson = array( 
           'style'=>$this->style->prepare(),
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
        $this->label->read($bson);
        if (isset($bson['style']))
            $this->style->read($bson['style']);
    }
    
    /**
     * readObj
     * converts object into Node
     * @access  public
     * @param object $obj
     */
    public function readObj( $obj ){
        if ( $obj ){
            $this->label->read($obj->label);
            $this->style->read($obj->style);
        }    
    }
}