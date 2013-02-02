<?php

class TemplatesTYPE extends Nodes
{
    //CONSTRUCT	
    public function __construct( $db=null, $collection = TEMPLATE_COLLECTION ){
    	parent::__construct( $db, $collection );	
    }
    
    //GETTERS
	public function templates(){ return $this->nodes; }
	
	
	//GETTERS
	public function get( $q=null, $fields=null, $orderBy=array('label'=>1), $distinct=null  ){
		$bsonArray = parent::get( $q, $fields , $orderBy);
		if ( count($bsonArray) ){
			foreach ( $bsonArray as $bson ){
				$template = new TemplateTYPE( $this->db, $this->collection );
				$template->read( $bson );
				$this->append( $template );
			}
		}	
	}
    
    public function getType( $type ){
        self::get( array('templateType'=>$type) );
    }
    public function getPageTemplates(){ $this->getType('page'); } 
	public function getWidgetTemplates(){ $this->getType('widget'); }
    public function getFieldTemplates(){ $this->getType('field'); }
	
	/***
	 * MONGO FUNCTIONS
	 */
	
	public function read( $bson ){
		if ( $bson && isset($bson['fields']) ){
			$this->clear();			
			foreach ( $bson['fields'] as $node )
				$this->append($node);
		}						
	}
	/***/ 
			   
} 