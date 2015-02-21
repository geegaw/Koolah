<?php
/**
 * KoolahSessionUser
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */ 
/**
 * KoolahSessionUser grants ajax access to UserTYPE
 * secures UserTYPE with user permissions
 * 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\AjaxAccessObjects
 */ 
class KoolahSessionUser extends UserTYPE{
	
	/**
     * $sessionUser 
     * @var sessionUser
     * @access  private
     */
    private $sessionUser;
    
    /**
     * status the boolean 
     * @var status
     * @access  public
     */
    public $status;
    
    /**
     * constructor
     * gets the and sets the user from the session
     * sets status the users status 
     * @access  public
     * @param customMongo $db 
     */    
    public function  __construct($db=null){
		$this->sessionUser = new SessionUser( $db );
		$this->status = $this->sessionUser->status;
		if ( $this->status->success() )
			parent::__construct( $db );
	}
	
	/**
     * reactivate
     * check session user is superuser
     * @access  public
     * @param bool $save - false
     * @return StatusTYPE
     */    
    public function reactivate($save=false){
		return cmsToolKit::permissionDenied();
	}
	
	/**
     * save
     * check session user has persmission modify or create
     * @access  public
     * @param assocArray $bson
     * @return StatusTYPE
     */    
    public function save($bson=null){
    	$cleanBson = array();
		if (isset($bson['username']))
			$cleanBson['username'] = $bson['username'];
		if (isset($bson['password']))
			$cleanBson['password'] = $bson['password'];
		return $this->sessionUser->save($cleanBson);
	}
	
	/**
     * get
     * get Session User
     * @access  public
     * @param assocArray $bson
     * @return StatusTYPE
     */    
    public function get($q = null, $fields = null){
    	return $this->sessionUser;
    }
	
	/**
     * getById
     * get Session User
     * @access  public
     * @param assocArray $bson
     * @return StatusTYPE
     */    
    public function getById($id = null){
    	return $this->sessionUser;
    }
	
	/**
     * prepare
     * get Session User
     * @access  public
     * @param assocArray $bson
     * @return StatusTYPE
     */    
    public function prepare($bson = null){
    	$bson = array(
    		'id'=> $this->sessionUser->id,
    		'username' => $this->sessionUser->getUsername(),
    		'name' => $this->sessionUser->getName(),
    		'isAdmin'=> $this->sessionUser->isAdmin(),
    		'isSuper'=> $this->sessionUser->isSuper(),
    		'permissions' => $this->sessionUser->flattenPermissions(),
		);
    	return $bson;
    }
	
		
	/**
     * del
     * check session user has persmission del
     * @access  public
     * @return StatusTYPE
     */    
    public function del(){
		return cmsToolKit::permissionDenied();	
	}
	
}

?>