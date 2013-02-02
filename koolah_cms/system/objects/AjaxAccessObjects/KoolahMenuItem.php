<?php

class KoolahMenuItem extends MenuItemTYPE{
	
	private $sessionUser;
	public $status;
	
	public function  __construct($db){
		$this->sessionUser = new SessionUser( $db );
		$this->status = $this->sessionUser->status;
		if ( $this->status->success() )
			parent::__construct( $db );
	}
	public function display( $active='', $wrapper='li', $rec=false, $class='', $levels=-1 ){
		if ( $this->permission &&  $this->sessionUser->can($this->permission) )
			parent::display( $active, $wrapper, $rec, $class, $levels );
	}
}

?>