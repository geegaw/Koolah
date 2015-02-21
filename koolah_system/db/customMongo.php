<?php 
/**
 * customMongo
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * customMongo
 * 
 * mongodb easy php class
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\db
  */
class customMongo{
	
	/**
     * status of connection
     * @var status
     * @access  public
     */		
	public $status;
	
	/**
     * mongo connection to db
     * @var mongo
     * @access  private
     */			
	private $mongo;
	
    /**
     * collection 
     * @var collection
     * @access  private
     */			
	private $collection; 
	
	/**
     * constructor
     * intialize db connection, set status to false if unable to connect     
     * @param string $db
     * @param string $u - username
     * @param string $p - password
     * @param string $host
     * @param string $replicaSet
     * @param string $port                          
    */    
    public function __construct( $db='tmp', $u=null, $p=null,  $host='localhost', $replicaSet=null, $port=null ){
			
		if ($u && $p)
			$server = "mongodb://$u:$p@$host";			
		else
			$server = "mongodb://$host";
			
		if ( $port )
			$server.=":$port";
		$server.="/db";
		
		$options = null;			
		if ( $replicaSet )
			$options['replicaSet']= $replicaSet;
		
		$this->status = new StatusTYPE();
		try{
			if ( $options )
				$m = new Mongo( $server, $options );
			else
				$m = new Mongo( $server );
			$this->mongo = $m->selectDB($db);			
		}
		catch( MongoConnectionException $e){
			$this->status->setFalse("Error: Could not connect to DB:[$e]");
		}		
	}
	
	/**
     * setCollection
     * set new collection as MongoCollection
     * @uses MongoCollection     
     * @access  protected
     * @param string $collection
     */    
    protected function setCollection( $collection ){
		$this->collection = new MongoCollection($this->mongo, $collection);
	}
	
	/**
     * get
     * get from mongodb in collection
     * @access  public
     * @param string $collection
     * @param assocArray $q -- query
     * @param array $fields
     * @param array $orderBy
     * @param bool $distinct
     * @return array                         
     */    
    public function get( $collection, $q=null, $fields=null, $orderBy=null, $offset=0, $limit=null, $distinct=null ){
    	self::setCollection( $collection );
		if ($q)
			$q = $this->formatQuery($q);

		if ( $distinct ){
		    //NOTE: At the time of developement MongoCursor::distinct was not working
		    if( $q )
                $cursor = $this->mongo->command(array("distinct" => "$collection", "key" => "$distinct", "query" => $q));
            else    
                $cursor = $this->mongo->command(array("distinct" => "$collection", "key" => "$distinct"));
        }
        elseif ( $q && $fields )
			$cursor = $this->collection->find( $q, $fields );
		elseif( $q )
			$cursor = $this->collection->find( $q );
		elseif ( $fields )
			$cursor = $this->collection->find( null, $fields );
		else
			$cursor = $this->collection->find();
		
		if ( $limit )
			$cursor->skip($offset)->limit($limit);
        if( $orderBy )
            $cursor->sort( $orderBy );

        return array(
			'nodes'=> iterator_to_array($cursor),
			'total'=>$this->collection->count(),
		);			
	}	
	
	/**
	 * formatQuery
	 * format query to search for like
	 */
	public function formatQuery($q){
		$formatted = array();
		if (is_array($q)){
			foreach ($q as $term => $query){
				if (is_array($query))
					$formatted[$term] = $this->formatQuery($query);
				elseif( preg_match( '/^\/([^\/]*)\/[ig]*$/', $query ) )
					$formatted[$term] = array('$regex' => new MongoRegex($query));
				elseif( !$query )//is null search
					$formatted[$term] = array('$type' => 10);						
				else
					$formatted[$term] = $query;
			}
		}
		else
			return $q;
		return $formatted;
	}
	
	/**
     * getOne
     * get one object from mongodb in collection
     * @access  public
     * @param string $collection
     * @param assocArray $q -- query
     * @param array $fields
     * @return assocArray                         
     */    
    public function getOne( $collection, $q, $fields=null ){
		self::setCollection( $collection );
		if ( $fields )
			return $this->collection->findOne( $q, $fields );
        return $this->collection->findOne( $q );
	}
	
	/**
     * getByID
     * get one object from mongodb in collection by an id
     * @access  public
     * @param string $collection
     * @param string $id
     * @return assocArray                         
     */    
    public function getByID( $collection, $id ){
		self::setCollection( $collection );
		$q = array( '_id'=>new MongoId($id) );
		return $this->collection->findOne( $q );
	}
	
