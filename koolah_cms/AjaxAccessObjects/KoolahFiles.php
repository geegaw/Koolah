<?php 
/**
 * KoolahFiles
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */ 
/**
 * KoolahFiles grants ajax access to FilesTYPE
 * secures FilesTYPE with user permissions
 * 
 * @author Christophe Vaugeois <cvaugeois@koolah.org> 
 * @package koolah\system\AjaxAccessObjects
 */ 
class KoolahFiles extends FilesTYPE{
	
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
	
}

?>