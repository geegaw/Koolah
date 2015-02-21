<?php
/**
 * SessionUser
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * SessionUser
 * 
 * initiates and retrieves UserTYPE from session
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\koolahObjects
 */
class SessionUser extends UserTYPE{
	
	/**
     * status of connection
     * @var status
     * @access  public
     */		
	public $status;
	
	/**
     * constructor
     * gets user from session     
     * @param customMongo $db
     */    
    public function __construct( $db=null ){
	    parent::__construct($db);    
	    $this->status = new StatusTYPE();	
		if (!session_id())
			session_start();
        if( isset($_SESSION['user']) && $_SESSION['user'] )
		    $this->getFromSession();
		else
		    $this->status->setFalse('Permission Denied: User not signed in');
    }
	
    /**
     * signin
     * checks if username exists and puts user into session
     * @access  public
     * @param string $username
     */    
    public function signin($username){
        if( $this->getByUsername($username) ){
            $this->status = new StatusTYPE();
            if (!session_id())
                session_start();
            $_SESSION['user'] = $this; 
        }
    }
    
    /**
     * signout
     * removes user from session
     * @access  public
     */    
    public function signout(){
        $_SESSION['user'] = null;
        session_unset();
    }
    
    /**
     * getFromSession
     * retrieves user from sesion and initiates UserTYPE
     * @access  public
     */    
    private function getFromSession(){
        $user = unserialize(serialize($_SESSION['user']));
        $bson = $user->prepare();
        $this->read( $bson );
    }
	
	/**
     * flattenPermissions
     * flattens all permissions in roles to permissions
     * @access  public
     */    
    public function flattenPermissions(){
		if ($this->isAdmin())
			return array();
		$permissions = $this->permissions->permissions() ? $this->permissions->permissions() : array();
		if ($this->roles->roles()){
			foreach ($this->roles->roles() as $role)
				$permissions = array_merge($permissions, $role->permissions);
		}
		return $permissions; 
	}
}

?>