	/**
     * getMongoID
     * get string id from MongoId
     * @access  public
     * @param string $mongoID -- MongoId
     * @return string                          
     */    
    public function getMongoID( $mongoID ){
		return $mongoID->{'$id'};		
	}
	
	
	/**
     * insert
     * insert new object into collection
     * NOTE: Unnecessary with mongo's save     
     * @access  public
     * @param string $collection
     * @param assocArray $bson
     * @param bool $safe          
     * @return StatusTYPE                          
     */    
    public function insert( $collection, $bson, $safe=true ){
		self::setCollection( $collection );	
		$status = new StatusTYPE();			
		try{
			$this->collection->insert( $bson, array('safe'=>$safe) );
		}	
		catch( MongoConnectionException $e){
			$status->setFalse("Error: Could not insert to DB:[$e]");
		}
		return $status;			
	}
	
	/**
     * batchInsert
     * batch insert new objects into collection
     * @access  public
     * @param string $collection
     * @param assocArray $bson
     * @param bool $safe          
     * @return StatusTYPE                          
     */    
    public function batchInsert( $collection, $bson, $safe=true ){
		self::setCollection( $collection );	
		$status = new StatusTYPE();					
		try{
			$this->collection->batchInsert( $bson, array('safe'=>$safe) );
		}	
		catch( MongoConnectionException $e){
			$status->setFalse("Error: Could not batch insert to DB:[$e]");
		}
		return $status;			
	}
	
	/**
     * update
     * update object in collection
     * NOTE: Useful over save for updating many records     
     * @access  public
     * @param string $collection
     * @param array $criteria     
     * @param assocArray $bson
     * @param bool $multiple
     * @param bool $safe          
     * @return StatusTYPE                          
     */    
    public function update( $collection, $criteria, $bson, $multiple=true, $safe=true ){
		self::setCollection( $collection );	
		$status = new StatusTYPE();			
		try{
			$this->collection->update( $criteria, $bson, array('multiple'=>$multiple, 'safe'=>$safe) );
		}	
		catch( MongoConnectionException $e){
			$status->setFalse("Error: Could not update to DB:[$e]");
		}
		return $status;			
	}
	
	/**
     * save
     * save will insert or update an object into collection     
     * @access  public
     * @param string $collection
     * @param assocArray $bson
     * @param bool $safe          
     * @return StatusTYPE                          
     */    
    public function save( $collection, $bson, $safe=true ){
		self::setCollection( $collection );	
		$status = new StatusTYPE();
		$id = null;			
		try{
		    $this->collection->save( $bson );
			$id = $bson['_id']->{'$id'};		
		}
		catch( MongoConnectionException $e){
			$status->setFalse("Error: Could not save to DB:[$e]");			
		}
		return array($status, $id);			
	}
	
	/**
     * del
     * deletes an object from collection     
     * @access  public
     * @param string $collection
     * @param array $criteria     
     * @param bool $justOne -- mysql equivalent to limit 1
     * @param bool $safe          
     * @return StatusTYPE                          
     */    
    public function del( $collection, $criteria, $justOne=true, $safe=true ){
		self::setCollection( $collection );	
		$status = new StatusTYPE();
		if ( $collection && $criteria ){			
			try{
				$this->collection->remove( $criteria, array('justOne'=>$justOne, 'safe'=>$safe) );
			}	
			catch( MongoConnectionException $e){
				$status->setFalse("Error: Could not update to DB:[$e]");
			}
		}
		else
			$status->setFalse("Error: Not enough information passed to delete");
		return $status;			
	}
	
	/**
     * jsParseWhere
     * parses a javascript passed query and turns it into an mongodb
     * assocArray query          
     * @access public
     * @param array $args     
     * @return assocArray                          
     */    
    static public function jsParseWhere( $args ){
		$q = null;	
		if( is_array($args) ){
			foreach( $args as $arg ){
				$parts = explode( '=', $arg );
				if ( count($parts)  > 1  ){
				    if ($parts[1] == 'null')
                        $parts[1] = null;
					$q[(string)$parts[0]] = $parts[1];
				}
			}
		}
		//elseif (is_object($args))
		//	$q = json_decode(json_encode($args), true);
		debug::printr($args, 1);
		return $q;
	}
	
    
    /**
     * cleanBson
     * cleans extrenious characters from bson
     * assocArray query          
     * @access public
     * @param assocArray $bson     
     * @return assocArray                          
     */    
    static public function cleanBson( $bson ){
        $cleanBson = $bson;    
        if ( is_array( $bson ) ){
            $cleanBson = null;   
            foreach( $bson as $k => $v ){
                $k = customMongo::cleanBson($k);
                $v = customMongo::cleanBson($v);
                $cleanBson[$k]=$v;
            }
        }
        elseif( is_string($bson) ){
            $cleanBson = str_replace('""', null, $bson);
        }
        return $cleanBson;
    }
}

?>
