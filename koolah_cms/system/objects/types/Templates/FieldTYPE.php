<?php
$labelOptions = array( 'text', 'textarea', 'template' );

class FieldTYPE extends Node
{
    public $required = false;
	public $many = false;
	public $options = '';
    	
    private $label;
	private $type;
	
    
	public function __construct( $db ){
		parent::__construct( $db, FIELD_COLLECTION );	
		$this->label = new LabelTYPE($db, FIELD_COLLECTION);
	}
	
	//GETTERS
	public function getLabel(){ return $this->label->label; }
	public function getRef(){ return $this->label->getRef(); }
    public function getType(){ return $this->type; }
	
	//SETTERS
	public function setLabel( $label ){  
		if ( is_string($label) )	
		 	$this->label->label = $label;
		else
		 	$this->label = $label;
	}
	public function setType( $type ){ $this->type = $type; }
	
	public function mkInput($page){
        $inputType = new FieldTypeTYPE( $this->type );
        if ( is_object($page) && method_exists($page, 'mkInput'))
            return $inputType->mkInput( $page->getData(), $this );
        return $inputType->mkInput( $page, $this );
    }
	
	
	public function save($bson=null){
		if ( !$this->id )	
			$this->label->setRef();	
		return parent::save();
	}
	
	/***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
	    $bson = array( 
	       'required'=>$this->required, 
	       'many'=>$this->many,
	       'options'=>$this->options,  
	       'type'=>$this->type
         );
        return parent::prepare() + $this->label->prepare() + $bson;
		//return parent::prepare() + $this->label->prepare() + $this->type->prepare() + array( 'required'=>$this->required, 'many'=>$this->many );		
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
        $this->label->read( $bson );
        if ( isset($bson['required']) )
            $this->required = $bson['required'];
        if ( isset($bson['many']) )
            $this->many = $bson['many'];
        if ( isset($bson['options']) )
            $this->options = $bson['options'];
        if ( isset($bson['type']) )
            $this->type = $bson['type'];
                        
    }
    
    public function readObj( $obj ){
//debug::vardump($obj);        
        if ( $obj ){
            $this->label->read( $obj );
            $this->type = $obj->type;               
            $this->required = $obj->required;
            $this->many = $obj->many;
             $this->options = $obj->options;
        }    
    }
    
    //JSON
	public function toJSON(){
		return parent::toJSON() + json_encode($this);
	}
}

?>
