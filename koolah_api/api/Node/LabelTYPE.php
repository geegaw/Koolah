<?php

class LabelTYPE{
		
	//PUBLIC
	public $label;
	
	//PRIVATE
	private $ref = null;
	
	private $db;
	private $collection; 
	
	
	//CONSTRUCT
	public function __construct( $db=null, $collection, $label='' ){
		if ( $db )    
		  $this->db = $db;
        else{
            $conf = new config();
            $this->db = $conf->cmsMongo;
        }
		$this->collection = $collection;
		
		$this->label = $label;		
	}
	
	//GETTER
	public function getRef(){ return $this->ref; }
	
	//SETTER
	public function setRef( $ref=null ){
		if ( !$ref )
			$ref = $this->label;
		$ref = $this->format( $ref );
		
		$i = 0;
		$checker = $ref;
		while( $this->exists($checker) ){
			$checker = $ref.$i;
			$i++;
		}		
		$this->ref = $ref;				
	}
	
	public function clear(){
		$this->label = '';
		$this->ref = null;
	}
	
	//LOOKUP
	public function get( $ref=null ){
		if ( !$ref )
			$ref = $this->ref;
		if ( $ref ){
			$q = array('ref' => $ref);
			$bson = $this->db->getOne( $this->collection, $q );
			$this->read( $bson );
		}		
	}	
	
	//HELPERS
	public function exists( $ref, $db=null, $collection=null ){
		if (!$db)
			$db = $this->db;
		if ( !$collection )
			$collection = $this->collection;
		
		$label = LabelTYPE::getByRef( $db, $collection, $ref ); 	
		return $label->getRef() !== null;
	}
	
	public function getByRef( $db, $collection, $ref ){
		$label = new LabelTYPE( $db, $collection ); 	
		if ( $ref ){
			$label->get( $ref );			
		}
		return $label;
	}
	
	public function format( $s ){
	    if (is_string($s)){    
    	    $s = strtolower($s);	
    		$s = str_replace(' ', '_', $s);
    		$s = preg_replace("[^A-Za-z0-9_]", "", $s );
        }
        return $s;
	}
	
	public function display(){
		echo $this->label;
	}
	
	/***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
		if ( !$this->ref )
			self::setRef();	
		return array ( 'label'=>$this->label, 'ref'=>$this->ref ); 
	}	
	
    public function read( $bson ){
//debug::printr($bson);     
        if ( is_array($bson) )
            self::readAssoc($bson);
        elseif( is_object($bson) )
            self::readObj( $bson );
        elseif( is_string($bson) )
            self::readJSON( $bson );
        else 
            // TODO return error
            return;  
    }
    
    public function readAssoc( $bson ){
        if ( isset($bson['label']) )
            $this->label = $bson['label'];
        if ( isset($bson['ref']) )
            $this->ref = $bson['ref'];      
    }
    
    public function readObj( $obj ){
        if ( $obj ){
            $this->label = $obj->label;
            $this->ref = $obj->ref;
        }        
    }
	
	public function readJSON( $json ){
        $bson = json_decode($json);
        self::read( $bson );
    }
}
?>