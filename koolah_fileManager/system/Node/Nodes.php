<?php
class Nodes{
	//PROTECTED	
	protected $nodes = null;
	protected $numNodes = 0;	
	protected $db;
	protected $collection;
	/***/
	
	//CONSTRUCT
	public function __construct( $db=null, $collection='node'){
		if ( $db )    
            $this->db = $db;
        else{
            $conf = new config();
            $this->db = $conf->cmsMongo;
        }   		
		$this->collection = $collection;				
	}
	/***/
	
	/**
	 * GETTERS 
	 */
	public function length(){ return $this->numNodes; }
	/***/
	
	/**
	 * SETTERS 
	 */
	public function set( $nodes ){
		$this->clear();
		if ( count($nodes) ){
			foreach ( $nodes as $nodes )
				$this->append( $nodes );
		}		
	}
	
	public function clear(){
		$this->nodes = null;
		$this->numNodes = 0;		
	}
	
	public function append( $node ){
		$this->nodes[] = $node;
		$this->numNodes++;		
	}
	
	public function remove( $node ){
		$status = new StatusTYPE();
        if ( $node ){
            $found = false;                                  
            for ( $i = 0; ($i < $this->numNodes) && !$found; $i++ ){
                if ( $node->getID() == $this->nodes[$i]->getID() ){
                    $this->numNodes--;
                    unset( $this->nodes[$i] );
                    $found = true;                    
                }
            }
        }
        else
            $status->setFalse( 'invalid field passed' );        
        return $status;    
	}
	/***/
	
	//LOOKUP
	public function find( $suspect ){
		$objs = array();
        if ( $this->nodes ){
            foreach( $this->nodes as $node ){
                if ( property_exists($node, 'compare') && $node->compare($suspect) ){
                    $objs[] = $node;
                }
                    
            }
        }	
        return $objs;	
	}
    
    public function get( $q=null, $fields=null, $orderBy=null, $distinct=null ){
        return $this->db->get( $this->collection, $q, $fields, $orderBy, $distinct );       
    }
    
    //public function distinct( $by, $q=null, $orderBy=null ){
    //    return $this->db->get( $this->collection, $q, null, $orderBy, $by );      
    //}
	/***/
	
	
	/***
	 * BOOLS
	 */
	public function isNotEmpty(){ return (bool)$this->numNodes; }
	public function isEmpty(){ return !$this->isNotEmpty(); } 
	/***/
	
	 
	/**
	 * DB OPERATIONS
	 */
	/***/
	
	/***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
		$bson = null;
		if ( $this->numNodes ){
			foreach ( $this->nodes as $node )
				if( @get_class( $node ) )
					$bson[] = $node->prepare();
		}
		return $bson;
	}
	
	public function read( $bson ){
		if ( $bson ){
			$this->clear();			
			foreach ( $bson as $node )
				$this->append($node);
		}						
	}
	/***/
}

?>