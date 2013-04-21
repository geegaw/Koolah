<?php

class Koolahmenu extends MenuTYPE{
	
	private $sessionUser;
	public $status;
	
	public function  __construct($db=null){
		$this->sessionUser = new SessionUser( $db );
		$this->status = $this->sessionUser->status;
		if ( $this->status->success() )
			parent::__construct( $db );
	}
	
	public function save($bson=null){
		if ( ($this->id && $this->sessionUser->can('m_menus') )
		|| ( !$this->id && $this->sessionUser->can('c_menus') )){
			return parent::save($bson);
		}
		return cmsToolKit::permissionDenied();
	}
		
	public function del(){
		if ( $this->sessionUser->can('d_menus') ) 
		  return parent::del();
		return cmsToolKit::permissionDenied();	
	}
	
}

?>