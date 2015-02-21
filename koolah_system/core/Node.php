<?php
/**
 * NodeTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * NodeTYPE
 * 
 * most objects will extend Node
 * Node contains most methods necc to interact with db
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\core
 */ 
class Node{
    
    /**
     * id
     * @var string
     * @access  protected
     */
    protected $id = null;
    
    /**
     * meta information
     * @var MetaTYPE
     * @access  protected
     */
    protected $meta;
    
    /**
     * collection name
     * @var string
     * @access  protected
     */
    protected $collection;
    
    /**
     * db connection
     * @var customMongo
     * @access  protected
     */
    protected $db;
    
    /**
     * constructor
     * can customize db but defaults to the config
     * collection defualts to node 
     * @param customMongo $db 
     * @param string $collection
     */    
    public function __construct( $db=null, $collection='node'){
        if ( $db )    
            $this->db = $db;
        else{
            $conf = new config();
            $this->db = $conf->cmsMongo;
        }       
        $this->collection = $collection;
        $this->meta = new MetaTYPE();       
    }
    
	public function getDB(){ return $this->db; }
	public function getCollection(){ return $this->collection; }
	
    /**
     * getID
     * @access  public
     * @return string 
     */
    public function getID(){ return $this->id; }
    
    /**
     * getMeta
     * @access  public
     * @return MetaTYPE 
     */
    public function getMeta(){ return $this->meta; }
    
    /**
     * setID 
     * @access  public
     * @param string $id 
     */
    public function setID($id){ $this->id= $id; }
    
    
    /**
     * getByID
     * gets from db by id in internal collection
     * sets its own properties
     * and then sets extentions properties
     * @access  public
     * @param string $id
     */
    public function getByID( $id=null ){
        if ( !$id)
            $id = $this->id;
        $bson = $this->db->getByID( $this->collection, $id );
        self::read( $bson );
        $this->read( $bson );   
    }
    
    /**
     * get
     * gets from db by query
     * and get fields specified all if left empty
     * @access  public
     * @param string $q
     * @param array|string $fields
     */
    public function get( $q, $fields=null ){
        $bson = $this->db->getOne( $this->collection, $q, $fields );
        self::read( $bson );
        $this->read( $bson );
    }
    
    /**
     * save
     * saves objects to db
     * if no bson is passed it saves itself
     * if creating object it sets its id to returned value from save
     * @access  public
     * @param assocArray $bson
     * @return StatusTYPE
     */
    public function save( $bson=null ){
        //TODO add this back
        //$this->meta->modificationHistory->update();
        if ( !$bson )
            $bson = $this->prepare();
        list( $status, $id ) = $this->db->save( $this->collection, $bson );
        if (!$this->id )
            $this->id = $id;
        return $status;
            
    }
    
    /**
     * del
     * deletes self from db
     * @access  public
     * @return StatusTYPE
     */
    public function del(){
        $status = new StatusTYPE();
        if ( $this->id ){
            $id = new MongoId($this->id);
            $criteria = array( '_id'=> $id );
            $status = $this->db->del( $this->collection, $criteria );
        }
        else
            $status->setFalse('Error: Could not delete, not enough information passed');
        return $status;
    }
    
    
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
        $bson= array( 'meta'=> $this->meta->prepare()); 
        if ( $this->id )
            $bson['_id']=new MongoId($this->id);
        return $bson;
    }
    
    /**
     * export
     * prepares for sending to another user
     * @access  public
     * @return array
     */
    public function export(){
        return array();
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
     * converts assocArray into Node
     * @access  public
     * @param assocArray $bson
     */
    public function readAssoc( $bson ){
        if ( isset($bson['_id']) )
            $this->id= $bson['_id']->{'$id'};
        if ( isset($bson['meta']) )
            $this->meta->read( $bson['meta'] );
        if ( isset($bson['collection']) )
            $this->collection = $bson['collection'];                
    }
    
    /**
     * readObj
     * converts object into Node
     * @access  public
     * @param object $obj
     */
    public function readObj( $obj ){
        if ( $obj ){
            if ( property_exists($obj, 'id') )        
                $this->id= $obj->id;
            if ( property_exists($obj, 'meta') )
                $this->meta->read( $obj->meta);
            if ( property_exists($obj, 'collection') )
                $this->collection = $obj->collection;
        }       
    }
    
    /**
     * readJson
     * converts JSON into Node
     * @access  public
     * @param JSON string $json
     */
    public function readJSON( $json ){
        $bson = json_decode($json, true);
        self::read( $bson );
    }
    
    /**
     * toJSON
     * prepares for json
     * @access  public
     * @return string
     */
    public function toJSON(){
        return json_encode(
            array(
                'classname'=> get_class($this), 
                'data' => $this->export()
            )
        );
    }
    
    
    
}

?>