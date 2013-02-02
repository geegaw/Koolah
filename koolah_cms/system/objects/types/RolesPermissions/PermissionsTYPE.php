<?php

class PermissionsTYPE extends Nodes
{
    //CONSTRUCT	
    public function __construct( $db  ){
    	parent::__construct( $db, USER_COLLECTION );	
    }
    
	public function loadPermissions(){
		include( CONF.'/permissions.php' );
		debug::printr($permissions, true);
	}
	
    /***
	 * FETCHERS
	 */
    public function permissions(){ return $this->nodes; }
	public function get( $q=null, $fields=null, $orderBy=null, $distinct=null  ){
		$bsonArray = parent::get( $q, $fields);
		if ( count($bsonArray) ){
			foreach ( $bsonArray as $bson ){
				$permission = new PermissionTYPE( $this->db, $this->collection );
				$permission->read( $bson );
				$this->append( $permission );
			}
		}	
	}
	/***/
	
	/***
	 * BOOLS
	 */
	/***/
	
	/***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
		return array( 'permissions'=>parent::prepare() );
	}
	
	public function read( $bson ){
		if ( $bson && isset($bson['permissions']) ){
			$this->clear();			
			foreach ( $bson['permissions'] as $node ){
				$permission = new PermissionTYPE( $this->db, $this->collection );
				$permission->read( $bson );
				$this->append( $permission );
			}				
		}						
	}
	/***/ 
		
	
	/***
	 * HELPERS FUNCTIONS
	 */	   
	public function sortByCat()
    {
        if ($this->length)
        {
            $tmp = null;
            foreach ( $this->permissions as $perm){
            	$parts = explode( '_', $perm->getLabel() );
				foreach ( $parts as $level ){
					
				}	
            	$tmp[$perm->category][] = $perm;
            }
                
            return $tmp;
        }
    }
	/***/
} 
?>