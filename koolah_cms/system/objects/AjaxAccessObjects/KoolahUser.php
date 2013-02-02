<?php

class KoolahUser extends UserTYPE{
	
	private $sessionUser;
	public $status;
	
	public function  __construct($db){
		$this->sessionUser = new SessionUser( $db );
		$this->status = $this->sessionUser->status;
		if ( $this->status->success() )
			parent::__construct( $db );
	}
	
	public function reactivate($save=false){
		if ( $this->sessionUser->isSuper())
			return parent::reactivate($save);
		return cmsToolKit::permissionDenied();
	}
	
	public function save($bson=null){
		if ( ($this->id && $this->sessionUser->can('m_users') )
		|| ( !$this->id && $this->sessionUser->can('c_users') )){
			return parent::save($bson);
		}
		return cmsToolKit::permissionDenied();
	}
		
	public function del(){
		if ( $this->sessionUser->can('d_users') ){
			if ( $this->sessionUser->isSuper() && $this->isInactive() )
				return parent::destroy();
			return parent::delete();
		}
		return cmsToolKit::permissionDenied();	
	}
	
}

?>