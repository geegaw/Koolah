<?php

class RoleTYPE extends Node{
  		
  	const SUPER_USER = 'superuser';
	const ADMIN = 'admin';
	
	public $label;
    public $permissions;
	
    //CONSTRUCT
    public function __construct( $db ){
		parent::__construct( $db, ROLES_COLLECTION );
		$this->label = new LabelTYPE( $db, ROLES_COLLECTION );
		//$this->permissions = new UserPermissionsTYPE( $db );
		$this->permissions = null;
	}
    
	/***
	 * FETCHERS
	 */
	/*public function get( $ref )
    {
        parent::get( array('username'=>$this->username));
		return ( (bool)$this->id );        
    }*/
    /***/
	
	
	/***
	 * BOOLS
	 */
	public function isSuper(){ return $this->label->getRef() === RoleTYPE::SUPER_USER; }
	public function isAdmin(){ return ($this->isSuper() || $this->label->getRef() === RoleTYPE::ADMIN); }
	public function can($permission){ return ($this->isAdmin() || ($this->permissions && in_array($permission, $this->permissions))); } 
	/***/
	
	
	/***
	 * MODIFIERS
	 */
	public function mkSuper( $save=false ){
		 $this->id = RoleTYPE::SUPER_USER;	
		 $this->label->label = RoleTYPE::SUPER_USER;
		 $this->label->setRef( RoleTYPE::SUPER_USER );
		 if ( $save )
		 	return $this->save();
	}
	public function mkAmin( $save=false ){
		 $this->id = RoleTYPE::ADMIN;	
		 $this->label->label = RoleTYPE::ADMIN;
		 $this->label->setRef( RoleTYPE::ADMIN );
		 if ( $save )
		 	return $this->save();
	}
	
	public function clear( $save=false ){
        $this->label->clear();
        $this->permissions->clear();
		if ( $save )
		 	return $this->save();
    }
	/***/
	
	
    /***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
		if ( !$this->id )
			$this->label->setRef();	
		$bson['permissions'] = $this->permissions;
		return parent::prepare() + $bson + $this->label->prepare();		
	}
	
	public function read( $bson ){
		parent::read($bson);
		$this->label->read( $bson );
		if ( $bson['permissions'] )
			$this->permissions = $bson['permissions'];
		//$this->permissions->read($bson);
	}
	
	/***
	 * Helpers
	 */
	/***/
}


?>
