<?php
/**
 * KoolahAlias
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */ 
/**
 * KoolahAlias grants ajax access to AliasTYPE
 * secures AliasTYPE with user permissions
 * 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\AjaxAccessObjects
 */ 
class KoolahAlias extends AliasTYPE{
	
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
     * save
     * disallow save
     * @access  public
     * @param assocArray $bson
     * @return StatusTYPE
     */    
    public function save($bson=null){
		return cmsToolKit::permissionDenied();
	}
		
	/**
     * del
     * disallow del
     * @access  public
     * @return StatusTYPE
     */    
    public function del(){
		return cmsToolKit::permissionDenied();	
	}
	
}

?>