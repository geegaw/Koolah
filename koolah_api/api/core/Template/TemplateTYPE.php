<?php

class TemplateTYPE extends Node{
    	
	//PUBLIC
    public $label;
	
	//PRIVATE
	private $templateType = null;
	
    //CONSTRUCT
    public function __construct( $db=null ){
		parent::__construct( $db, TEMPLATE_COLLECTION );	
		$this->label = new LabelTYPE( $db, TEMPLATE_COLLECTION );
	}
    
    public function getType(){ return $this->templateType; }
    public function setType($type){
         if ( in_array( $type, self::getTypes() ) )    
            $this->templateType = $type; 
    }
    
    /***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
		$bson = array('templateType'=>$this->templateType);
		return parent::prepare() + $bson + $this->label->prepare();		
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
//debug::printr($bson);
        if ( isset($bson['templateType']) )
            $this->templateType = $bson['templateType'];
        $this->label->read( $bson );
    }
    
    public function readObj( $obj ){
        if ( $obj ){
//debug::printr($obj, true);            
            $this->templateType = $obj->templateType;            
            $this->label->read( $obj->label );
        }        
    }
    
	/***
	 * Helpers
	 */
	
	/***
     *
     *  
     * NOTE: if adding types also must add in TemplateTYPE.js 
     */
	public static function getTypes(){
		$types = array(
					'page', 
					'widget',
					'field'
				);
		return $types;
	}
}
?>
