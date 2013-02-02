<?php
class Node{
	
	//PROTECTED
	protected $id = null;
	
	protected $collection;
	protected $db;
	
		
	public function __construct( $db=null, $collection='node'){
		if ( $db )    
            $this->db = $db;
        else{
            $conf = new config();
            $this->db = $conf->cmsMongo;
        }		
		$this->collection = $collection;
	}
	
	public function getID(){ return $this->id; }
	public function setID($id){ $this->id= $id; }
	
	public function getByID( $id=null ){
		if ( !$id)
			$id = $this->id;
		$bson = $this->db->getByID( $this->collection, $id );
		self::read( $bson );
		$this->read( $bson );	
	}
	
	public function get( $q, $fields=null ){
		$bson = $this->db->getOne( $this->collection, $q, $fields );
		self::read( $bson );
		$this->read( $bson );
	}
	
	/***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
		$bson= array( 'meta'=> $this->meta->prepare());	
		if ( $this->id )
			$bson['_id']=new MongoId($this->id);
		return $bson;
	}
	
    public function read( $bson ){
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
//debug::printr($bson);        
        if ( isset($bson['_id']) )
            $this->id= $bson['_id']->{'$id'};
        if ( isset($bson['collection']) )
            $this->collection = $bson['collection'];                
    }
    
    public function readObj( $obj ){
        if ( $obj ){
//debug::vardump($obj);
            if ( property_exists($obj, 'id') )        
                $this->id= $obj->id;
            if ( property_exists($obj, 'meta') )
                $this->meta->read( $obj->meta);
            if ( property_exists($obj, 'collection') )
                $this->collection = $obj->collection;
        }       
    }
    
    public function readJSON( $json ){
        $bson = json_decode($json);
        self::read( $bson );
    }
	
}

?>