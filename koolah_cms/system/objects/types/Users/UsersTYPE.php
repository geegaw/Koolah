<?php

class UsersTYPE extends Nodes
{
    //CONSTRUCT	
    public function __construct( $db ){
    	parent::__construct( $db, USER_COLLECTION );	
    }
    
    /***
	 * FETCHERS
	 */
	public function users(){ return $this->nodes; }
	
	public function get( $q=null, $fields=null, $orderBy=array('last_name'=>1, 'first_name'=>2), $distinct=null  ){
		$bsonArray = parent::get( $q, $fields, $orderBy);
		if ( count($bsonArray) ){
			foreach ( $bsonArray as $bson ){
				$user = new UserTYPE( $this->db, $this->collection );
				$user->read( $bson );
				$this->append( $user );
			}
		}	
	}
	
	public function getSupers(){
		$supers = new UsersTYPE( $this->db );	
		if ( !$this->users() )
			$this->get();
		if ( $this->users() ){			
			foreach( $users as $user){
				if ( $user->isSuper() )
					$supers->append( $user );
			}
		}
		return $supers;
	}
	
	public function getAdmins( $filterSupers=false ){
		$admins = null;	
		if ( !$this->users() )
			$this->get();
		if ( $this->users() ){
			$supers = new UsersTYPE( $this->db );
			foreach( $users as $user){
				//is admin && if want to filter supers
				if ( $user->isAdmin() && !($filterSupers && $user->isSuper()) ){
						$admins->append( $user );
				}
			}
		}
		return $admins;
	}
	/***/
	
	
	/***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
		return array( 'users'=>parent::prepare() );
	}
	
	public function read( $bson ){
		if ( $bson && isset($bson['users']) ){
			$this->clear();			
			foreach ( $bson['users'] as $node )
				$this->append($node);
		}						
	}
	/***/ 
			   
}  
?>
