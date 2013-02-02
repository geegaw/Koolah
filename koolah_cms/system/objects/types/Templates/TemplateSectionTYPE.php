<?php

class TemplateSectionTYPE extends Node{
    	
	//PUBLIC
    public $name = '';
    public $fields;
	
	//CONSTRUCT
    public function __construct( $db, $type="page" ){
		parent::__construct( $db, TEMPLATE_COLLECTION );	
		$this->fields = new FieldsTYPE( $db );		
	}
    
    public function mkInput( $page ){ return $this->fields->mkInput( $page ); }
    
	/*
	public function save($bson=null ){
		if ( !$this->id )	
			$this->label->setRef();
		return parent::save($bson);
	}*/
	
    /***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
		return parent::prepare() + array('name'=>$this->name) + $this->fields->prepare();		
	}


    public function read( $bson ){
//debug::printr($bson, true);     
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
    
    public function readAssoc( $bson ){
        if ( isset( $bson['name'] ))
            $this->name = $bson['name'];
        $this->fields->read($bson);            
    }
    
    public function readObj( $obj ){
        if ( $obj ){
//debug::vardump($obj);            
            $this->name = $obj->name;
            $this->fields->read($obj->fields);
        }      
    }
    
}
?>
