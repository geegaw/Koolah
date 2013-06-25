<?php
/**
 * LabelTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * LabelTYPE
 * 
 * easily include both a label and unique reference to refer by
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\core
  */
class LabelTYPE{
		
	/**
     * easy display label 
     * @var string  
     * @access  public
     */
	public $label;
	
    /**
     * unique value that allows you to call by
     * if label is modified ref remains its orig value
     * @var string
     * @access  private
     */
    private $ref = null;
	
    /**
     * db connector
     * @var customMongo  
     * @access  private
     */
	private $db;
    
    /**
     * collection name
     * @var string 
     * @access  private
     */
	private $collection; 
	
	
	/**
     * constructor
     * @param customMongo $db
     * @param string $collection
     * @param string $label
     */
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
	
	/**
     * getRef
     * @access  public
     * @return string 
     */
	public function getRef(){ return $this->ref; }
	
	/**
     * setRef
     * if no reference passed label is used
     * formats the string and finds appropriate unused reference name
     * @access  public
     * @param string $ref 
     */
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
	
    /**
     * clear
     * emptys label and ref values
     * @access  public
     */
	public function clear(){
		$this->label = '';
		$this->ref = null;
	}
	
	/**
     * get
     * gets by $ref and reads into self
     * @access  public
     * @param string $ref
     */
	public function get( $ref=null ){
		if ( !$ref )
			$ref = $this->ref;
		if ( $ref ){
			$q = array('ref' => $ref);
			$bson = $this->db->getOne( $this->collection, $q );
			$this->read( $bson );
		}		
	}	
	
	/**
     * exists
     * checks to see if ref already exists in collection
     * @access  public
     * @param string $ref
     * @param customMongo $db
     * @param string $collection
     * @return bool
     */
	public function exists( $ref, $db=null, $collection=null ){
		if (!$db)
			$db = $this->db;
		if ( !$collection )
			$collection = $this->collection;
		
		$label = LabelTYPE::getByRef( $db, $collection, $ref ); 	
		return $label->getRef() !== null;
	}
	
    /**
     * getByRef
     * gets from db by ref in collection
     * different from get, in that you can get a new label with this method
     * @access  public
     * @param customMongo $db
     * @param string $collection
     * @param string $ref
     * @return LabelTYPE
     */
	public function getByRef( $db, $collection, $ref ){
		$label = new LabelTYPE( $db, $collection ); 	
		if ( $ref ){
			$label->get( $ref );			
		}
		return $label;
	}
	
    /**
     * format
     * lowercases, replaces all spaces with _
     * removes all chars except letters, numbers and _
     * @access  public
     * @param string $s
     * @return string
     */
	public function format( $s ){
	    if (is_string($s)){    
    	    $s = strtolower($s);	
    		$s = str_replace(' ', '_', $s);
    		$s = preg_replace("[^A-Za-z0-9_]", "", $s );
        }
        return $s;
	}
	
    /**
     * display
     * echos label
     * @access  public
     */
	public function display(){
		echo $this->label;
	}
	
	/**
     * prepare
     * prepares for sending to db
     * if no ref it creates one
     * @access  public
     * @return assocArray
     */
    public function prepare(){
		if ( !$this->ref )
			self::setRef();	
		return array ( 'label'=>$this->label, 'ref'=>$this->ref ); 
	}	
	
    /**
     * export
     * prepares for sending to another user
     * @access  public
     * @return assocArray
     */
    public function export(){
        return array ( 'label'=>$this->label, 'ref'=>'' ); 
    }
    
    /**
     * read
     * reads from db
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
            self::readJSON( $bson );
        else 
            // TODO return error
            return;  
    }
    
    /**
     * readAssoc
     * converts assocArray into LabelTYPE
     * @access  public
     * @param assocArray $bson
     */
    public function readAssoc( $bson ){
        if ( isset($bson['label']) )
            $this->label = $bson['label'];
        if ( isset($bson['ref']) )
            $this->ref = $bson['ref'];      
    }
    
    /**
     * readObj
     * converts object into LabelTYPE
     * @access  public
     * @param object $obj
     */
    public function readObj( $obj ){
        if ( $obj ){
            $this->label = $obj->label;
            $this->ref = $obj->ref;
        }        
    }
	
    /**
     * readJson
     * converts JSON into LabelTYPE
     * @access  public
     * @param JSON string $json
     */
	public function readJSON( $json ){
        $bson = json_decode($json);
        self::read( $bson );
    }
}
?>