<?php
/**
 * UserTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * UserTYPE
 * 
 * class to handle user information and actions
 * and roles and permissions
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Users
 */
class UserTYPE extends Node{
  		
  	/**
     * username should be an email
     * @var string
     * @access private
     */
    private $username;
    
    /**
     * users name, ex Christophe Vaugeois
     * @var string
     * @access private
     */
    private $name;
    
    /**
     * password
     * @var string
     * @access private
     */
    private $password;
    
    /**
     * if user is active or not
     * NOTE: only super users can 
     * permanately delete a user
     * they become inactive
     * when other users delete a user
     * @var bool
     * @access private
     */
    private $active;
  	
  	/**
     * roles associated with user
     * @var UserRolesTYPE
     * @access public
     */
    public $roles;
	
	/**
     * permissions associated with user
     * @var UserPermissionsTYPE
     * @access public
     */
    public $permissions;
	
	/**
     * roles user can grant
     * TODO not complete
     * @var UserRolesTYPE
     * @access public
     */
    public $grantableRoles;
	
	/**
     * permissions user can grant
     * TODO not complete
     * @var UserPermissionsTYPE
     * @access public
     */
    public $grantablePermissions;
	
	/**
     * user page history and actions
     * @var UserHistoryTYPE
     * @access public
     */
    public $history;
    
    /**
     * constructor
     * initiates db to the users collection
     * @param customMongo $db
     */    
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
    
	/**
     * getUsername
     * get Username
     * @access public   
     * @return string     
     */    
    public function getUsername(){ return $this->username; }
	
	/**
     * getName
     * get Name
     * @access public   
     * @return string     
     */    
    public function getName(){ return $this->name; }
	
	/**
     * getPassword
     * get Password
     * @access public   
     * @return string     
     */    
    public function getPassword(){ return $this->password;}
	/***/
	
	/**
     * setUsername
     * set username and active or not
     * check that username is unique
     * @access public   
     * @param string $email
     * @param bool $active - optional
     * @return StatusTYPE     
     */    
    public function setUsername( $email, $active=false ){
		$status = new StatusTYPE();	
		if( UserTYPE::uniqueUsername($email, $active) )
			$this->username = $email;  
		else
			$status->setFalse('error: '.NON_UNIQUE);
		return $status; 
	}
	
	/**
     * setName
     * set Name
     * @access public   
     * @param string $name     
     */    
    public function setName($name){ $this->name = $name; }
	
	/**
     * setPassword
     * set Password and hash
     * @access public   
     * @param string $pass     
     */    
    public function setPassword($pass){ if( $pass ) $this->password = md5($pass); }
	
	/**
     * getByUsername
     * get user based on username
     * @access public   
     * @param string $username - optional
     * @return mixed     
     */    
    public function getByUsername($username=null)
    {
        if (!$username)
            $username = $this->username;
		
        parent::get( array('username'=>$username));
		return ( (bool)$this->id );        
    }
	
	/**
     * isActive
     * check if user is active
     * @access public   
     * @return bool     
     */    
    public function isActive(){ return $this->active; }
	
	/**
     * isActive
     * check if user is active
     * @access public   
     * @return bool     
     */    
    public function isInactive(){ return !$this->active; }
	
	/**
     * can
     * check if user has permission
     * @access public   
     * @param string $permission
     * @return bool     
     */    
    public function can($permission){ return ($this->roles->can( $permission ) || $this->permissions->hasPermission( $permission )); }
	
	/**
     * canGrant
     * check if user can give permission
     * @access public   
     * @param string $permission
     * @return bool     
     */    
    public function canGrant($permission){ return (  $this->roles->hasAdmin() || $this->grantableRoles->can( $permission ) || $this->grantablePermissions->hasPermission( $permission )); }
	
	/**
     * isSuper
     * check if user is superUser
     * @access public   
     * @return bool     
     */    
    public function isSuper(){ return $this->roles->hasSuper(); }
	
	/**
     * isAdmin
     * check if user is an admin
     * @access public   
     * @return bool     
     */    
    public function isAdmin(){ return $this->roles->hasAdmin(); }
	/***/
	
	
	/**
     * save
     * before saving checks that username is unique 
     * @access  public
     * @param assocArray $bson
     * @return StatusTYPE
     */
    public function save($bson=null ){
		if ( UserTYPE::uniqueUsername( $this->username, $this->active, $this->getID() ) )	
			return parent::save($bson);
		$status = new StatusTYPE( 'username already exists', false);
		return $status;
	}
	
