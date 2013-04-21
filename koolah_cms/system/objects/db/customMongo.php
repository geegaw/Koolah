<?php 
class customMongo {
	
	//PUBLIC		
	public $status;
	
	//PRIVATE		
	private $mongo;
	private $collection; 
	
	/***
	 * CONSTRUCTOR 
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
	
	protected function setCollection( $collection ){
		$this->collection = new MongoCollection($this->mongo, $collection);
	}
	
	/***
	 * GET 
	 */
	public function get( $collection, $q=null, $fields=null, $orderBy=null, $distinct=null ){
		self::setCollection( $collection );
		if ( $distinct ){
		    //NOTE: At the time of developement MongoCursor::distinct was not working
		    if( $q )
                $cursor = $this->mongo->command(array("distinct" => "$collection", "key" => "$distinct", "query" => $q));
            else    
                $cursor = $this->mongo->command(array("distinct" => "$collection", "key" => "$distinct"));
            debug::printr($cursor, 1);
        }
        elseif ( $q && $fields )
			$cursor = $this->collection->find( $q, $fields );
		elseif( $q )
			$cursor = $this->collection->find( $q );
		elseif ( $fields )
			$cursor = $this->collection->find( null, $fields );
		else
			$cursor = $this->collection->find();
        if( $orderBy )
            $cursor->sort( $orderBy );
		return iterator_to_array($cursor);			
	}	
	
	/***
	 * GETONE 
	 */
	public function getOne( $collection, $q, $fields=null ){
		self::setCollection( $collection );
		if ( $fields )
			return $collection->findOne( $q, $fields );
        return $this->collection->findOne( $q );
	}
	
	/***
	 * GET by ID 
	 */
	public function getByID( $collection, $id ){
		self::setCollection( $collection );
		$q = array( '_id'=>new MongoId($id) );
		return $this->collection->findOne( $q );
	}
	
	/***
	 * GET MongoID
	 * return string of id 
	 */
	public function getMongoID( $mongoID){
		return $mongoID->{'$id'};		
	}
	
	
	/***
	 * INSERT
	 * 
	 * NOTE: Unnecessary with mongo's save 
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
	
	/***
	 * BATCH INSERT 
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
	
	/***
	 * INSERT
	 * 
	 * NOTE: Useful over save for updating many records 
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
	
	/***
	 * SAVE
	 * NOTE: Handles insert or update 
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
	
	/***
	 * DEL -- remove
	 * NOTE: Handles insert or update 
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
		return $q;
	}
	
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
