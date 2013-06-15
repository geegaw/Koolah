<?php
/**
 * TemplateSectionTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
 /**
 * TemplateSectionTYPE
 * 
 * Class to handle a sections inside of a template represented
 * as a tab in the view
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Templates
 */
class TemplateSectionTYPE extends Node{
    	
	/**
     * name
     * @var string
     * @access public
     */
    public $name = '';
    
    /**
     * list of fields in template section
     * @var FieldsTYPE
     * @access public
     */
    public $fields;
	
	 /**
     * constructor
     * initiates db to the templates collection
     * @param customMongo $db
     * @param string $type
     */    
    public function __construct( $db=null, $type="page" ){
		parent::__construct( $db, TEMPLATE_COLLECTION );	
		$this->fields = new FieldsTYPE( $db );		
	}
    
    /**
     * mkInput
     * make an input based on the section, fill with page data if possible
     * @access public   
     * @param PageTYPE $page     
     * @return string
     */    
    public function mkInput( $page ){ return $this->fields->mkInput( $page ); }
    
	/**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
		return parent::prepare() + array('name'=>$this->name) + $this->fields->prepare();		
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
        if ( isset( $bson['name'] ))
            $this->name = $bson['name'];
        $this->fields->read($bson);            
    }
    
    /**
     * readObj
     * converts object into Node
     * @access  public
     * @param object $obj
     */
    public function readObj( $obj ){
        if ( $obj ){
            $this->name = $obj->name;
            $this->fields->read($obj->fields);
        }      
    }
    
}
?>