	/**
     * deactivate
     * deactivate user and save if desired 
     * @access  public
     * @param bool $save - optional
     * @return StatusTYPE|void
     */
    private function deactivate( $save=false ){
		 $this->active = false;
		 if ( $save )
		 	return $this->save();
	}
	
	/**
     * reactivate
     * reactivate user and save if desired 
     * @access  public
     * @param bool $save - optional
     * @return StatusTYPE|void
     */
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
	
	/**
     * mkSuper
     * set user to be superuse 
     * @access  public
     * @param bool $save - optional
     * @return StatusTYPE|void
     */
    public function mkSuper($save=false){
		$this->roles->mkSuper();	
		if ( $save )
		 	return $this->save();
	}
	 
	/**
     * delete
     * delete user sets user to inactive 
     * NOTE: only super users can permanently delete 
     * @access  public
     * @return StatusTYPE
     */
    public function delete(){ return $this->deactivate(true); }                           
    
    /**
     * destroy
     * permanently delete 
     * @access  public
     * @return StatusTYPE
     */
    public function destroy(){ return parent::del(); }
	
	/**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
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
	
	/**
     * read
     * reads from db - clears and handles children's reading
     * calls appropriate method based on $bson type
     * @access  public
     * @param assocArray $bson
     */
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
	
	/**
     * uniqueUsername
     * check if username is unique
     * can return true if an inactive user
     * has same username examples where you may want this
     * john doe is let go and you hire jane doe
     * both emails may be jdoe@companyname.com and
     * john doe is set to inactive 
     * @access  public
     * @param string $username
     * @param bool $active
     * @param string $id
     */
    public function uniqueUsername( $username, $active=false, $id=null ){
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
	
	/**
     * verify
     * verify a user by username and password combo
     * @access  public
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function verify( $username, $password ){
        $tmp = new UserTYPE( $this->db );
        
        //this is an initial setup step
        //if no super users are int he system initiate 
        //first time setup 
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
	
	/**
     * isFirstTime
     * @ignore
     * @access  private
     * @param string $username
     * @param string $password
     * @return bool
     */
    private function isFirstTime($username, $password){
		list( $u, $p ) = explode(':', FIRST_TIME_LOGIN_UP);
		if (( $username == $u ) && ($password == $p)){
			$tmp = new UsersTYPE( $this->db );
			$supers = $tmp->getSupers();
			return 	!$supers->isEmpty();
		}
	}	
	
	/**
     * getByID
     * get user based on id
     * and get the users history
     * @access public   
     * @param string $id - optional
     */    
    public function getByID( $id=null ){
        parent::getByID( $id );
        $this->history->getByUserID( $this->getID() );   
    }
    
    /**
     * get
     * get user based on a query
     * and get the users history
     * @access public   
     * @param string $q
     * @param array $fields
     */    
    public function get( $q, $fields=null ){
        parent::get( $q, $fields );
        $this->getHistory();
    }
    
    /**
     * getHistory
     * get user's history
     * @access public   
     * @param bool $distinct
     */    
    public function getHistory( $distinct=false ){
        $this->history->getByUserID( $this->getID(), $distinct );
    }
    
    /**
     * del
     * delete user from db and delete its history
     * @access public   
     * @return StatusTYPE
     */    
    public function del(){
        $status = parent::del();
        if ( $status->success() )
            $status = $this->history->del();
        return $status;
    }
    
    /**
     * updateHistory
     * upate a user's history
     * @access public   
     * @param string $title
     * @param string $url
     * @param bool $save
     * @return StatusTYPE
     */    
    public function updateHistory( $title, $url, $save=true ){
        return $this->history->update($this->getID(), $title, $url, $save);
    }
    
    /**
     * updateHistoryAction
     * upate a user's actions
     * @access public   
     * @param string $action
     * @param string $classname
     * @param string $id
     * @param bool $save
     * @return StatusTYPE
     */    
    public function updateHistoryAction( $action, $classname, $id, $save=true ){
        $this->history->getByUserID( $this->getID() );
        return $this->history->updateAction($action, $classname, $id, $save);
    }
}

?>
