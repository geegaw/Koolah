<?php

class UserTYPE extends Node{
  		
  	private $username;
    private $name;
    private $password;
    private $active;
  	
  	public $roles;
	public $permissions;
	public $grantableRoles;
	public $grantablePermissions;
	public $history;
    
    //CONSTRUCT
    public function __construct( $db=null ){
		parent::__construct( $db, USER_COLLECTION );
		$this->username = '';
	    $this->name = '';
	    $this->password = '';
	    $this->active = true;
	  	$this->history = new UserHistoryTYPE( $db );
        
	  	$this->roles = new UserRolesTYPE( $db );
		$this->permissions = new UserPermissionsTYPE( $db );
		
		$this->grantableRoles = new UserRolesTYPE( $db, 'grantableRoles' );
		$this->grantablePermissions = new UserPermissionsTYPE( $db, 'grantablePermissions' );
	}
    
	/***
	 * GETTERS
	 */
	public function getUsername(){ return $this->username; }
	public function getName(){ return $this->name; }
	public function getPassword(){ return $this->password;}
	/***/
	
	/***
	 * SETTERS
	 */
	public function setUsername( $email, $active=false ){
		$status = new StatusTYPE();	
		if( UserTYPE::uniqueUsername($email, $active) )
			$this->username = $email;  
		else
			$status->setFalse('error: '.NON_UNIQUE);
		return $status; 
	}
	public function setName($name){ $this->name = $name; }
	public function setPassword($pass){ if( $pass ) $this->password = md5($pass); }
	/***/
	
	/***
	 * FETCHERS
	 */
	public function getByUsername($username=null)
    {
        if (!$username)
            $username = $this->username;
		
        parent::get( array('username'=>$username));
		return ( (bool)$this->id );        
    }
    /***/
	
	
	/***
	 * BOOLS
	 */
	public function isActive(){ return $this->active; }
	public function isInactive(){ return !$this->active; }
	public function can($permission){ return ($this->roles->can( $permission ) || $this->permissions->hasPermission( $permission )); }
	public function canGrant($permission){ return (  $this->roles->hasAdmin() || $this->grantableRoles->can( $permission ) || $this->grantablePermissions->hasPermission( $permission )); }
	public function isSuper(){ return $this->roles->hasSuper(); }
	public function isAdmin(){ return $this->roles->hasAdmin(); }
	/***/
	
	
	/***
	 * MODIFIERS
	 */
	public function save($bson=null ){
		if ( UserTYPE::uniqueUsername( $this->username, $this->active, $this->getID() ) )	
			return parent::save($bson);
		$status = new StatusTYPE( 'username already exists', false);
		return $status;
	}
	
	private function deactivate( $save=false ){
		 $this->active = false;
		 if ( $save )
		 	return $this->save();
	}
	
	public function reactivate( $save=false ){
		 $status = new StatusTYPE();	
		 if ( UsersTYPE::uniqueUsername( $this->username ) ){
		 	$this->active = true;
			if ( $save )
		 		return $this->save();
		 }
		 else
		 	$status->setFalse( 'error: '.USERNAME_HAS_BEEN_USED );		 
		 return $status; 
	}
	
	public function mkSuper($save=false){
		$this->roles->mkSuper();	
		if ( $save )
		 	return $this->save();
	}
	 
	public function delete(){ return $this->deactivate(true); }                           
    public function destroy(){ return parent::del(); }
	/***/
	
	
    /***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
		$bson = array(
			'username'=>$this->username,
			'name'=>$this->name,
			'password'=>$this->password,
			'active'=>$this->active,
		);
		return parent::prepare() + $bson 
			+ $this->roles->prepare() + $this->permissions->prepare() 
			+ $this->grantableRoles->prepare() + $this->grantablePermissions->prepare();		
	}
	
	public function read( $bson ){
		parent::read($bson);
		if ( isset($bson['username']) )
			$this->username = $bson['username'];
		if ( isset($bson['name']) )
			$this->name = $bson['name'];
		if ( isset($bson['password']) ){
			$this->password = $bson['password'];
			if ( strlen($this->password) < 32 )
				$this->setPassword($this->password);
		}
		if ( isset($bson['active']) )
			$this->active = $bson['active'];
		$this->roles->read( $bson );
		$this->permissions->read($bson);
		$this->grantableRoles->read( $bson );
		$this->grantablePermissions->read($bson);
	}
	
	/***
	 * Helpers
	 */
	public function uniqueUsername( $username, $active=false, $id=null )
    {
        $tmp = new UserTYPE( $this->db );
		if( $tmp->getByUsername( $username ) ){
			if ( $id && ( $id == $tmp->getID() ))
				return true;
			if( $active && !$tmp->isActive() )
				return true;
			return false;
		}
		return true;        
    }	
	
	public function verify( $username, $password )
    {
        $tmp = new UserTYPE( $this->db );
		if ( $this->isFirstTime($username, $password) ){
			header("Location:".FIRST_TIME_LOGIN);
			exit;
		}
		$tmp->getByUsername( $username );
		return ( 
			$tmp->getByUsername( $username ) 
			&& $tmp->isActive()
			&& ($tmp->password === md5( $password ))
		);      
    }
	
	private function isFirstTime($username, $password){
		list( $u, $p ) = explode(':', FIRST_TIME_LOGIN_UP);
		if (( $username == $u ) && ($password == $p)){
			$tmp = new UsersTYPE( $this->db );
			$supers = $tmp->getSupers();
			return 	!$supers->isEmpty();
		}
	}	
	/***/
	
	public function getByID( $id=null ){
        parent::getByID( $id );
        $this->history->getByUserID( $this->getID() );   
    }
    
    public function get( $q, $fields=null ){
        parent::get( $q, $fields );
        $this->getHistory();
    }
    
    public function getHistory( $distinct=false ){
        $this->history->getByUserID( $this->getID(), $distinct );
    }
    
    public function del(){
        $status = parent::del();
        if ( $status->success() )
            $status = $this->history->del();
        return $status;
    }
    
    public function updateHistory( $title, $url, $save=true ){
        return $this->history->update($this->getID(), $title, $url, $save);
    }
    
    public function updateHistoryAction( $action, $classname, $id, $save=true ){
        $this->history->getByUserID( $this->getID() );
        return $this->history->updateAction($action, $classname, $id, $save);
    }
}

?>
