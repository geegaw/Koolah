<?php

class UserPermissionsTYPE extends Nodes
{
	public $prefix;
	
    //CONSTRUCT	
    public function __construct( $db, $prefix='permissions'  ){
    	parent::__construct( $db, USER_COLLECTION );
		$this->prefix = $prefix;	
    }
    
    /***
	 * FETCHERS
	 */
    public function permissions(){ return $this->nodes; }
	public function get( $q=null, $fields=null, $orderBy=null, $distinct=null  ){
		$bsonArray = parent::get( $q, $fields, $orderBy);
		if ( count($bsonArray) ){
			foreach ( $bsonArray as $bson ){
				$permission = new UserPermissionTYPE( $this->db, $this->collection );
				$permission->read( $bson );
				$this->append( $permission );
			}
		}	
	}
	/***/
	
	/***
	 * BOOLS
	 */
	 
	public function hasPermission( $suspect ){
		$has = false;	
		if ( $this->permissions() ){
			foreach ( $permissions as $permission ){
				if ( $suspect == $permission->name )
					return true;
			}
			
		}
		return $has;
	} 
	 
	/***/
	
	/***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
		$permissions = null;
		if ($this->permissions()){
			foreach( $this->permissions() as $permission ){
				$permissions[] = $permission;
			}
		}
		return array( $this->prefix=>$permissions );
	}
	
	public function read( $bson ){
		if ( $bson && isset($bson[$this->prefix]) && count($bson[$this->prefix])){
			$this->clear();			
			foreach ( $bson[$this->prefix] as $permission ){
				$this->append( $permission );
			}				
		}						
	}
	/***/ 
			   
} 
?>