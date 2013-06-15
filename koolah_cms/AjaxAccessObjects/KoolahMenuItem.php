<?php
/**
 * KoolahMenuItem
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois 
 */ 
/**
 * KoolahMenuItem grants ajax access to MenuItemTYPE
 * secures MenuItemTYPE with user permissions
 * 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\AjaxAccessObjects
 */ 
class KoolahMenuItem extends MenuItemTYPE{
	
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
     * display
     * check session user has required menuitem permission
     * @param string $active
     * @param string $wrapper
     * @param bool $rec
     * @param string $class
     * @param int $levels
     * @access  public
     */    
    public function display( $active='', $wrapper='li', $rec=false, $class='', $levels=-1 ){
		if ( $this->permission &&  $this->sessionUser->can($this->permission) )
			parent::display( $active, $wrapper, $rec, $class, $levels );
	}
}

?>