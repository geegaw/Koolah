<?php
/**
 * FieldTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
 
 $labelOptions = array( 'text', 'textarea', 'template' );
 
/**
 * FieldTYPE
 * 
 * Class to handle fields that power templates
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Templates
 */
 class FieldTYPE extends Node{
        
    /**
     * boolean to determine if field is required
     * @var bool
     * @access public
     */
    public $required = false;
	
	/**
     * boolean to determine if there can be multiple
     * values
     * @var bool
     * @access public
     */
    public $many = false;
	
	/**
     * internal options pertaining
     * to field
     * @var string
     * @access public
     */
    public $options = '';
    	
    /**
     * label
     * @var LabelTYPE
     * @access public
     */
    private $label;
	
	/**
     * field type
     * @var string
     * @access public
     */
    private $type;
	
    
	/**
     * constructor
     * initiates db to the fields collection
     * @param customMongo $db
     */    
    public function __construct( $db=null ){
		parent::__construct( $db, FIELD_COLLECTION );	
		$this->label = new LabelTYPE($db, FIELD_COLLECTION);
	}
	
	/**
     * getLabel
     * get label
     * @access public   
     * @return string     
     */    
    public function getLabel(){ return $this->label->label; }
	
	/**
     * getRef
     * get Ref
     * @access public   
     * @return string     
     */    
    public function getRef(){ return $this->label->getRef(); }
    
    /**
     * getType
     * get Type
     * @access public   
     * @return string     
     */    
    public function getType(){ return $this->type; }
	
	/**
     * setLabel
     * set Label
     * @access public   
     * @param string|LabelTYPE $label     
     */    
    public function setLabel( $label ){  
		if ( is_string($label) )	
		 	$this->label->label = $label;
		else
		 	$this->label = $label;
	}
	
	/**
     * setType
     * set Type
     * @access public   
     * @param string $type     
     */    
    public function setType( $type ){ $this->type = $type; }
	
	/**
     * mkInput
     * make an input based on the field, fill with page data if possible
     * @access public   
     * @param PageTYPE $page     
     * @return string
     */    
    public function mkInput($page){
        $inputType = new FieldTypeTYPE( $this->type );
        if ( is_object($page) && method_exists($page, 'mkInput'))
            return $inputType->mkInput( $page->getData(), $this );
        return $inputType->mkInput( $page, $this );
    }
	
	
	/**
     * save
     * set the ref if inserting
     * @access public   
     * @param assocArray $bson     
     * @return StatusTYPE
     */    
    public function save($bson=null){
		if ( !$this->id )	
			$this->label->setRef();	
		return parent::save();
	}
	
	/**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
	    $bson = array( 
	       'required'=>$this->required, 
	       'many'=>$this->many,
	       'options'=>$this->options,  
	       'type'=>$this->type
         );
        return parent::prepare() + $this->label->prepare() + $bson;
	}
    
    /**
     * export
     * prepares for sending to another user
     * @access  public
     * @return assocArray
     */
    public function export(){
        $bson = array( 
           'required'=>$this->required, 
           'many'=>$this->many,
           'options'=>$this->options,  
           'type'=>$this->type
         );
         
         if ($this->type == 'custom'){
                $bson['options'] = '';
                $requiredTemplate = new TemplateTYPE();
                $requiredTemplate->getByID( $this->options );
                $bson['requiredTemplate'] = $requiredTemplate->export();
         }
         
        return parent::export() + $this->label->export() + $bson; 
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
        $this->label->read( $bson );
        if ( isset($bson['required']) )
            $this->required = $bson['required'];
        if ( isset($bson['many']) )
            $this->many = $bson['many'];
        if ( isset($bson['options']) )
            $this->options = $bson['options'];
        if ( isset($bson['type']) )
            $this->type = $bson['type'];
        
        // special case
        // coming from an import
        if ($this->type == 'custom' && isset($bson['requiredTemplate'])){
                $requiredTemplate = new TemplateTYPE();
                $requiredTemplate->read( $bson['requiredTemplate'] );
                $status = $requiredTemplate->save();
                if ($status->success())
                    $this->option = $requiredTemplate->getID();
         }                        
    }
    
    /**
     * readObj
     * converts object into Node
     * @access  public
     * @param object $obj
     */
    public function readObj( $obj ){
        if ( $obj ){
            $this->label->read( $obj );
            $this->type = $obj->type;               
            $this->required = $obj->required;
            $this->many = $obj->many;
             $this->options = $obj->options;
        }    
    }
    
    /**
     * toJSON
     * converts object into JSON
     * @access  public
     * @return string
     */
    public function toJSON(){
		return parent::toJSON() + json_encode($this);
	}
}

?>
