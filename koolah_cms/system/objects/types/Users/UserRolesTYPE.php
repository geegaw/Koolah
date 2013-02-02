<?php

class UserRolesTYPE extends RolesTYPE
{
	public $prefix;
	
	public function __construct( $db, $prefix = 'roles' ){
		parent::__construct($db);
		$this->prefix = $prefix;
	}
	
	
	/***
	 * BOOLS
	 */
	public function hasSuper(){
		if ( $this->roles() ){
			foreach( $this->roles() as $role ){
				if( $role->isSuper() )
					return true;
			}
		}
	}
	
	public function hasAdmin( $filterSupers=false ){
		if ( $this->roles() ){
			foreach( $this->roles() as $role ){
				if( $role->isAdmin() && !($filterSupers && $role->isSuper()) )
					return true;
			}
		}
	}
	/***/ 
	
	/***
	 * MODIFIERS
	 */
	 
	public function mkSuper($save=false){
		$super = new RoleTYPE( $this->db );
		$super->mkSuper();
		$this->append( $super );
		
		if ( $save )
		 	return $this->save();
	}
	
	public function mkAdmin($save=false){
		$admin = new RoleTYPE( $this->db );
		$admin->mkAdmin();
		$this->append( $admin );

		if ( $save )
		 	return $this->save();
	}
	/**/
	
	
	 
    /***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
		$roles = null;	
		if ($this->roles() ){
			foreach ( $this->roles() as $role )
				//$roles[]= new MongoId($role->getID());
				$roles[]= $role->getID();
		}
		return array( $this->prefix=>$roles );
	}
	
	public function read( $bson ){
		if ( $bson && isset($bson[$this->prefix]) ){
			$this->clear();			
			foreach ( $bson[$this->prefix] as $nodeID ){
				$role = new RoleTYPE( $this->db, $this->collection );
				if ( $nodeID === RoleTYPE::SUPER_USER )
					$role->mkSuper();
				elseif ( $nodeID === RoleTYPE::ADMIN )
					$role->mkAmin();
				else	
					$role->getByID( $nodeID );
				$this->append( $role );
			}				
		}	
	}
	/***/ 
			   
}