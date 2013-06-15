<?php
/**
 * KoolahTag
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */ 
/**
 * KoolahTag grants ajax access to TagTYPE
 * secures TagTYPE with user permissions
 * 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\AjaxAccessObjects
 */ 
class KoolahTag extends TagTYPE{
	
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
     * check session user has persmission modify or create
     * @access  public
     * @param assocArray $bson
     * @return StatusTYPE
     */    
    public function save($bson=null){
		if ( ($this->id && $this->sessionUser->can('m_tags') )
		|| ( !$this->id && $this->sessionUser->can('c_tags') )){
			return parent::save($bson);
		}
		return cmsToolKit::permissionDenied();
	}
		
	/**
     * del
     * check session user has persmission del
     * @access  public
     * @return StatusTYPE
     */    
    public function del(){
		if ( $this->sessionUser->can('d_tags') ) 
		  return parent::del();
		return cmsToolKit::permissionDenied();	
	}
	
}

?>