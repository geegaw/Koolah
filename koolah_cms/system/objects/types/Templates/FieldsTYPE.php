<?php

class FieldsTYPE extends Nodes{
    
    //CONSTRUCT
    public function __construct( $db, $collection = FIELD_COLLECTION ){
		parent::__construct( $db, $collection );
    }
	
	//GETTERS
	public function fields(){ return $this->nodes; }
	
    
    public function mkInput($page){
        $html = '';
        if ( $this->length() ){
            foreach( $this->fields() as $field )
                $html.= $field->mkInput( $page );
        }
        return $html;
    }
    
	/***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
		return array( 'fields'=>parent::prepare() );
	}
/*	
	public function read( $bson ){
		if ( $bson && isset($bson['fields']) ){
			$this->clear();			
			foreach ( $bson['fields'] as $field_bson ){
				$field = new FieldTYPE( $this->db );
				$field->read( $field_bson );
				$this->append( $field );	
				//var_dump($node);die;
			}
				//
		}						
	}
*/
    public function read( $bson ){
//debug::vardump($bson);            
        $this->clear();        
        if ( $bson ){
            if (is_string($bson)){
                $bson = json_decode($bson);
                return self::read($bson);
            }
            elseif ( is_array($bson) && isset($bson['fields']) )
                $fields = $bson['fields'];
            elseif( is_object($bson) )
                $fields = $bson->fields;    
            else 
                $fields = $bson;
//debug::vardump($fields);             
            foreach ( $fields as $field_bson ){
                $field = new FieldTYPE( $this->db );
                $field->read( $field_bson );
                $this->append( $field );    
            }                 
        }
    } 
	/***/ 	   
}

 